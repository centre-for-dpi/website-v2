# Runbook 20 — The container image

Purpose: how the `cdpi/website-v2` image is built, what is baked into it,
how to build and boot it locally, and which values are still placeholders
awaiting the production discovery run (runbook 00).

Related: `Dockerfile`, `docker/`, `composer.json`, `compose.dev.yaml`,
`.github/workflows/release.yml`, ADR-004.

## 1. What the image is

One image, built once per merge to `main`, tagged `sha-<40-hex commit>` on
GHCR, deployed unchanged to staging and production (ADR-004). It contains:

| Layer | Source | Notes |
| --- | --- | --- |
| WordPress core + Apache + PHP | `wordpress:${WP_IMAGE_TAG}` (official image) | Core is copied from `/usr/src/wordpress` into `/var/www/html` **at build time**, so the official entrypoint's first-run copy never happens. |
| `wp-config.php` | `docker/wp-config.php` | Upstream `wp-config-docker.php` plus `WP_HOME`/`WP_SITEURL` from env, `DISALLOW_FILE_MODS`, auto-updates off, `DISABLE_WP_CRON` from env. Every value comes from the environment; nothing secret is in the file. |
| Plugins | `composer.json` / `composer.lock` via WPackagist | Six plugins pinned to production's exact versions. `composer validate --strict --no-check-all` in the build fails if the lock is stale. |
| Must-use plugins | `wp-content-extra/mu-plugins/` | Empty placeholder until production's three mu-plugins are reviewed and committed (section 6). |
| Theme | this repository, built in the `theme` stage | `npm ci && npm run build` in `node:22-bookworm-slim`, then only `style.css`, `screenshot.png`, root `*.php`, `src/`, `templates/`, `public/` ship. Committed `public/js`, `public/css` and the manifest are excluded by `.dockerignore` and rebuilt. |
| WP-CLI | `wordpress:${WP_CLI_IMAGE_TAG}` | `/usr/local/bin/wp`, for `wp core update-db` during deploys and for local setup. |
| `php.ini` overrides | `docker/php/cdpi.ini` | Upload/memory/time limits templated from build ARGs; errors to stderr; opcache without timestamp validation (the docroot never changes at runtime). |
| Build identity | `ARG GIT_SHA` | `ENV CDPI_BUILD_SHA`, OCI `revision` label, and `<meta name="cdpi-build" content="<sha>">` in every page (`functions.php`). |

Ownership inside the image: everything under `/var/www/html` is `root:root`,
directories 755, files 644. The only path `www-data` can write is
`wp-content/uploads`, which is a named volume at runtime. With
`DISALLOW_FILE_MODS` this is what makes the running container tamper-resistant:
an in-place edit of the docroot cannot be made to stick past a restart.

## 2. Placeholders awaiting the discovery run

These are the exact lines to change once `scripts/host/discover.sh` output is
in hand. Each is a one-line edit to a default; nothing else needs to move.

```dockerfile
ARG WP_IMAGE_TAG=6.8-php8.2-apache      # -> <wp core version>-php<php major.minor>-apache
ARG WP_CLI_IMAGE_TAG=cli-2.12.0-php8.2  # -> keep the php suffix in step with WP_IMAGE_TAG
ARG THEME_SLUG=cdpi                     # -> `wp option get stylesheet` on production, verbatim
ARG PHP_UPLOAD_MAX_FILESIZE=64M         # -> discover.sh "php.ini limits"
ARG PHP_POST_MAX_SIZE=64M
ARG PHP_MEMORY_LIMIT=256M
ARG PHP_MAX_EXECUTION_TIME=120
```

Before changing an image tag, confirm it exists:

```bash
curl -s -o /dev/null -w '%{http_code}\n' \
  "https://hub.docker.com/v2/repositories/library/wordpress/tags/<tag>"   # 200 = exists
```

### `THEME_SLUG` — read this before cutover

WordPress finds the active theme by directory name: the `stylesheet` (and
`template`) option in the database names the directory under
`wp-content/themes/`. The image installs the theme at
`wp-content/themes/${THEME_SLUG}/`. **If `THEME_SLUG` does not equal
production's `stylesheet` option, the site boots with no theme** (WordPress
falls back to a default theme, which the image deliberately does not ship, so
the front end renders raw). The current default `cdpi` is a guess. Set it from
`discover.sh` output (`wp option get stylesheet`) and do not rename it later
without a matching `wp theme activate` in the same deploy.

The value is also what the deploy smoke check implicitly tests: the
stylesheet URL in the served page is
`/wp-content/themes/<THEME_SLUG>/public/css/style.<hash>.css` and must return
200.

## 3. Build locally

Requires Docker with BuildKit (any current Docker Desktop or Engine). No Node,
PHP or Composer on the machine: every tool runs inside the build.

