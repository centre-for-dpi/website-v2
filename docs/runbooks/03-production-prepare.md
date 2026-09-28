# Runbook 03 — Production preparation (no cutover)

> **Status: skeleton. To be completed in WP8**, after runbook 02 has been
> executed end to end on staging — WP5 is what proves these steps are complete
> enough to repeat. Everything below is either a heading to fill in or a
> production-specific delta that is already known. Do not run this runbook
> against production until WP8 fills it in and the supervisor has reviewed it.

Purpose: get the Lightsail production host ready to serve the container image,
verified by a shadow test, **without taking any traffic**. Apache keeps serving
the live site throughout. Cutover is runbook 04 (WP9).

Run by: the infra / release owner, as their named admin account.

---

## 1. Snapshot and freeze — *to be completed in WP8*

- Lightsail snapshot; record the snapshot id.
- `mysqldump` of the live database to `/var/backups/cdpi/pre-prepare.sql.gz`.
- Confirm disk headroom: the image, the uploads volume copy and the backups all
  land on the same 60 GB disk.

## 2. Run install.sh — *see runbook 01, with `--env production`*

```bash
rsync -a --delete deploy/ <you>@<prod-host>:/tmp/deploy/
sudo /tmp/deploy/host/install.sh --check --env production --admins-file /tmp/deploy/host/admins.txt
sudo /tmp/deploy/host/install.sh         --env production --admins-file /tmp/deploy/host/admins.txt
```

Production deltas versus staging:

- `--env production` adds the ufw rule allowing **3306/tcp from
  `172.30.0.0/24`** — the compose subnet — because MySQL stays on the host.
- Ubuntu 22.04/24.04 ship **classic sudo**, so `install.sh` additionally writes
  `/etc/sudoers.d/cdpi-admins-logging` with `log_input`, `log_output`,
  `iolog_dir=/var/log/sudo-io` and `logfile=/var/log/sudo.log`. Full privileged
  session replay is therefore available here, unlike on the sudo-rs staging box.
- `admins.txt` should list the architects and, if the MSP needs host access,
  **one named account per MSP engineer**. No shared or vendor accounts.
- The Lightsail default user is the break-glass account; seal it with
  `--seal-default-user` only after a named login is verified (runbook 01 §6–7).

## 3. Confirm the vendor has no host access — *to be completed in WP8*

## 4. `/etc/cdpi/*.env` — production values

Same shape as runbook 02 §3, with these differences:

| Key | Production value | Why |
|---|---|---|
| `WORDPRESS_DB_HOST` | `host.docker.internal` | MySQL stays on the host; `compose.production.yaml` adds `host.docker.internal:host-gateway` |
| `WORDPRESS_DB_NAME` / `_USER` / `_PASSWORD` | the **existing** live credentials | nothing about the database changes at cutover |
| `WORDPRESS_TABLE_PREFIX` | the **existing** prefix from the live `wp-config.php` | a wrong prefix shows the installer |
| the eight `WORDPRESS_*_KEY` / `_SALT` | copied **verbatim** from the live `wp-config.php` | changing them logs every editor out and breaks password-reset links |
| `WP_HOME` / `WP_SITEURL` | `https://<prod domain>` | must match what is in the database |
| `WP_ENVIRONMENT_TYPE` | `production` | |
| `CADDY_TLS` | empty | Let's Encrypt; production always uses a hostname |
| `CADDY_ENV` | `production` | no `X-Robots-Tag` |
| `SMOKE_CA_CERT` | empty | public certificate chain |
| `DB_MODE` | `host` | |
| `COMPOSE_FILES` | `"compose.yaml compose.production.yaml"` | |

`db.env` is **not** installed on production.

## 5. `/etc/cdpi/my.cnf` for backups

`cdpi-deploy-root` runs `mysqldump --defaults-extra-file=/etc/cdpi/my.cnf` in
host mode, so the credentials never appear in the process list.

```bash
sudo -i
umask 077
cat > /etc/cdpi/my.cnf <<'EOF'
[client]
user=cdpi_backup
password=<backup account password>
host=127.0.0.1
EOF
chmod 0600 /etc/cdpi/my.cnf
chown root:root /etc/cdpi/my.cnf
```

A dedicated read-only-ish backup account is preferable to root:

```sql
CREATE USER 'cdpi_backup'@'localhost' IDENTIFIED BY '<password>';
GRANT SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER, PROCESS
  ON *.* TO 'cdpi_backup'@'localhost';
```

Verify before relying on it:

