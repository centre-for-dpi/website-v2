# deploy/ — host-side deploy tooling (WP4)

Nothing here runs in CI and nothing here is deployed by merging this PR. These
are the files that get copied onto the two hosts, plus the scripts those hosts
run when GitHub Actions asks them to deploy.

The pipeline's whole interface to a host is three commands over SSH:

```
ssh deploy@<host> deploy <sha-…40hex | vX.Y.Z>
ssh deploy@<host> rollback
ssh deploy@<host> status
```

Nothing else is accepted, there is no shell on the other end, and no secret
crosses the SSH session — only the tag. Registry credentials are root-only on
each host.

---

## Map of files

### Compose stack — copied to `/opt/cdpi/`

| File | Which host | What it adds |
|---|---|---|
| `compose.yaml` | both | `caddy` (pinned `caddy:2.11.4`, ports 80/443/443-udp, `caddy_data` for certificates) and `wordpress` (`ghcr.io/centre-for-dpi/website-v2:${IMAGE_TAG}`, `uploads` volume, `tmpfs /tmp`, curl healthcheck, capped json-file logs). Project name pinned to `cdpi`; network pinned to `172.30.0.0/24`. **`wordpress` publishes no host port.** |
| `compose.staging.yaml` | staging | `db` = `mysql:8.4.11` with `--innodb-buffer-pool-size=256M`, `db_data` volume, `mysqladmin ping` healthcheck; `wordpress` waits for `db` to be healthy |
| `compose.production.yaml` | production | `wordpress.extra_hosts: host.docker.internal:host-gateway` — MySQL stays on the host and is never managed by compose |
| `compose.shadow.yaml` | production, WP8 only | parks `caddy` in an inactive profile, publishes `127.0.0.1:8080:80`, `DISABLE_WP_CRON=true`. Lets the new image be proven while Apache still serves the live site |
| `Caddyfile` | both | one file for both environments; see "Two design choices" below |

### Environment files — created by hand at `/etc/cdpi/`, root:root 0600

The repo carries only `.example` files documenting the keys (ADR-005 — keys in
the repo, values never).

| Example | Installs as | Read by |
|---|---|---|
| `app.env.example` | `/etc/cdpi/app.env` | the `wordpress` container |
| `caddy.env.example` | `/etc/cdpi/caddy.env` | the `caddy` container (`SITE_HOST`, `CADDY_TLS`, `CADDY_ENV`) |
| `db.env.example` | `/etc/cdpi/db.env` — **staging only** | the `db` container |
| `deploy.env.example` | `/etc/cdpi/deploy.env` | `cdpi-deploy-root` (bash-sourced, so quote values with spaces) |
| — | `/etc/cdpi/registry.env` | `cdpi-deploy-root`, for `docker login ghcr.io`. Created in runbook 02 §4; `GHCR_USER` + `GHCR_TOKEN` (fine-grained `read:packages`) |
| — | `/etc/cdpi/my.cnf` — **production only** | host-mode `mysqldump`. Runbook 03 §5 |

`compose.yaml` refers to these as `${CDPI_ETC:-/etc/cdpi}/…` so CI and local
validation can point them at a throwaway directory. On a host `CDPI_ETC` is
never set.

### Host scripts and configuration — `deploy/host/`

| File | Installs as | Notes |
|---|---|---|
| `install.sh` | run from `/tmp/deploy/host/` | idempotent provisioning; `--check`, `--env`, `--admins-file`, `--seal-default-user` |
| `cdpi-deploy` | `/usr/local/bin/cdpi-deploy` 0755 root | sshd `ForceCommand` wrapper, runs as `deploy`. Parses `SSH_ORIGINAL_COMMAND`, no `eval`, exits 126 on anything unexpected |
| `cdpi-deploy-root` | `/usr/local/sbin/cdpi-deploy-root` 0750 root | the ten-step deploy, reached only via `sudo -n`. `flock`, `--dry-run`. **The only supported way to (re)start `wordpress`** — see "The base image's anonymous volume" below |
| `cdpi-breakglass-notify` | `/usr/local/sbin/` 0750 root | `pam_exec` hook; alerts on every break-glass login |
| `sudoers.d-cdpi-deploy` | `/etc/sudoers.d/cdpi-deploy` 0440 root | one command, no wildcard; `visudo -cf`-validated before it is moved into place |
| `sshd_config.d-cdpi-keys.conf` | `/etc/ssh/sshd_config.d/01-cdpi-keys.conf` | root-owned `AuthorizedKeysFile` |
| `sshd_config.d-cdpi-deploy.conf` | `/etc/ssh/sshd_config.d/10-cdpi-deploy.conf` | `Match User deploy` → `ForceCommand`, no PTY, no forwarding |
| `admins.txt` | source of truth, not installed | `username<TAB>key`. One line per human; the `deploy` user and the break-glass account are deliberately absent |
| `test-cdpi-deploy.sh` | not installed | 26-case parser self-test; runs without root or sudo |

