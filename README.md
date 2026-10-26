# CDPI WordPress Theme

WordPress theme for the CDPI website (`https://cdpi.dev`). This repository is
the theme only; WordPress core and plugins are not in it.

## Contributing

1. Branch from `main` (`git switch -c <topic> main`); never push to `main`.
2. Open a pull request. The template (`.github/pull_request_template.md`)
   asks what you need to tell the reviewer.
3. Two checks must pass: `lint` (every PHP file parses) and `build`
   (`npm ci && npm run build` succeeds on Node 22). `image-theme-stage` and
   `deploy-tooling` run too and are advisory.
4. One architect review (CODEOWNERS covers `.github/`, `Dockerfile`,
   `deploy/`, `composer.*`).
5. Merge (merge or squash).

What happens next is automatic or approved, never manual on a server:

- **Merging deploys to staging** (`https://65.2.140.75`, IP mode, internal
  CA) within a few minutes: the Release workflow builds one container image
  tagged `sha-<merge commit>` and the staging host swaps to it. Check the
  commit a site serves with
  `curl -s https://<site>/ | grep -o 'cdpi-build" content="[0-9a-f]*'`.
- **Production is a dispatched, approved release** of a commit that already
  ran on staging: `gh workflow run release.yml -f image_tag=sha-<commit>
  -f environment=production`, approved by a required reviewer. Production
  picks it up within about a minute and the run verifies the site serves it.
  Rollback is the same command with an older commit (`docs/runbooks/rollback.md`).

Nobody edits files on a live server. A hand-edited production theme blocks
releases until it is resolved (`docs/runbooks/03-production-checkout.md` §9).

## Local development (unchanged)

Prerequisites: Node 22 (`.nvmrc`), npm, and this theme active on a local
WordPress install (e.g. Local by Flywheel).

```bash
npm install
npm run dev      # webpack watch, NODE_ENV=development, BrowserSync proxy (see webpack.config.js)
```

Source lives in `styles/scss/` (entry `main.scss`) and `styles/js/` (entry
`scripts.js`). PHP templates and blocks under the theme root and `src/blocks/`
are used as-is by WordPress.

## Build output: still committed (for now)

Before your final commit, run the production build and commit what it
writes:

```bash
npm run build    # wipes public/js and public/css, writes hashed bundles + public/webpack.manifest.json
git add public/js public/css public/webpack.manifest.json
```

**Still required: production checks the theme out from git**, so the
committed `public/` is what it serves (ADR-004 amendment 2026-10-26 in
`docs/adr/ADR-001-010-cicd.md`). The staging image rebuilds these in CI and
ignores the committed copies, so a stale manifest shows up on staging as
missing CSS/JS before it can reach production. Commit `public/webpack.manifest.json`
together with the bundles it names; never commit `npm run dev` output.
`public/img` is source and stays committed regardless.

## Where things are

- `docs/runbooks/` — staging bootstrap (01, 02), production checkout mode
  (03-production-checkout), image (20; **known theme issues in §9**),
  rollback, backup/restore, repository governance (10).
- `docs/adr/ADR-001-010-cicd.md` — the decisions and their amendments.
- `BREAKGLASS.md` — what to record when a protection is bypassed.
- `deploy/` — compose files, host scripts, and the production deploy script
  (`deploy/host/cdpi-deploy-theme`, with its self-test).
- `.github/workflows/` — `ci.yml` (pull requests), `release.yml` (build,
  staging, production), `ghcr-retention.yml`.
