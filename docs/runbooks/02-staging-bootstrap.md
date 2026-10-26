# Runbook 02 — Staging bootstrap

Purpose: turn a provisioned host (runbook 01) into a working staging site
carrying a copy of production content, and wire the GitHub `staging`
environment to it.

Run by: the infra owner, as their named admin account, following this
runbook. Every step is journaled under the account that ran it. Verification
by the supervisor is external only — the public HTTPS endpoint and its
`cdpi-build` marker, the GitHub Actions logs, and the state of the GitHub
`staging` environment — so nobody else needs, or has, an account on the host.

Prerequisites: runbook 01 complete; a GHCR read-only token; a production
database dump and uploads archive, copied onto the host by the infra owner;
a stable public address (the Elastic IP, or the current public IPv4 while the
EIP allocation is pending; do not stop/start the instance in that case).

---

## 1. Decide the address mode

| | Subdomain mode (ADR-006) | IP-only mode (deviation, note it in the ADR) |
|---|---|---|
| `SITE_HOST` | `staging.<domain>` | the Elastic IP |
| `CADDY_TLS` | empty | `internal` |
| Certificate | Let's Encrypt, HTTP-01 on port 80 | Caddy's local CA |
| `SMOKE_CA_CERT` | empty | **empty for the first deploy**, then `/opt/cdpi/caddy-root.crt` (step 8; the file exists only after Caddy's first run) |
| Extra work | a DNS A record, TTL 300, **no CDN in front** | export and distribute the Caddy root (step 8) |

Everything else is identical. WordPress stores the URL in the database, so in
IP-only mode the Elastic IP is load-bearing: never release it.

## 2. Copy the compose files and Caddyfile to the host

From a checkout, as your named account:

```bash
rsync -a deploy/compose.yaml deploy/compose.staging.yaml deploy/Caddyfile \
  <you>@<host>:/tmp/
ssh <you>@<host> 'sudo install -o root -g root -m 0644 /tmp/compose.yaml /tmp/compose.staging.yaml /tmp/Caddyfile /opt/cdpi/ && rm -f /tmp/compose.yaml /tmp/compose.staging.yaml /tmp/Caddyfile'
```

`compose.production.yaml` and `compose.shadow.yaml` are not needed on staging.

## 3. Create `/etc/cdpi/*.env`

First, read the table prefix from the production dump (on the laptop, before
copying it over), so `WORDPRESS_TABLE_PREFIX` below matches it:

```bash
zcat ~/cdpi-prod.sql.gz | grep -m1 'CREATE TABLE'
```

All four are root-owned and 0600 inside `/etc/cdpi` (0700). Copy the `.example`
files from the repo as a starting point and fill them in **on the host** —
never send real values through anything but the SSH session.

```bash
sudo -i
umask 077
cd /etc/cdpi
# paste each file's content from deploy/*.env.example, then edit
$EDITOR app.env caddy.env db.env deploy.env
chmod 0600 app.env caddy.env db.env deploy.env
chown root:root app.env caddy.env db.env deploy.env
ls -l /etc/cdpi
```

**No `$` in any value.** docker compose interpolates `$name` inside `env_file`
values: an undefined name becomes empty and every compose call prints
`The "name" variable is not set`. Salts from api.wordpress.org contain `$` and
were silently truncated on the first staging bootstrap, so do not use that
service. Generate the staging salts fresh (do **not** reuse production's) with
this loop, which prints the eight lines ready to paste into `app.env` and
cannot produce a `$`:

```bash
for k in AUTH_KEY SECURE_AUTH_KEY LOGGED_IN_KEY NONCE_KEY \
         AUTH_SALT SECURE_AUTH_SALT LOGGED_IN_SALT NONCE_SALT; do
  printf 'WORDPRESS_%s=%s\n' "$k" "$(openssl rand -base64 48 | tr -d '/+=')"
done
```

Generate the database passwords the same way, one call per password:

```bash
openssl rand -base64 30 | tr -d '/+='
```

`db.env`'s `MYSQL_DATABASE` / `MYSQL_USER` / `MYSQL_PASSWORD` must match
`app.env`'s `WORDPRESS_DB_NAME` / `WORDPRESS_DB_USER` / `WORDPRESS_DB_PASSWORD`.
`WORDPRESS_DB_HOST=db`. `WP_ENVIRONMENT_TYPE=staging`. `WORDPRESS_TABLE_PREFIX`
must match the production dump you are about to import (`wp_` unless discovery
said otherwise).

`deploy.env`: `ENV=staging`, `DB_MODE=container`,
`COMPOSE_FILES="compose.yaml compose.staging.yaml"`, and `SITE_HOST` /
`SMOKE_CA_CERT` per the table in step 1 (IP-only mode: `SMOKE_CA_CERT` stays
empty until step 8).

Finally confirm that no value carries a `$` (`$$` is the escape for a literal
one, should a value ever need it):

```bash
grep -n '\$' /etc/cdpi/*.env    # expect no output
```

## 4. Registry credentials

The infra owner creates a **classic** GitHub personal access token carrying
only the `read:packages` scope (no repo scopes) with a 90-day expiry. Classic
is specified because fine-grained tokens are not reliably accepted by the
container registry for pulls. Then, on the host:

```bash
sudo -i
umask 077
printf 'GHCR_USER=%s\nGHCR_TOKEN=%s\n' '<github-user>' '<token>' > /etc/cdpi/registry.env
chmod 0600 /etc/cdpi/registry.env
chown root:root /etc/cdpi/registry.env
```

Confirm it works without leaving a credential on disk:

```bash
sudo bash -c 'set -a; . /etc/cdpi/registry.env; set +a; DOCKER_CONFIG=$(mktemp -d); export DOCKER_CONFIG; printf "%s" "$GHCR_TOKEN" | docker login ghcr.io -u "$GHCR_USER" --password-stdin; rm -rf "$DOCKER_CONFIG"'
```

The token never crosses an SSH deploy session: only the tag does.

## 5. Deploy key pair

Generate it on the operator's machine, one per environment:

```bash
ssh-keygen -t ed25519 -a 64 -f cdpi-deploy-staging -C deploy@staging -N ''
```

Install the **public** half on the host, root-owned:

```bash
scp cdpi-deploy-staging.pub <you>@<host>:/tmp/
ssh <you>@<host> 'sudo install -o root -g root -m 0644 /tmp/cdpi-deploy-staging.pub /etc/ssh/authorized_keys.d/deploy && rm -f /tmp/cdpi-deploy-staging.pub'
```

Record the host key so the workflow can pin it:

```bash
ssh-keyscan -t ed25519 <host> > known_hosts_staging
cat known_hosts_staging
```

## 6. GitHub `staging` environment

These are exactly the names `.github/workflows/release.yml` reads (through
`.github/actions/remote-deploy`); the deploy job fails on its first step,
naming what is missing, if any of them is unset.

| Kind | Name | Value |
|---|---|---|
| secret | `DEPLOY_SSH_KEY` | private half of the deploy key pair |
| variable | `DEPLOY_HOST` | the Elastic IP (or `staging.<domain>` in subdomain mode); what the runner SSHes to |
| variable | `DEPLOY_USER` | `deploy` |
| variable | `DEPLOY_KNOWN_HOSTS` | the `ssh-keyscan` line from step 5; pins the host key |
| variable | `SITE_URL` | `https://<Elastic IP>` in IP-only mode, `https://staging.<domain>` in subdomain mode. No path, no trailing slash. The runner's smoke check fetches `SITE_URL/` and it becomes the environment's link in GitHub |
| variable | `SMOKE_CA_CERT` | IP-only mode: the Caddy root PEM, set in step 8 once it exists. Subdomain mode: leave unset |

```bash
gh secret   set DEPLOY_SSH_KEY      --env staging < cdpi-deploy-staging
gh variable set DEPLOY_HOST         --env staging --body '<host or elastic ip>'
gh variable set DEPLOY_USER         --env staging --body 'deploy'
gh variable set DEPLOY_KNOWN_HOSTS  --env staging --body "$(cat known_hosts_staging)"
gh variable set SITE_URL            --env staging --body 'https://<host or elastic ip>'
gh variable list --env staging
```

Confirm the wrapper is the only thing reachable:

```bash
ssh -i cdpi-deploy-staging deploy@<host> status        # works
ssh -i cdpi-deploy-staging deploy@<host> 'ls /'        # refused, exit 126
ssh -i cdpi-deploy-staging deploy@<host>               # refused, no shell
```

Keep the private key file until step 13: steps 10 and 12 use it.

## 7. What the first deploy looks like

The image does not exist on the host yet; the first real `deploy` in step 10
is what starts the stack. There is nothing to boot by hand first.

**IP-only mode, read before step 10.** Caddy's local CA, and with it the root
certificate the smoke check verifies against, exists only after Caddy has run
once. So the first `deploy` runs through backup, pull, migration and the
container swap, and then fails at the smoke step:

```
step 7/10 smoke check
smoke: attempt 1/12 HTTP 000
...
smoke: attempt 12/12 HTTP 000
step 8/10 smoke check FAILED for sha-...
no previous tag: the containers are left running as deployed; ...
=== DEPLOY FAILED, NO PREVIOUS TAG TO ROLL BACK TO ===
```

That is expected. The stack is left running, Caddy has now generated its
root, and step 8 exports it. Keep `SMOKE_CA_CERT` **empty** in `deploy.env`
until then: if it points at a file that does not exist yet,
`cdpi-deploy-root` refuses to start at all, before the backup, with a message
saying so. The second `deploy` in step 10 then ends `DEPLOY OK`.

Subdomain mode has no such detour: the first deploy ends `DEPLOY OK`.

If you want the database up before step 10 (step 9 imports into it):

```bash
cd /opt/cdpi
sudo IMAGE_TAG=unused docker compose -f compose.yaml -f compose.staging.yaml up -d db
```

## 8. IP-only mode: export and distribute the Caddy root

Do this between the first `deploy` of step 10, which fails at the smoke step
as step 7 describes, and the second one. Caddy must have run once:

```bash
cd /opt/cdpi
sudo docker compose -f compose.yaml -f compose.staging.yaml exec -T caddy \
  cat /data/caddy/pki/authorities/local/root.crt | sudo tee /opt/cdpi/caddy-root.crt >/dev/null
sudo chmod 0644 /opt/cdpi/caddy-root.crt
openssl x509 -in /opt/cdpi/caddy-root.crt -noout -subject -dates
```

Verify the root against the running site exactly the way the smoke check
does, pinned to this host and without `-k`:

```bash
curl --cacert /opt/cdpi/caddy-root.crt --resolve <ip>:443:127.0.0.1 https://<ip>/ -o /dev/null -w '%{http_code}\n'
```

Expect `200`. A `tlsv1 alert internal error` here from an older curl (8.5 on
Ubuntu 24.04 sends no SNI for an IP literal) means the Caddyfile in
`/opt/cdpi` predates its `default_sni` option: re-copy it per step 2 and
`sudo docker compose -f compose.yaml -f compose.staging.yaml restart caddy`.

Now set `SMOKE_CA_CERT=/opt/cdpi/caddy-root.crt` in `/etc/cdpi/deploy.env`
(it was empty until now).

Each admin fetches it once and trusts it locally:

```bash
scp <you>@<host>:/opt/cdpi/caddy-root.crt ./cdpi-caddy-root.crt
# curl, ad hoc
curl --cacert ./cdpi-caddy-root.crt https://<host>/
# Linux system trust
sudo cp cdpi-caddy-root.crt /usr/local/share/ca-certificates/cdpi-caddy-root.crt && sudo update-ca-certificates
# macOS
sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain cdpi-caddy-root.crt
# Firefox has its own store: Settings -> Privacy & Security -> Certificates -> View Certificates -> Authorities -> Import
```

This root is only trusted for the staging host; it is not a public CA and
signs nothing else. Give the same PEM to the workflow so the runner's smoke
check can verify the certificate too:

```bash
gh variable set SMOKE_CA_CERT --env staging --body "$(cat cdpi-caddy-root.crt)"
```

(A multi-line variable is fine; the workflow writes it back to a file.)

## 9. Import the production content

The infra owner usually has a gzipped dump (`~/cdpi-prod.sql.gz`) and an
uploads **directory** (`~/cdpi-uploads/`, from `rsync`) on the laptop. Create
a staging area on the host and copy both over:

```bash
# from the laptop
ssh <you>@<host> 'sudo install -d -o <you> -m 0700 /var/tmp/prod'
scp ~/cdpi-prod.sql.gz <you>@<host>:/var/tmp/prod/
rsync -az ~/cdpi-uploads/ <you>@<host>:/var/tmp/prod/uploads/
```

Then, on the host:

```bash
cd /opt/cdpi
# IMAGE_TAG=unused: compose.yaml refuses to run at all without IMAGE_TAG, and
# /opt/cdpi/.env does not exist until the first deploy has written it.
DC="env IMAGE_TAG=unused docker compose -f compose.yaml -f compose.staging.yaml"

# database
sudo $DC up -d db
gunzip -c /var/tmp/prod/cdpi-prod.sql.gz | sudo $DC exec -T db sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"'

# uploads into the named volume. Create it with compose's own labels so
# compose owns it: a bare `docker volume create cdpi_uploads` works too, but
# every later compose call then warns `volume "cdpi_uploads" already exists
# but was not created by Docker Compose`.
sudo docker volume create \
  --label com.docker.compose.project=cdpi \
  --label com.docker.compose.volume=uploads \
  --label "com.docker.compose.version=$(sudo docker compose version --short)" \
  cdpi_uploads
MP=$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)
sudo rsync -a /var/tmp/prod/uploads/ "$MP"/
sudo chown -R 33:33 "$MP"    # www-data inside the container is uid/gid 33
sudo du -sh "$MP"
```

Alternative: if the uploads arrived as a tarball instead of a directory,
replace the `rsync` into the volume with
`sudo tar -xzf /var/tmp/prod/uploads.tar.gz -C "$MP" --strip-components=1`
(adjust `--strip-components` to the archive's layout), then run the same
`chown`.

A host that already prints the `was not created by Docker Compose` warning
(its volume was created by hand before this runbook said otherwise) is
working correctly; the warning is cosmetic and safe to live with. To silence
it, recreate the volume from the last pre-deploy snapshot with the stack
stopped, then redeploy the current tag. The snapshot is as of the last
deploy: if media was uploaded since, refresh it first with
`sudo rsync -a "$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)"/ /var/backups/cdpi/uploads-latest/`.

```bash
cd /opt/cdpi && DC="docker compose -f compose.yaml -f compose.staging.yaml"
sudo $DC down && sudo docker volume rm cdpi_uploads
sudo docker volume create --label com.docker.compose.project=cdpi --label com.docker.compose.volume=uploads --label "com.docker.compose.version=$(sudo docker compose version --short)" cdpi_uploads
sudo rsync -a /var/backups/cdpi/uploads-latest/ "$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)"/
sudo /usr/local/sbin/cdpi-deploy-root deploy "$(sudo cat /var/lib/cdpi/current_tag)"
```

(`down` without `-v` keeps the other named volumes; the snapshot was taken
with `rsync -a`, so the `33:33` ownership comes back with it.)

Then delete the production copies from the host:

```bash
sudo shred -u /var/tmp/prod/cdpi-prod.sql.gz ; sudo rm -rf /var/tmp/prod
```

## 10. First deploy

Pick a `sha-` tag that exists in GHCR (the workflow prints it; `docker
manifest inspect ghcr.io/centre-for-dpi/website-v2:sha-<sha>` confirms it).

```bash
ssh -i cdpi-deploy-staging deploy@<host> deploy sha-<40-hex-sha>
```

The host prints all ten steps.

- **Subdomain mode:** expect `=== DEPLOY OK ...`.
- **IP-only mode:** expect this first run to end
  `=== DEPLOY FAILED, NO PREVIOUS TAG TO ROLL BACK TO ===` at step 7, the
  smoke check (`HTTP 000` twelve times), with the stack left running, as step
  7 explains. Now do step 8: export the root, verify `200` with `--cacert`,
  set `SMOKE_CA_CERT` in `deploy.env`. Then run the same command again and
  expect `=== DEPLOY OK ...`. The state files are written only on success, so
  the second run is still "the first deploy" to the script.

Once step 8 is done (IP-only mode) and step 6's variables are all set, run the
same deploy through the pipeline, which is what every merge to `main` will do
from now on:

```bash
gh workflow run release.yml -f image_tag=sha-<40-hex-sha> -f environment=staging
gh run watch "$(gh run list --workflow release.yml -L 1 --json databaseId -q '.[0].databaseId')"
```

Green means the runner reached the host over SSH with the pinned host key,
the wrapper accepted the command, and the runner then saw the marker on
`SITE_URL`. That is the WP6 acceptance test; a red run says which of the
three it was. (If the run shows `deploy-staging` as *skipped*, step 13's
`STAGING_DEPLOYS_ENABLED` is not set yet; set it and dispatch again.)

## 11. Rewrite the URLs and switch off indexing

The imported database still points at the production domain.

```bash
cd /opt/cdpi
DC="docker compose -f compose.yaml -f compose.staging.yaml"
WP="sudo $DC run --rm --user www-data wordpress wp"

$WP search-replace 'https://cdpi.dev' 'https://<staging host>' \
  --all-tables --precise --skip-columns=guid --report-changed-only
$WP option update blog_public 0
$WP option get home; $WP option get siteurl
$WP cache flush
```

`--skip-columns=guid` is deliberate: GUIDs are permanent identifiers, not
links. `blog_public 0` is belt to Caddy's `X-Robots-Tag` (which
`CADDY_ENV=staging` turns on) and the theme's own `noindex`.

If the production site is also reachable over `http://`, repeat the
`search-replace` for the `http://cdpi.dev` form.

## 12. Verify (mirrors the WP5 acceptance criteria)

```bash
# the marker equals the deployed SHA
curl -sS ${CA:+--cacert $CA} https://<host>/ | grep -o 'cdpi-build" content="[0-9a-f]*'

# noindex header
curl -sSI ${CA:+--cacert $CA} https://<host>/ | grep -i x-robots-tag

# certificate: valid Let's Encrypt chain, or chains to the exported root
curl -sS -o /dev/null -w '%{http_code} %{ssl_verify_result}\n' ${CA:+--cacert $CA} https://<host>/

# the deploy account is a dead end
ssh -i cdpi-deploy-staging deploy@<host> status
ssh -i cdpi-deploy-staging deploy@<host> 'ls /'   ; echo "exit=$?"   # expect 126
ssh -i cdpi-deploy-staging deploy@<host>          ; echo "exit=$?"   # expect 126

# nobody used the shared key
ssh <you>@<host> 'sudo journalctl -u ssh | grep "Accepted publickey" ; wtmpdb last ubuntu 2>/dev/null || true'

# host-side state and log
ssh -i cdpi-deploy-staging deploy@<host> status
```

Then by hand in a browser: homepage, a blog post permalink, `/daas/`, the XLSX
export, and an uploaded image. Log into `/wp-admin/` and confirm the editor
works.

## 13. Hand-off

Destroy the private half of the deploy key everywhere except the GitHub
secret. From here on only the workflow can talk to the `deploy` account;
humans use their named accounts and `sudo /usr/local/sbin/cdpi-deploy-root`
(see `rollback.md` §2).

```bash
shred -u cdpi-deploy-staging
```

Record in the ADR folder: instance id, Elastic IP, security group, subnet, AMI,
launch date, the named admin accounts, the key pair name, and where the `.pem`
is vaulted. Note the address mode chosen in step 1 as an ADR-006 deviation if
IP-only was used.

If the Elastic IP changes (it should not), update `DEPLOY_HOST`, `SITE_URL`
and `DEPLOY_KNOWN_HOSTS` in the `staging` environment and the WordPress URLs
(step 11) together.

Finally, switch on automatic staging deploys. Until this **repository**
variable is `true`, `release.yml` skips its `deploy-staging` job (the image
still builds), so merges to `main` before this point never contact the host:

```bash
gh variable set STAGING_DEPLOYS_ENABLED --body true
```

Its production twin, `PRODUCTION_DEPLOYS_ENABLED`, is set at cutover
(runbook 04). Unsetting either flag (or setting it to anything but `true`)
pauses deploys to that environment without touching the workflow.

Next: the next merge to `main` deploys itself to staging; `rollback.md`
covers dispatching an older tag.