`install.sh` also writes, from templates inside itself:
`/etc/ssh/sshd_config.d/00-cdpi-access.conf` (only if absent — cloud-init owns
it on staging), `/etc/ssh/authorized_keys.d/<user>` per admin,
`/etc/sudoers.d/cdpi-admins` (+`-logging` on classic-sudo hosts),
`/etc/fail2ban/jail.d/cdpi.conf`, `/etc/audit/rules.d/cdpi.rules`,
`/etc/logrotate.d/cdpi-deploy`, `/etc/sysctl.d/99-cdpi-swappiness.conf`, and,
when sealing, `/etc/ssh/breakglass-banner` and
`/etc/ssh/sshd_config.d/20-cdpi-breakglass.conf`.

### Host state written at runtime

| Path | What |
|---|---|
| `/opt/cdpi/.env` | `IMAGE_TAG=<tag>`, rewritten by every deploy |
| `/var/lib/cdpi/current_tag`, `previous_tag` | what `status` and `rollback` read |
| `/var/log/cdpi-deploy.log` | every step, also sent to the journal under tag `cdpi-deploy`; rotated weekly, 12 kept |
| `/var/backups/cdpi/` | pre-deploy dumps and hard-linked uploads snapshots |

### Which host gets what

**Staging** (EC2, Ubuntu 26.04, sudo-rs): `compose.yaml`,
`compose.staging.yaml`, `Caddyfile` in `/opt/cdpi`; `app.env`, `caddy.env`,
`db.env`, `deploy.env`, `registry.env` in `/etc/cdpi`; `DB_MODE=container`.
No `my.cnf`, no production/shadow overlays.

**Production** (Lightsail, Ubuntu 22.04/24.04, classic sudo): `compose.yaml`,
`compose.production.yaml`, `compose.shadow.yaml`, `Caddyfile` in `/opt/cdpi`;
`app.env`, `caddy.env`, `deploy.env`, `registry.env`, `my.cnf` in `/etc/cdpi`;
`DB_MODE=host`; `install.sh --env production`. No `db.env`.

### Runbooks

`docs/runbooks/01-provision-host.md` · `02-staging-bootstrap.md` ·
`03-production-prepare.md` (skeleton, filled in WP8) · `rollback.md` ·
`backup-restore.md`.

---

## Three things worth knowing about

### The base image's anonymous `/var/www/html` volume — never `up -d` by hand

`wordpress:*-apache` declares `VOLUME /var/www/html`. Our image bakes the
site into that path, but the declaration still makes Docker mount an
**anonymous volume** there on the container's first start, seeded from the
image. On a recreate — `docker compose up -d` for a new `IMAGE_TAG`, even
with `--force-recreate` — compose **re-attaches the same anonymous volume**,
so the container keeps serving the *old* docroot; only the ENV changes. The
`cdpi-build` smoke marker is an ENV value, not a file, so it reports the new
SHA while the old files are served.

`cdpi-deploy-root` therefore swaps the container with
`up -d --no-build --no-deps --renew-anon-volumes wordpress`, which creates a
fresh anonymous volume seeded from the new image (scoped to `wordpress`
because `-V` also force-recreates every service it selects — unscoped it
would restart MySQL and Caddy on each deploy), follows it with a plain
`up -d` for anything not yet running, and then removes the orphaned volume with
`docker volume prune -f --filter label=com.docker.volume.anonymous` (step 10;
anonymous and unreferenced only — `cdpi_uploads`, `cdpi_db_data`,
`cdpi_caddy_data`, `cdpi_caddy_config` are named and never candidates).
`docker compose run --rm` (the migration step) removes its container's
anonymous volume itself.

Consequences:

- **A manual `docker compose up -d` without `-V` is not a valid redeploy**,
  and neither is `restart` or `--force-recreate`. Use
  `ssh deploy@<host> deploy <tag>` / `rollback`, which is the same code path
  and the only supported one.
- Do not "fix" this by mounting a bind or named volume at `/var/www/html`:
  that would pin the docroot to whatever was copied in first and defeat
  image-based deploys altogether. `uploads` is mounted *inside* it and is the
  only persistent path by design.
- Proven in PR #5 "Review fix 2": a file planted in `/var/www/html` survived
  `--force-recreate` on the same volume id and was gone only with `-V`.

### One Caddyfile, snippets selected by environment variable

`{$VAR}` in a Caddyfile is substituted **textually before parsing**, so the
value can choose which snippet gets imported:

```
import tls_{$CADDY_TLS:}          # tls_internal -> `tls internal`; tls_ -> empty
import robots_{$CADDY_ENV:production}
```

This avoids the two traps in the obvious approaches. `tls` with an empty
argument is a parse error, so `tls {$CADDY_TLS}` cannot express "no tls
directive". And `header X-Robots-Tag {$CADDY_ROBOTS:""}` would emit an *empty*
`X-Robots-Tag` header on production rather than none. A wrong value fails loudly
at startup (`File to import not found: tls_bogus`), which is the behaviour you
want: a typo must not silently publish staging to search engines or silently
skip TLS.

### Shadow mode parks Caddy in an inactive profile

