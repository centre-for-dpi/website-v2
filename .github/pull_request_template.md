## What and why

<!-- One or two sentences. What changes, and what problem it solves. Link the
     issue or ticket if there is one. -->

## How it was verified

<!-- What you actually ran or clicked, not what you expect to work.
     e.g. "npm run build locally, checked /daas/ and a blog post on staging" -->

## Checklist

- [ ] No files under `public/js`, `public/css` or `public/webpack.manifest.json`
      changed unless intended (CI builds these; see ADR-004)
- [ ] PHP parses — `php -l` clean on every changed `.php` file (the `lint` job
      checks this)
- [ ] Screenshots or a short clip attached for any UI change
- [ ] Changes to `.github/`, `Dockerfile`, `docker/`, `deploy/`, `composer.*`,
      `scripts/` or `docs/adr/` — an architect's review is required (CODEOWNERS)

Deploys: merging to `main` deploys to staging automatically; production requires
approval.
