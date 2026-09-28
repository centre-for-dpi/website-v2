# `wp-content-extra/mu-plugins/` — PLACEHOLDER, awaiting review

Everything in this directory is copied verbatim into
`/var/www/html/wp-content/mu-plugins/` in the container image. It is empty on
purpose right now: it holds nothing but this README.

## Why it exists

Production runs three must-use plugins that are **not** public packages, so
Composer cannot install them (`composer.json` covers only the six WPackagist
plugins). From the user's `wp plugin list`:

| Plugin | Version | Notes |
| --- | --- | --- |
| `cdn-cache-purge` | 2.0.0 | Implies a CDN in front of the live site; purging on deploy is a WP9 concern. |
| `wp-cli-login-server` | 1.2 | Grants magic-link admin logins. Review before shipping: it is an authentication bypass by design. |
| `security-helper` | 2.0.0 | Unknown provenance. Must be read line by line before it goes into an image. |

Until they are reviewed and committed here, an image built from this branch
runs **without** them. That is the safe default: shipping unreviewed
third-party PHP into a baked artifact would defeat the point of ADR-004.

## How they get committed

1. The infra owner runs `scripts/host/discover.sh` on the Lightsail box, which
   tars `wp-content/mu-plugins` for review.
2. Read every file. Anything that phones home, writes to the docroot, or
   bypasses authentication gets an explicit decision recorded in the PR, not a
   silent `git add`.
3. Unpack the approved files directly into this directory, preserving layout:
   a top-level `*.php` loader plus any subdirectory it includes. WordPress
   auto-loads **only** top-level `.php` files in `mu-plugins/`; files in
   subdirectories must be `require`d by a top-level loader.
4. Open a PR touching only `wp-content-extra/mu-plugins/**`, so the diff is
   reviewable on its own.

## Why the README is not shipped

`.dockerignore` excludes `**/*.md`, so this file never enters the build
context and cannot reach the image. The directory itself still does (Docker
sends an excluded directory as an empty directory), which is all the
Dockerfile's `COPY` needs; `docker run --rm cdpi-local ls -A
/var/www/html/wp-content/mu-plugins` prints nothing. Git will not track an
empty directory, so this README is also what keeps the directory in the
repository until real mu-plugins land.

## Note for `DISALLOW_FILE_MODS`

`docker/wp-config.php` sets `DISALLOW_FILE_MODS`, which hides the plugin
installer. Must-use plugins are unaffected: they load from disk and cannot be
deactivated from wp-admin, which is exactly why they need review before they
are baked in.
