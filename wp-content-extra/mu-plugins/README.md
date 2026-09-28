# `wp-content-extra/mu-plugins/`

Everything in this directory except this README is copied verbatim into
`/var/www/html/wp-content/mu-plugins/` in the container image. WordPress
auto-loads every top-level `.php` file there on every request, with no way to
deactivate it from wp-admin, so nothing lands here without a line-by-line
review recorded in the PR that adds it.

## What is shipped

| File | Version | Decision |
| --- | --- | --- |
| `security-helper.php` | 2.0.0 | **Kept.** Production's copy, byte for byte. See below. |

`security-helper.php` is Nestify-era code (the previous managed host) and is
kept for behaviour parity at cutover: the site must behave the same the moment
it moves from the Lightsail box to the container, and this file changes what
editors see in wp-admin. What it does:

- hides the core-update UI (Dashboard > Updates menu, the update nag, the
  admin-bar updates item, the "Get Version x.y" footer);
- removes the Right Now, Activity and WordPress Events and News dashboard
  widgets;
- removes several Site Health tests (background updates, scheduled events,
  WordPress version, loopback, REST availability, page cache, persistent
  object cache) and the scheduled-events info panel;
- on every admin page load by a logged-in user, deletes any user whose login
  starts with `deleted`, `wp_update`, `wpcron` or `yanz` (a clean-up for a
  past compromise, left running);
- on login, rejects passwords that are `password`, a single repeated
  character, shorter than 8 characters, or found in the Have I Been Pwned
  range API (`api.pwnedpasswords.com`; only the first five characters of the
  SHA-1 hash leave the server, and a failed lookup lets the login proceed);
- blocks updates to the `widget_custom_html` option, so the Custom HTML widget
  cannot be configured;
- fires the `rt_nginx_helper_after_purge_all` action when a scheduled post is
  published. Nothing listens to that action in this image (see the dropped
  files below), so this is a no-op apart from one `error_log` line. Known
  wart, present on production too: the callback is hooked to
  `transition_post_status`, whose first argument is the new status, not a
  post ID, so `get_post()` returns null and PHP logs
  `Attempt to read property "post_status" on null` (line 138) on every status
  transition. Harmless, but noisy in the container log.

It contains no credentials, no host names other than the HIBP API, and writes
nothing to the docroot. Trimming it to what the site still needs (the update
UI hiding and the password checks are the likely keepers; the user-deletion
sweep, the Site Health removals and the nginx action are candidates to go) is a
WP11 hardening follow-up, not part of the cutover.

## What was dropped, and why

Production's `wp-content/mu-plugins/` held three more things, all leftovers
from the Nestify hosting stack. Production today is Apache on Lightsail with
no nginx page cache, no Redis and no CDN (verified from the live site's
response headers), so none of them does anything useful there, and one of
them phones out to a third party. None is shipped.

| Dropped | Reason |
| --- | --- |
| `cdn-cache-purge.php` | Nestify shim: requires the bundled `nginx-helper/`, points it at Redis on `127.0.0.1:6379` (nothing listens) and POSTs the hostname plus every changed URL to `https://my.nestify.io/cdn/purge/1/purge` on each purge event. Data leaves the site for a host we no longer use. |
| `nginx-helper/` | A full copy of the rtCamp nginx-helper plugin, loaded only by the shim above. No nginx, no cache to purge. |
| `wp-cli-login-server.php` | Serves the `wp login` magic-link command. An authentication bypass by design, and unused. |

No credentials were found in any of the four.

## Why the README is not shipped

`.dockerignore` excludes `**/*.md`, so this file never enters the build
context and cannot reach the image. The Dockerfile's `COPY` of this directory
therefore delivers `security-helper.php` and nothing else:
`docker run --rm cdpi-local ls -A /var/www/html/wp-content/mu-plugins` prints
`security-helper.php` alone.

## Adding or changing a must-use plugin

1. Read every file. Anything that phones home, writes to the docroot, or
   bypasses authentication gets an explicit keep/drop decision recorded in
   the PR, not a silent `git add`.
2. Put the approved files directly in this directory, preserving layout: a
   top-level `*.php` loader plus any subdirectory it includes. WordPress
   auto-loads **only** top-level `.php` files in `mu-plugins/`; files in
   subdirectories must be `require`d by a top-level loader.
3. Open a PR touching only `wp-content-extra/mu-plugins/**` (plus this README
   and runbook 20), so the diff is reviewable on its own.
4. Verify with runbook 20 section 4: `wp plugin list --status=must-use`.

## Note for `DISALLOW_FILE_MODS`

`docker/wp-config.php` sets `DISALLOW_FILE_MODS`, which hides the plugin
installer. Must-use plugins are unaffected: they load from disk and cannot be
deactivated from wp-admin, which is exactly why they need review before they
are baked in.
