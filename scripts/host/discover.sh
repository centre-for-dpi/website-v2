#!/usr/bin/env bash
#
# discover.sh — read-only inventory of a CDPI WordPress host.
#
# Run this on the production Lightsail box (or any WordPress host) to collect
# the facts the containerisation work needs: core/PHP/MySQL versions, php.ini
# limits, the active theme slug, the plugin and must-use plugin inventory,
# Apache vhost and .htaccess rules, cron, CDN headers and disk headroom.
#
# It is READ-ONLY: it never writes to the document root, the database, or any
# system path. The single file it creates is a tarball of wp-content/mu-plugins
# under /tmp, which is what the image build needs (mu-plugins are not public
# packages and must be copied from production).
#
# Usage:
#   ./discover.sh [wordpress-document-root]     # default /var/www/cdpi-website
#
# Send back BOTH the full stdout of this script and the /tmp tarball it names
# on its last line. Missing tools are reported, not fatal — the script always
# runs to the end.

set -u

DOCROOT="${1:-/var/www/cdpi-website}"
TARBALL="/tmp/cdpi-mu-plugins-$(date +%F).tgz"

# ---------------------------------------------------------------------------
# helpers
# ---------------------------------------------------------------------------

heading() {
  printf '\n===== %s =====\n' "$*"
}

have() {
  command -v "$1" >/dev/null 2>&1
}

skip() {
  printf '(not available: %s)\n' "$*"
}

# wp-cli refuses to run as root without --allow-root; add it only when needed.
WP_ROOT_FLAG=""
if [ "$(id -u)" = "0" ]; then
  WP_ROOT_FLAG="--allow-root"
fi

wp_cmd() {
  if ! have wp; then
    skip "wp-cli not installed"
    return 0
  fi
  if [ -n "$WP_ROOT_FLAG" ]; then
    wp "$@" "$WP_ROOT_FLAG" 2>&1
  else
    wp "$@" 2>&1
  fi
}

# Locate wp-config.php: normally in the docroot, sometimes one level above.
WP_CONFIG=""
for candidate in "$DOCROOT/wp-config.php" "$DOCROOT/../wp-config.php"; do
  if [ -r "$candidate" ]; then
    WP_CONFIG="$candidate"
    break
  fi
done

# ---------------------------------------------------------------------------

heading "discover.sh context"
printf 'script run at    : %s\n' "$(date -Is 2>/dev/null || date)"
printf 'document root    : %s\n' "$DOCROOT"
printf 'running as       : %s (uid %s)\n' "$(id -un 2>/dev/null)" "$(id -u)"
printf 'wp --allow-root  : %s\n' "${WP_ROOT_FLAG:-no}"
printf 'wp-config.php    : %s\n' "${WP_CONFIG:-NOT FOUND}"
printf 'hostname         : %s\n' "$(hostname 2>/dev/null)"

if [ ! -d "$DOCROOT" ]; then
  printf '\n!! %s is not a directory. Pass the correct document root as the\n' "$DOCROOT"
  printf '!! first argument, e.g. ./discover.sh /var/www/html\n'
  printf '!! Continuing anyway — host-level sections below are still useful.\n'
fi

cd "$DOCROOT" 2>/dev/null || printf '(could not cd into %s)\n' "$DOCROOT"

heading "Operating system"
if have lsb_release; then
  lsb_release -ds
else
  skip "lsb_release"
  [ -r /etc/os-release ] && grep -E '^PRETTY_NAME=' /etc/os-release
fi
uname -m
uname -sr

heading "PHP version"
if have php; then
  php -v 2>&1 | head -1
else
  skip "php"
fi

heading "PHP limits (php -i)"
if have php; then
  php -i 2>/dev/null | grep -E \
    '^(upload_max_filesize|post_max_size|memory_limit|max_execution_time) ' \
    || printf '(none of the four settings matched; full php -i not shown)\n'
  printf -- '--- loaded php.ini files ---\n'
  php --ini 2>&1
else
  skip "php"
fi

heading "MySQL / MariaDB version"
if have mysql; then
  mysql --version
elif have mariadb; then
  mariadb --version
else
  skip "mysql / mariadb client"
fi

heading "WordPress core version"
wp_cmd core version

heading "WordPress plugins (JSON)"
wp_cmd plugin list --format=json

heading "WordPress themes (JSON)"
wp_cmd theme list --format=json