```bash
docker build --build-arg GIT_SHA=$(git rev-parse HEAD) -t cdpi-local .
```

To build only the theme stage (what CI's `image-theme-stage` job does) and see
the shipped tree and asset hashes:

```bash
docker build --target theme --progress=plain . 2>&1 | grep -A40 'theme tree shipped'
```

The hashes printed (`bundle.<hash>.js`, `style.<hash>.css`) must equal the
ones committed under `public/` until WP10 removes the committed output; that
is the proof that CI builds match the vendor's local builds.

Quick checks on the built image:

```bash
docker run --rm cdpi-local wp --info --allow-root | head -5
docker run --rm --user www-data cdpi-local find /var/www/html -writable -maxdepth 3   # uploads only
docker run --rm cdpi-local ls -la /var/www/html/wp-content/themes /var/www/html/wp-content/plugins
docker history cdpi-local            # no secrets in any layer: there are none to leak
```

## 4. Boot locally with `compose.dev.yaml`

`compose.dev.yaml` is a local-only stack: `mysql:8.4.11` plus the image built
from this checkout, published on `127.0.0.1:8080`, with the same uploads mount
point and healthcheck as the deploy stack. It is never used on a host. If
8080 is taken, `export CDPI_DEV_PORT=8087` before every command below and use
that port in the URLs.

```bash
docker compose -f compose.dev.yaml up -d --build
docker compose -f compose.dev.yaml ps          # wait for wordpress to be "healthy"
```

Install WordPress and activate the theme and plugins with WP-CLI, running as
`www-data` exactly as the deploy script does:

```bash
DC="docker compose -f compose.dev.yaml"
$DC run --rm --user www-data wordpress wp core install \
  --url=http://localhost:8080 --title='CDPI dev' \
  --admin_user=admin --admin_password=admin --admin_email=dev@example.invalid \
  --skip-email
$DC run --rm --user www-data wordpress wp theme activate cdpi     # = THEME_SLUG
$DC run --rm --user www-data wordpress wp plugin activate --all
$DC run --rm --user www-data wordpress wp rewrite structure '/%postname%/'
$DC run --rm --user www-data wordpress wp core update-db
```

(No `--hard`: the image ships the official image's `.htaccess` with the
standard rewrite rules, the docroot is read-only for `www-data`, and pretty
permalinks work without rewriting it.)

Then confirm what the deploy smoke check will look for:

```bash
curl -s http://localhost:8080/ | grep -o '<meta name="cdpi-build" content="[^"]*">'
curl -s http://localhost:8080/ | grep -o '/wp-content/themes/[^"]*\.css'
curl -sI http://localhost:8080/ | grep -i x-robots-tag       # noindex: WP_ENVIRONMENT_TYPE=local
```

About the healthcheck: with the `X-Forwarded-Proto: https` header and an
`http://` `WP_HOME`, WordPress answers the probe with a canonical 301 (to the
https URL). `curl -f` treats a 3xx as success, so the container still reports
healthy; the check proves Apache, PHP and WordPress boot, not that the site
renders. On the hosts `WP_HOME` is `https://…` and the same probe returns 200.

Optional: see PHP notices and deprecations the theme raises by adding
`WORDPRESS_DEBUG: "1"` to the `wordpress` service environment and reading
`docker compose -f compose.dev.yaml logs wordpress`.

Tear down, including the database and uploads volumes, and remove the image:

```bash
docker compose -f compose.dev.yaml down -v
docker image rm cdpi-local
```

## 5. Anonymous volume on `/var/www/html` — deploy scripts must renew it

The official `wordpress` image declares `VOLUME /var/www/html`, and a
Dockerfile cannot undeclare a volume inherited from its base. Consequences:

- Every `docker run` / `docker compose up` of the image creates an **anonymous
  volume** for `/var/www/html`, initialised from the image's docroot.
- `docker compose up -d` on a changed image *recreates* the container but, by
  default, **re-attaches the previous container's anonymous volume**, so the
  old docroot (old theme, old core) keeps serving and the `cdpi-build` marker
  does not change. The deploy would then fail its own smoke check.
- `docker compose down` without `-v` leaves those anonymous volumes dangling.

Verified locally (WP3): a file planted in the running container's
`/var/www/html` was still there after `docker compose up -d --force-recreate`
(same anonymous volume id), and gone after
`docker compose up -d --force-recreate --renew-anon-volumes` (new volume id).
Note that the `cdpi-build` marker does **not** catch this on its own: it comes
from the `CDPI_BUILD_SHA` environment variable, which is container config and
does change on recreate even when the docroot underneath is stale.

Therefore the deploy stack must always start the service with
`docker compose up -d --renew-anon-volumes` (short `-V`), and prune dangling
volumes after a successful deploy (`docker volume prune -f` is safe there:
the named `uploads`, `db_data`, `caddy_*` volumes are in use and never
pruned). `docker compose run --rm` (used for `wp core update-db`) is
unaffected: `--rm` also removes the anonymous volume it created.

`compose.dev.yaml` is a throwaway stack, so locally `down -v` covers it, but
after rebuilding the image use `up -d -V` to see the new build.

## 6. Must-use plugins: how they get committed

Production runs three mu-plugins that are not public packages
(`cdn-cache-purge` 2.0.0, `wp-cli-login-server` 1.2, `security-helper` 2.0.0).
They arrive as the tarball `discover.sh` produces (runbook 00). Then:

1. Read every file. `wp-cli-login-server` is an authentication mechanism by
   design and `security-helper` is of unknown provenance; each gets an
   explicit keep/drop decision recorded in the PR.
2. Unpack the approved files into `wp-content-extra/mu-plugins/`, preserving
   the layout: a top-level `*.php` loader plus any subdirectory it includes.
   WordPress auto-loads only top-level `.php` files in `mu-plugins/`.
3. Open a PR touching only `wp-content-extra/mu-plugins/**` so the diff stands
   on its own. The Dockerfile already copies the directory; nothing else
   changes. `.dockerignore` keeps `*.md` out of the image, so the README in that
   directory can stay.
4. Verify locally with section 4: `wp plugin list --status=must-use`.

Until then, images built from this repository run **without** them.
`cdn-cache-purge` in particular implies a CDN in front of the site whose purge
step belongs to the cutover (WP9).

## 7. Release workflow (`.github/workflows/release.yml`)

Triggers only on `push` to `main` and on tags `v*`; never on pull requests.
Job `build` (environment `build`, `packages: write`):

1. Buildx, GHCR login with the workflow token.
2. `docker/metadata-action` computes `ghcr.io/centre-for-dpi/website-v2:sha-<full sha>`
   and, on a tag push, `:<tag>`. No `latest`.
3. Immutability guard: `docker manifest inspect` of the `sha-` tag; if it
   already exists the build and push are skipped and the job says so. GHCR does
   not enforce immutability, so the workflow does.
4. `docker/build-push-action` with `GIT_SHA=${{ github.sha }}`, GitHub Actions
   cache, `provenance: false`, `sbom: false` (attestations add untagged
   manifests the retention workflow would sweep).
5. Output `image_tag` = `sha-<sha>` for the deploy jobs added in WP6/WP8.

## 8. Pending WP1 merge — additions for `ci.yml` and `dependabot.yml`

PR #2 (WP1) adds `.github/workflows/ci.yml` and `.github/dependabot.yml` and
was not yet merged when this runbook was written, so WP3 does not touch those
files. Once #2 is on `main`, apply both blocks below in one small PR.

### 8a. `image-theme-stage` job for `.github/workflows/ci.yml`

Runs on pull requests only, builds the `theme` stage of the Dockerfile without
pushing anything, so a PR that breaks the image build is caught before merge.
References no secret. Append under `jobs:`:

```yaml
  # Builds the theme stage of the Dockerfile on pull requests only, so a change
  # that breaks the container build fails here rather than on main. Nothing is
  # pushed and no secret is referenced.
  image-theme-stage:
    name: image-theme-stage
    if: github.event_name == 'pull_request'
    runs-on: ubuntu-latest
    timeout-minutes: 15
    steps:
      - name: Check out the pull request head
        uses: actions/checkout@3d3c42e5aac5ba805825da76410c181273ba90b1 # v7.0.1

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@f87e5991a6d7451dcb8d9637bfbc97413f497069 # v4.4.1

      - name: Build the theme stage
        uses: docker/build-push-action@c3c9e263c25d99ce0380d002d59b67737d91b0dc # v7.4.0
        with:
          context: .
          target: theme
          push: false
          cache-from: type=gha
          cache-to: type=gha,mode=max
```

### 8b. `docker` and `composer` ecosystems for `.github/dependabot.yml`

`docker` bumps the `FROM` tags in the Dockerfile (core and PHP upgrades arrive
as PRs); `composer` bumps the plugin pins in `composer.json`/`composer.lock`.
Append under `updates:`:

```yaml
  # Core and PHP upgrades: Dependabot rewrites the wordpress:<tag> FROM lines.
  # Note the ARG defaults (WP_IMAGE_TAG, WP_CLI_IMAGE_TAG) are what actually
  # select the tag; review that both move together.
  - package-ecosystem: docker
    directory: /
    schedule:
      interval: weekly
      day: monday
    labels: ["dependencies"]
    commit-message:
      prefix: build

  # Plugin pins. Every bump is a PR that rebuilds the image, so a plugin
  # update is reviewed and deployed like any other change (ADR-004).
  - package-ecosystem: composer
    directory: /
    schedule:
      interval: weekly
      day: monday
    labels: ["dependencies"]
    commit-message:
      prefix: build
```