```bash
sudo mysqldump --defaults-extra-file=/etc/cdpi/my.cnf --single-transaction --quick \
  --routines --triggers <dbname> | gzip -c | wc -c
```

## 6. Make the host's MySQL reachable from the container

Two options. **Pick one and record which** — they have different blast radii.

**Option A — TCP on the bridge (what `compose.production.yaml` assumes):**

```sql
CREATE USER '<wpuser>'@'172.30.0.%' IDENTIFIED BY '<same password as app.env>';
GRANT ALL PRIVILEGES ON <dbname>.* TO '<wpuser>'@'172.30.0.%';
FLUSH PRIVILEGES;
```

and widen `bind-address` in `/etc/mysql/mysql.conf.d/mysqld.cnf` from
`127.0.0.1` to also cover the docker bridge address (or `0.0.0.0`, relying on
ufw — the `--env production` rule allows 3306 only from `172.30.0.0/24`).
Restarting MySQL is a brief interruption to the live site: do it in a window.

**Option B — socket bind-mount (lower touch, no `bind-address` change, no ufw
rule, no new grant host):** bind-mount `/var/run/mysqld/mysqld.sock` into the
container and set `WORDPRESS_DB_HOST=localhost:/var/run/mysqld/mysqld.sock`.
This needs an extra `volumes:` entry, which is a one-line addition to
`compose.production.yaml`. *Decision and the compose change: WP8.*

## 7. Pre-seed the uploads volume — *to be completed in WP8*

```bash
sudo docker volume create cdpi_uploads
MP=$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)
sudo rsync -a /var/www/cdpi-website/wp-content/uploads/ "$MP/"
sudo chown -R 33:33 "$MP"
```

(The final `rsync --delete` happens in the cutover window, runbook 04.)

## 8. Production deploy key and the `production` environment — *as runbook 02 §5–6, with `--env production`*

Plus: the `production` GitHub environment has required reviewers
(`adammwaniki`, `justMuriithi`) and the deploy job stays inert until the repo
variable `PRODUCTION_DEPLOYS_ENABLED` is set at cutover.

## 9. Shadow test — the point of this runbook

Runs the new image on loopback only, while Apache keeps serving the live site.
`compose.shadow.yaml` parks Caddy in an inactive profile so nothing contends
for ports 80/443, publishes the app on `127.0.0.1:8080` and disables WP cron.

```bash
cd /opt/cdpi
DC="docker compose -f compose.yaml -f compose.production.yaml -f compose.shadow.yaml"
# -V (--renew-anon-volumes) is mandatory: the base image declares VOLUME
# /var/www/html and a plain `up -d` would re-attach the previous shadow
# run's anonymous volume, i.e. serve the previous tag's files. See
# deploy/README.md § "The base image's anonymous volume".
sudo IMAGE_TAG=sha-<sha> $DC up -d -V
sudo $DC ps

curl -sS -o /tmp/shadow.html -w '%{http_code}\n' \
  -H 'Host: <prod domain>' -H 'X-Forwarded-Proto: https' http://127.0.0.1:8080/
grep -o 'cdpi-build" content="[0-9a-f]*' /tmp/shadow.html
```

Also check `/daas/`, a blog permalink, the XLSX export, and an admin login
through the same `Host:` header. Then:

```bash
sudo $DC down        # NOT -v: the uploads volume must survive
```

**Critical check before cutover:** the image's WordPress core version must
equal `wp core version` on the host, so `wp core update-db` is a no-op at
cutover and the database is never migrated forward under the old Apache site.

```bash
sudo -u www-data wp --path=/var/www/cdpi-website core version
sudo IMAGE_TAG=sha-<sha> $DC run --rm --no-deps --user www-data wordpress wp core version
```

## 10. Acceptance — *to be completed in WP8*

- shadow `curl` output: HTTP 200 plus the expected marker;
- `ssh deploy@prod status` works from a runner;
- `mysqldump` via `my.cnf` produces a non-empty dump;
- image core version == host core version;
- disk headroom confirmed;
- `wtmpdb last`/journal shows no default-account logins without a matching
  `BREAKGLASS.md` entry.

## 11. Open questions for WP8

- Option A or Option B in §6.
- Whether the CDN in front of the site needs a configuration change (the
  `cdn-cache-purge` mu-plugin implies one exists); the smoke check already uses
  `--resolve`, so it is unaffected, but a browser check may be served stale.
- Whether the MSP needs named host accounts at all.

Next: **runbook 04 — production cutover** (WP9).
