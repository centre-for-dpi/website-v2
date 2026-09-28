<?php
/**
 * WordPress configuration for the cdpi/website-v2 container image.
 *
 * Derived from the official image's /usr/src/wordpress/wp-config-docker.php
 * (wordpress:7.0.4-php8.2-apache; the file is unchanged upstream between the
 * 6.8 and 7.0.4 images apart from its header comment), with the CDPI
 * additions marked below.
 *
 * Everything environment-specific comes from the environment (ADR-005):
 * the compose stack supplies it via `env_file: /etc/cdpi/app.env`. Every key
 * also accepts a `<KEY>_FILE` variant pointing at a file, which is how Docker
 * secrets are read; that behaviour comes from getenv_docker() below and is
 * kept verbatim from upstream.
 *
 * This file is baked into the image, so the official entrypoint never
 * generates one: docker-entrypoint.sh only writes wp-config.php when
 * `[ ! -s wp-config.php ]`, and it only copies core when neither index.php
 * nor wp-includes/version.php exists. Both are present in the image.
 *
 * @package WordPress
 */

// IMPORTANT: this file needs to stay in-sync with https://github.com/WordPress/WordPress/blob/master/wp-config-sample.php
// (it gets parsed by the upstream wizard in https://github.com/WordPress/WordPress/blob/f27cb65e1ef25d11b535695a660e7282b98eb742/wp-admin/setup-config.php#L356-L392)

// a helper function to lookup "env_FILE", "env", then fallback
if (!function_exists('getenv_docker')) {
	// https://github.com/docker-library/wordpress/issues/588 (WP-CLI will load this file 2x)
	function getenv_docker($env, $default) {
		if ($fileEnv = getenv($env . '_FILE')) {
			return rtrim(file_get_contents($fileEnv), "\r\n");
		}
		else if (($val = getenv($env)) !== false) {
			return $val;
		}
		else {
			return $default;
		}
	}
}

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv_docker('WORDPRESS_DB_NAME', 'wordpress') );

/** Database username */
define( 'DB_USER', getenv_docker('WORDPRESS_DB_USER', 'example username') );

/** Database password */
define( 'DB_PASSWORD', getenv_docker('WORDPRESS_DB_PASSWORD', 'example password') );

/**
 * Docker image fallback values above are sourced from the official WordPress installation wizard:
 * https://github.com/WordPress/WordPress/blob/1356f6537220ffdc32b9dad2a6cdbe2d010b7a88/wp-admin/setup-config.php#L224-L238
 * (However, using "example username" and "example password" in your database is strongly discouraged.  Please use strong, random credentials!)
 */

/** Database hostname */
define( 'DB_HOST', getenv_docker('WORDPRESS_DB_HOST', 'mysql') );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv_docker('WORDPRESS_DB_CHARSET', 'utf8mb4') );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * CDPI note: unlike the upstream image we do NOT generate random salts at
 * container start (the entrypoint's awk pass only runs when it writes
 * wp-config.php itself, which it never does here). Production must therefore
 * supply the EXISTING salts in /etc/cdpi/app.env, or every logged-in session
 * and every password-reset link is invalidated at cutover. See WP8.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         getenv_docker('WORDPRESS_AUTH_KEY',         'put your unique phrase here') );
define( 'SECURE_AUTH_KEY',  getenv_docker('WORDPRESS_SECURE_AUTH_KEY',  'put your unique phrase here') );
define( 'LOGGED_IN_KEY',    getenv_docker('WORDPRESS_LOGGED_IN_KEY',    'put your unique phrase here') );
define( 'NONCE_KEY',        getenv_docker('WORDPRESS_NONCE_KEY',        'put your unique phrase here') );
define( 'AUTH_SALT',        getenv_docker('WORDPRESS_AUTH_SALT',        'put your unique phrase here') );
define( 'SECURE_AUTH_SALT', getenv_docker('WORDPRESS_SECURE_AUTH_SALT', 'put your unique phrase here') );
define( 'LOGGED_IN_SALT',   getenv_docker('WORDPRESS_LOGGED_IN_SALT',   'put your unique phrase here') );
define( 'NONCE_SALT',       getenv_docker('WORDPRESS_NONCE_SALT',       'put your unique phrase here') );
// (See also https://wordpress.stackexchange.com/a/152905/199287)

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * CDPI note: production's real prefix comes from scripts/host/discover.sh and
 * must be set as WORDPRESS_TABLE_PREFIX in /etc/cdpi/app.env before cutover.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = getenv_docker('WORDPRESS_TABLE_PREFIX', 'wp_');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their environments.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', !!getenv_docker('WORDPRESS_DEBUG', '') );

/* Add any custom values between this line and the "stop editing" line. */

// If we're behind a proxy server and using HTTPS, we need to alert WordPress of that fact
// see also https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
// CDPI: Caddy terminates TLS and sets X-Forwarded-Proto, so this block is load-bearing.
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
	$_SERVER['HTTPS'] = 'on';
}
// (we include this by default because reverse proxying is extremely common in container environments)

/* ---------------------------------------------------------------------------
 * CDPI additions (everything below this comment is ours, not upstream's)
 * ------------------------------------------------------------------------- */

/**
 * Site URLs.
 *
 * These have to live in wp-config.php rather than in an mu-plugin, because
 * WordPress resolves them while loading wp-settings.php, before plugins run.
 * Defining them also makes the values in the database advisory only, so a
 * bad `siteurl` row cannot lock anyone out of the site.
 *
 * Left undefined when the env var is empty, so that a fresh `wp core install`
 * (local development, see docs/runbooks/20-image.md) can set them itself.
 */
$cdpiHome = getenv_docker('WP_HOME', '');
if ($cdpiHome !== '') {
	define( 'WP_HOME', $cdpiHome );
}
unset($cdpiHome);

$cdpiSiteUrl = getenv_docker('WP_SITEURL', '');
if ($cdpiSiteUrl !== '') {
	define( 'WP_SITEURL', $cdpiSiteUrl );
}
unset($cdpiSiteUrl);

/**
 * The filesystem is the artifact (ADR-004).
 *
 * DISALLOW_FILE_MODS removes the plugin/theme installer, the editor and the
 * updater from wp-admin entirely. Nothing writes to /var/www/html at runtime,
 * so wp-content/upgrade never has to be writable and an in-place modification
 * of the docroot -- the April 2026 incident class -- cannot be made to stick:
 * a redeploy or a restart replaces the container wholesale.
 *
 * Core, plugin and theme versions move by changing composer.json or the
 * Dockerfile's WP_IMAGE_TAG and shipping a new image.
 */
define( 'DISALLOW_FILE_MODS', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );

/**
 * WP-Cron.
 *
 * Left on by default (WordPress's own default). Set DISABLE_WP_CRON=true in
 * app.env where a real scheduler drives wp-cron instead -- for instance the
 * shadow-test stack in WP8, which must not fire scheduled jobs against the
 * live database.
 */
define( 'DISABLE_WP_CRON', filter_var(getenv_docker('DISABLE_WP_CRON', 'false'), FILTER_VALIDATE_BOOLEAN) );

/**
 * WP_ENVIRONMENT_TYPE is deliberately NOT defined here.
 *
 * WordPress reads the environment variable of that name natively in
 * wp_get_environment_type(), and a constant would override -- and silence --
 * the env var the compose stack sets. Valid values: local, development,
 * staging, production (anything else falls back to production).
 * functions.php uses it to add `noindex` outside production.
 */

if ($configExtra = getenv_docker('WORDPRESS_CONFIG_EXTRA', '')) {
	eval($configExtra);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
