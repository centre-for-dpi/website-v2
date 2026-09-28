# Runbook 00 — Production host discovery

Purpose: collect the facts the containerisation work (WP3 onwards) needs from
the live Lightsail box, without changing anything on it.

`scripts/host/discover.sh` is read-only. It reads versions, options, config and
headers and prints them. The only file it creates is a tarball of
`wp-content/mu-plugins` under `/tmp`, because the must-use plugins are not
public packages and have to be copied from production into the image.

Run by: the infra / release owner (you), as your named admin account.

## 1. Copy the script to the box

From a machine with a checkout of this repo:

```bash
scp scripts/host/discover.sh <you>@<prod-host>:/tmp/discover.sh
```

Or, if you are already on the box, paste it into `/tmp/discover.sh`. Then:

```bash
chmod +x /tmp/discover.sh
```

## 2. Run it

The first argument is the WordPress document root. It defaults to
`/var/www/cdpi-website`; pass a different path if yours differs.

```bash
sudo /tmp/discover.sh 2>&1 | tee /tmp/cdpi-discovery-$(date +%F).txt
```

`sudo` is used so `wp-config.php`, the Apache vhosts and `/etc/cron.d` are
readable. The script adds `--allow-root` to its `wp` calls only when it is
actually running as uid 0.

Every section is guarded by `command -v`, so missing tools are reported as
`(not available: …)` rather than aborting the run. It always reaches the
`discover.sh finished` heading — if it does not, that itself is worth
reporting.

## 3. Send back two things

1. The captured output, `/tmp/cdpi-discovery-<date>.txt`.
2. The tarball named on the script's last line,
   `/tmp/cdpi-mu-plugins-<date>.tgz`.

Both are safe to share with the project team, but note the output contains the
database name and user (their **values for any key containing KEY, SALT,
PASSWORD or PASS are masked as `***`** before printing). Skim it once before
sending.

After they have been collected, delete both from `/tmp`.

## 4. What the output is used for

| Output | Used for |
|---|---|
| `wp core version`, `php -v` | Choosing the pinned base image tag, e.g. `wordpress:<core>-php<php>-apache`. The image core version must equal the host's so `wp core update-db` is a no-op at cutover. |
| `php -i` limits (`upload_max_filesize`, `post_max_size`, `memory_limit`, `max_execution_time`) | `docker/php/cdpi.ini` in the image, so media uploads and long admin requests keep working. |
| `wp option get stylesheet` / `template` | The theme directory name to install the built theme into inside the image. |
| `wp option get siteurl` / `home` / `permalink_structure` / `blog_public` / `upload_path` | `WP_HOME` / `WP_SITEURL`, rewrite rules, and the staging `blog_public 0` step. |
| `table_prefix`, `define(` lines | `/etc/cdpi/app.env` on the host — existing DB credentials, table prefix and salts, so logged-in sessions survive cutover. |
| `.htaccess`, Apache vhosts | Rules that must be reproduced in the Caddyfile or kept in the container's `.htaccess`. |
| mu-plugins listing + tarball | `wp-content-extra/mu-plugins/` in the repo, committed after review, baked into the image. |
| `mysql --version`, `wp db size --tables` | MySQL major version for the staging container, and backup/restore sizing. |
| cron, certbot timers | What has to be disabled at cutover (WP9 step 5) and what must be reproduced. |
| `du -sh wp-content/uploads`, `df -h`, `free -m` | Uploads volume pre-seed time, disk headroom for images and backups, swap sizing. |
| CDN header check | Confirms the CDN implied by the `cdn-cache-purge` must-use plugin, and which headers identify it (so the smoke check uses `curl --resolve` and cannot be fooled by a cached copy). |

## 5. Plugin inventory already provided

You supplied `wp plugin list` before this runbook existed, so the script's
plugin section is a re-confirmation rather than new information. For reference,
the inventory it should match:

| Plugin | Version | Status | Notes |
|---|---|---|---|
| advanced-custom-fields | 6.8.1 | active | Free edition, **not** Pro — no licence secret needed |
| custom-post-type-ui | 1.19.2 | active | All 12 CPTs live only in the DB via this plugin |
| duracelltomi-google-tag-manager | 1.22.3 | active | |
| mailchimp-for-wp | 4.12.5 | active | |
| redirection | 5.7.5 | active | |
| wordpress-importer | 0.9.5 | active | |
| akismet | — | inactive | Excluded from the image |
| contact-form-7 | — | inactive | Excluded from the image |
| hello | — | inactive | Excluded from the image |
| cdn-cache-purge | 2.0.0 | must-use | Not a public package — copy from prod. Implies a CDN in front of the site |
| wp-cli-login-server | 1.2 | must-use | Not a public package — copy from prod |
| security-helper | 2.0.0 | must-use | Not a public package — copy from prod |

All six active plugins exist on WPackagist, so they are installed by
`composer install` at image build time with no secrets. The three must-use
plugins can only come from the tarball in step 3.