`compose.shadow.yaml` sets `caddy: {profiles: [disabled]}`. Because `disabled`
is never passed to `--profile`, `up` starts `wordpress` alone, so nothing
contends for ports 80/443 while Apache still serves the live production site.
`docker compose config --services` on the shadow overlay prints exactly
`wordpress`, which is the check that it worked. `deploy.replicas: 0` was the
alternative; it needs swarm semantics that plain `compose up` ignores.

---

## How deploys are triggered (`.github/workflows/release.yml`)

Nothing on a host polls anything. A deploy happens only when a GitHub Actions
job, bound to the `staging` or `production` environment, opens one SSH session
to the host and sends `deploy <tag>`:

| Trigger | What runs | Tag deployed |
|---|---|---|
| merge to `main` (push) | `build` publishes `sha-<sha>` to GHCR, then `deploy-staging` | the tag `build` just produced |
| `gh workflow run release.yml -f image_tag=<tag> -f environment=staging` | `validate`, then `deploy-staging` | the tag you named (rollback or redeploy) |
| `gh workflow run release.yml -f image_tag=<tag> -f environment=production` | `validate`, then `deploy-production` after a required reviewer approves | the tag you named |

Production is never reached from a push. Each deploy job is skipped (not
failed) until its repository variable is `true`: `STAGING_DEPLOYS_ENABLED`
(last step of runbook 02, after the manual first deploy succeeds) and
`PRODUCTION_DEPLOYS_ENABLED` (cutover, runbook 04). Until then a run stops
after `build` or `validate` without contacting any host; clearing a flag
pauses deploys to that environment.

Both deploy jobs call the composite action `.github/actions/remote-deploy`:
it writes the environment's `DEPLOY_SSH_KEY` to a 0600 file, pins the host
key from `DEPLOY_KNOWN_HOSTS` (`StrictHostKeyChecking=yes`), runs
`ssh deploy@DEPLOY_HOST deploy <tag>` and streams the host's log, then fetches
`SITE_URL/` from the runner (up to 10 tries, 6 s apart, connection pinned to
`DEPLOY_HOST` so a CDN cannot answer, `--cacert SMOKE_CA_CERT` when set) until
it sees HTTP 200 and `<meta name="cdpi-build" content="<sha>">`. The host-side
script has already done its own smoke and automatic rollback by then; the
runner's check is the outside view. What each environment must define is in
runbook 02 §6 (staging) and 03 §8 (production).

The static checks over this directory (shellcheck, the wrapper self-test,
`compose config`, `caddy validate`) run on every pull request in the
`deploy-tooling` job of `.github/workflows/ci.yml`.

---

## Local validation run for this PR

All of the following were run on the builder machine (Docker 29.8.1, compose
v5.5.1; no Node and no aws CLI here, neither is needed).

```bash
# 1. shellcheck — clean, exit 0, no output
docker run --rm -v "$PWD":/w -w /w koalaman/shellcheck:stable \
  deploy/host/cdpi-deploy deploy/host/cdpi-deploy-root \
  deploy/host/cdpi-breakglass-notify deploy/host/install.sh \
  deploy/host/test-cdpi-deploy.sh

# 2. the wrapper parser self-test — 26 cases, 0 failures
./deploy/host/test-cdpi-deploy.sh

# 3. compose config, all three overlays, with throwaway env files via CDPI_ETC
#    (see the WP6 snippet above for the exact commands)

# 4. Caddyfile: caddy validate with CADDY_TLS=internal and with it unset;
#    caddy adapt to prove the JSON actually differs (internal issuer present /
#    absent, X-Robots-Tag present / absent); caddy fmt --diff clean;
#    an invalid CADDY_TLS value fails loudly

# 5. cdpi-deploy-root --dry-run for a sha- tag (container DB) and a v- tag
#    (host DB), plus rollback, argument rejection, and the flock contention case

# 6. install.sh in a throwaway ubuntu:24.04 container, with
#    CDPI_INSTALL_SKIP="apt docker swap ufw fail2ban auditd":
#    --check, apply, apply again (0 changes), sshd -T verification,
#    visudo -c, seal refusal, and key revocation

# 7. the staging shape brought up end to end against a placeholder image
#    (wordpress:6.8-apache retagged), CADDY_TLS=internal, SITE_HOST=localhost:
#    HTTP 200 on the installer page through the internal certificate,
#    X-Robots-Tag present, Server header stripped, HTTP->HTTPS redirect,
#    then `down -v`

# 8. (Review fix 2) the root script run for real inside an isolated
#    docker:dind daemon against two locally tagged placeholder images:
#    deploy sha-<zeros>, plant a file in /var/www/html, deploy sha-<ones>;
#    the planted file is gone, the served marker changes, and
#    `docker volume ls -qf dangling=true` is empty afterwards. See the PR.
```

What could **not** be exercised here, and is therefore WP5's job on the real
staging host: the apt/Docker/ufw/fail2ban/auditd steps of `install.sh` (they
need systemd and a real network), `--seal-default-user` past its refusal check,
the `pam_exec` break-glass hook firing on a real login, `docker login ghcr.io`
with a real token, `mysqldump` in either mode against real data, the smoke check
against the real image's `cdpi-build` marker, and image pruning.
