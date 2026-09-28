# syntax=docker/dockerfile:1.7
#
# cdpi/website-v2 container image (ADR-004): pinned WordPress core + this theme
# (built from source in the `theme` stage) + pinned plugins (resolved by Composer
# in the `plugins` stage) + WP-CLI, assembled on the official Apache image.
#
# Nothing here needs a secret. Every value below that depends on the production
# host is a build ARG with a placeholder default and is marked "!! PLACEHOLDER";
# scripts/host/discover.sh (runbook 00) supplies the real values, after which
# each is a one-line change to the ARG default. See docs/runbooks/20-image.md.
#
# The ARGs before the first FROM are global: they are usable in FROM lines and
# are re-declared (without a value) inside the stage that needs them.

# !! PLACEHOLDER: production's core and PHP version (`wp core version`, `php -v`
# from discover.sh). Must be an existing tag of docker.io/library/wordpress.
ARG WP_IMAGE_TAG=6.8-php8.2-apache
# WP-CLI is taken from the matching official cli image; keep the PHP suffix in
# step with WP_IMAGE_TAG.
ARG WP_CLI_IMAGE_TAG=cli-2.12.0-php8.2
# !! PLACEHOLDER: the theme directory name. This MUST equal the `stylesheet`
# option in the production database (`wp option get stylesheet`), because that
# is the directory WordPress looks in for the active theme. A mismatch means
# the site boots on no theme at all. discover.sh prints it.
ARG THEME_SLUG=cdpi
# !! PLACEHOLDER: php.ini limits (discover.sh prints production's values).
ARG PHP_UPLOAD_MAX_FILESIZE=64M
ARG PHP_POST_MAX_SIZE=64M
ARG PHP_MEMORY_LIMIT=256M
ARG PHP_MAX_EXECUTION_TIME=120
# Set by the release workflow to the commit being built; surfaces as the
# CDPI_BUILD_SHA env var and the <meta name="cdpi-build"> tag in every page.
ARG GIT_SHA=unknown


# ---------------------------------------------------------------------------
# Stage 1: build the theme assets from source.
# ---------------------------------------------------------------------------
FROM node:22-bookworm-slim AS theme
WORKDIR /theme

# Dependencies first so they cache independently of source edits. NODE_ENV is
# deliberately NOT set to production here: `npm ci` would then skip the
# devDependencies that webpack lives in. `npm run build` passes
# --node-env=production to webpack itself.
COPY package.json package-lock.json .nvmrc ./
RUN npm ci --no-audit --no-fund

# webpack.config.js runs cleanFolders() at config-load time, which wipes
# public/js and public/css, so committed build output can never end up in the
# image even if it slipped past .dockerignore. Everything under public/ that is
# NOT build output (img, fonts) is copied straight into the output tree below
# and never passes through the webpack working directory.
COPY webpack.config.js ./
COPY styles/ styles/
RUN npm run build

# Assemble the clean theme tree that ships. Only what WordPress needs at
# runtime: style.css (theme header), screenshot.png, the root templates,
# src/ (functions.php loads src/redlof/), templates/, and public/ with the
# freshly built js/css/manifest plus the committed img and fonts.
COPY style.css screenshot.png *.php /out/
COPY src/ /out/src/
COPY templates/ /out/templates/
COPY public/img/ /out/public/img/
COPY public/fonts/ /out/public/fonts/
RUN set -eux; \
    cp -r public/js public/css public/webpack.manifest.json /out/public/; \
    find /out -name .DS_Store -delete; \
    test -s /out/public/webpack.manifest.json; \
    # Normalise ownership and modes here, so the final stage needs no chown
    # pass (which would rewrite every file into an extra image layer).
    chown -R root:root /out; \
    find /out -type d -exec chmod 755 {} +; \
    find /out -type f -exec chmod 644 {} +; \
    echo '--- theme tree shipped in the image (find /out -maxdepth 2):'; \
    find /out -maxdepth 2 | sort; \
    echo '--- built assets:'; \
    ls -l /out/public/js /out/public/css; \
    cat /out/public/webpack.manifest.json


# ---------------------------------------------------------------------------
# Stage 2: resolve the pinned plugin set with Composer (no secrets: all six
# plugins are public WPackagist packages; ACF is the free edition).
# ---------------------------------------------------------------------------
FROM composer:2 AS plugins
WORKDIR /app
COPY composer.json composer.lock ./
# --no-check-all skips only the "exact version constraints should be avoided"
# advisory, which is triggered by the pins we want on purpose; --strict still
# turns every other warning into an error and the lock-file consistency check
# still runs (a composer.json edit without a matching `composer update`
# fails the build here rather than silently installing the old lock).
RUN composer validate --strict --no-check-all
# composer/installers places each plugin under wp-content/plugins/<slug>/
# (extra.installer-paths in composer.json). Only that directory is copied on.
RUN set -eux; \
    composer install --no-dev --prefer-dist --no-interaction --no-scripts --optimize-autoloader; \
    chown -R root:root /app/wp-content/plugins; \
    find /app/wp-content/plugins -type d -exec chmod 755 {} +; \
    find /app/wp-content/plugins -type f -exec chmod 644 {} +; \
    echo '--- plugins installed:'; ls -l /app/wp-content/plugins