heading "WordPress options"
for opt in stylesheet template siteurl home permalink_structure blog_public upload_path; do
  printf '%-20s : ' "$opt"
  wp_cmd option get "$opt"
done

heading "Database size per table"
if have wp; then
  wp_cmd db size --tables
else
  skip "wp-cli"
fi

heading "table_prefix from wp-config.php"
if [ -n "$WP_CONFIG" ]; then
  # "[$]" keeps the dollar literal without single-quoting the whole pattern.
  grep -aE "^[[:space:]]*[$]table_prefix" "$WP_CONFIG" \
    || printf '(no table_prefix line found)\n'
else
  skip "wp-config.php"
fi

heading "wp-config.php define() lines (secrets masked)"
if [ -n "$WP_CONFIG" ]; then
  grep -aE "^[[:space:]]*define\(" "$WP_CONFIG" | awk '
    {
      line = $0
      if (line ~ /KEY|SALT|PASSWORD|PASS/) {
        sub(/,[[:space:]]*.*$/, ", \x27***\x27 );", line)
      }
      print line
    }'
else
  skip "wp-config.php"
fi

heading ".htaccess"
if [ -r "$DOCROOT/.htaccess" ]; then
  cat "$DOCROOT/.htaccess"
else
  printf '(no readable %s/.htaccess)\n' "$DOCROOT"
fi

heading "Apache enabled vhosts"
if [ -d /etc/apache2/sites-enabled ]; then
  ls -l /etc/apache2/sites-enabled
  for vhost in /etc/apache2/sites-enabled/*; do
    [ -r "$vhost" ] || continue
    printf -- '\n--- %s ---\n' "$vhost"
    cat "$vhost"
  done
else
  skip "/etc/apache2/sites-enabled"
fi

heading "Cron"
printf -- '--- crontab -l (user %s) ---\n' "$(id -un 2>/dev/null)"
if have crontab; then
  crontab -l 2>&1
else
  skip "crontab"
fi
printf -- '\n--- /etc/cron.d ---\n'
if [ -d /etc/cron.d ]; then
  ls -l /etc/cron.d
else
  skip "/etc/cron.d"
fi

heading "certbot timers"
if have systemctl; then
  systemctl list-timers --all 2>/dev/null | grep -i certbot \
    || printf '(no certbot timer found)\n'
else
  skip "systemctl"
fi

heading "Uploads directory size"
if [ -d "$DOCROOT/wp-content/uploads" ]; then
  du -sh "$DOCROOT/wp-content/uploads" 2>/dev/null
else
  printf '(no %s/wp-content/uploads)\n' "$DOCROOT"
fi

heading "Must-use plugins (wp-content/mu-plugins)"
if [ -d "$DOCROOT/wp-content/mu-plugins" ]; then
  ls -la "$DOCROOT/wp-content/mu-plugins"
else
  printf '(no %s/wp-content/mu-plugins)\n' "$DOCROOT"
fi

heading "Disk space"
df -h / 2>&1

heading "Memory"
if have free; then
  free -m
else
  skip "free"
fi

heading "Docker"
if have docker; then
  docker --version
else
  skip "docker (expected on a host that has not been provisioned yet)"
fi

heading "CDN / proxy check on the site home URL"
if have curl && have wp; then
  home_url="$(wp_cmd option get home | tr -d '\r')"
  printf 'home URL: %s\n' "$home_url"
  case "$home_url" in
    http://* | https://*)
      curl -sI --max-time 20 "$home_url" 2>&1 \
        | grep -iE '^(HTTP/|server|cf-ray|cf-cache-status|x-cache|x-cache-hits|via|x-served-by|x-amz-cf-id|age):' \
        || printf '(no CDN-ish headers matched)\n'
      ;;
    *)
      printf '(could not determine a usable home URL, skipping header check)\n'
      ;;
  esac
else
  skip "curl and/or wp-cli"
fi

heading "mu-plugins tarball"
if [ -d "$DOCROOT/wp-content/mu-plugins" ]; then
  if have tar; then
    if tar -czf "$TARBALL" -C "$DOCROOT/wp-content" mu-plugins 2>&1; then
      ls -lh "$TARBALL"
      printf '\nSend this file back for review: %s\n' "$TARBALL"
    else
      printf '(tar failed; nothing written)\n'
    fi
  else
    skip "tar"
  fi
else
  printf '(no mu-plugins directory, no tarball created)\n'
fi

heading "discover.sh finished"
printf 'Nothing on this host was modified. The only file created is:\n  %s\n' "$TARBALL"