# ---------------------------------------------------------------------------
# Stage 3: WP-CLI binary from the matching official image.
# ---------------------------------------------------------------------------
FROM wordpress:${WP_CLI_IMAGE_TAG} AS cli


# ---------------------------------------------------------------------------
# Final image.
# ---------------------------------------------------------------------------
FROM wordpress:${WP_IMAGE_TAG}
ARG THEME_SLUG
ARG PHP_UPLOAD_MAX_FILESIZE
ARG PHP_POST_MAX_SIZE
ARG PHP_MEMORY_LIMIT
ARG PHP_MAX_EXECUTION_TIME

COPY --from=cli /usr/local/bin/wp /usr/local/bin/wp

# Bake core into the docroot. The official entrypoint (docker-entrypoint.sh)
# copies /usr/src/wordpress into /var/www/html only when neither index.php nor
# wp-includes/version.php exists there, and generates wp-config.php only when
# none is present; both are present after this stage, so the entrypoint does
# nothing but `exec` the command. The container is therefore fully formed at
# build time and can be thrown away and recreated at will.
#
# The bundled default themes and the inactive akismet/hello plugins are not
# part of the site and would only be attack surface and update noise.
RUN set -eux; \
    cp -a /usr/src/wordpress/. /var/www/html/; \
    rm -rf /var/www/html/wp-content/themes/twenty* \
           /var/www/html/wp-content/plugins/akismet \
           /var/www/html/wp-content/plugins/hello.php \
           /var/www/html/wp-config-docker.php; \
    chown -R root:root /var/www/html; \
    find /var/www/html -type d -exec chmod 755 {} +; \
    find /var/www/html -type f -exec chmod 644 {} +

# Files copied straight from the build context keep the checkout's modes, so
# the two small context copies get --chmod; the two stage copies were
# normalised in their stages and COPY --from preserves that.
COPY --chown=root:root --chmod=644 docker/wp-config.php /var/www/html/wp-config.php
COPY --from=plugins /app/wp-content/plugins/ /var/www/html/wp-content/plugins/
# wp-content-extra/mu-plugins/ is a placeholder until production's must-use
# plugins are reviewed and committed (its README is excluded by .dockerignore,
# so the directory arrives empty). Modes are normalised in the RUN below,
# which is cheap while the directory is small.
COPY --chown=root:root wp-content-extra/mu-plugins/ /var/www/html/wp-content/mu-plugins/
COPY --from=theme /out/ /var/www/html/wp-content/themes/${THEME_SLUG}/

# php.ini overrides, templated from the build ARGs above.
COPY docker/php/cdpi.ini /usr/local/etc/php/conf.d/zz-cdpi.ini
RUN set -eux; \
    sed -i \
      -e "s|@UPLOAD_MAX_FILESIZE@|${PHP_UPLOAD_MAX_FILESIZE}|g" \
      -e "s|@POST_MAX_SIZE@|${PHP_POST_MAX_SIZE}|g" \
      -e "s|@MEMORY_LIMIT@|${PHP_MEMORY_LIMIT}|g" \
      -e "s|@MAX_EXECUTION_TIME@|${PHP_MAX_EXECUTION_TIME}|g" \
      /usr/local/etc/php/conf.d/zz-cdpi.ini; \
    ! grep -n '@[A-Z_]*@' /usr/local/etc/php/conf.d/zz-cdpi.ini; \
    grep -E '^(upload_max_filesize|post_max_size|memory_limit|max_execution_time)' /usr/local/etc/php/conf.d/zz-cdpi.ini; \
    php -r 'foreach (["upload_max_filesize","post_max_size","memory_limit"] as $k) echo $k, "=", ini_get($k), "\n";'

# Ownership: the docroot belongs to root and is read-only for the web server
# user; www-data can write to wp-content/uploads and nowhere else (that path
# is a named volume at runtime, but its ownership in the image is what the
# volume is initialised from). Together with DISALLOW_FILE_MODS in wp-config
# this is what makes the running container tamper-resistant (ADR-004).
# The find below only verifies (it must print nothing); it rewrites no file,
# so this layer stays small.
RUN set -eux; \
    find /var/www/html/wp-content/mu-plugins -type d -exec chmod 755 {} +; \
    find /var/www/html/wp-content/mu-plugins -type f -exec chmod 644 {} +; \
    install -d -o www-data -g www-data -m 755 /var/www/html/wp-content/uploads; \
    ! find /var/www/html -path /var/www/html/wp-content/uploads -prune -o \
        \( ! -user root -o ! -group root -o \( -type d ! -perm 755 \) -o \( -type f ! -perm 644 \) \) -print \
        | grep .; \
    test -f "/var/www/html/wp-content/themes/${THEME_SLUG}/style.css"; \
    test -f "/var/www/html/wp-content/themes/${THEME_SLUG}/public/webpack.manifest.json"; \
    ls -la /var/www/html/wp-content/themes /var/www/html/wp-content/plugins /var/www/html/wp-content/mu-plugins

# ENTRYPOINT (docker-entrypoint.sh), CMD (apache2-foreground), EXPOSE 80 and
# STOPSIGNAL are inherited from the base image unchanged.

# Build identity last, so every layer above stays cacheable across commits.
ARG GIT_SHA
ENV CDPI_BUILD_SHA=$GIT_SHA
LABEL org.opencontainers.image.source="https://github.com/centre-for-dpi/website-v2" \
      org.opencontainers.image.revision="$GIT_SHA"
