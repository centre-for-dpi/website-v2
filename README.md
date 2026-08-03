# CDPI WordPress Theme

WordPress theme for the CDPI website.

## Prerequisites

- Node.js and npm installed locally.
- This theme active on a local WordPress install (e.g. via Local by Flywheel).

## 1. Install dependencies

From the theme root (this directory):

```bash
npm install
```

This reads `package.json` and installs everything webpack needs (loaders, sass, autoprefixer, etc.) into `node_modules`.

## 2. Make your changes

Source files live in `styles/`:

- `styles/scss/` — Sass source (entry point: `styles/scss/main.scss`)
- `styles/js/` — JS source (entry point: `styles/js/scripts.js`)

Also edit PHP templates/blocks directly under the theme root and `src/blocks/` as needed — those are not built, they're used as-is by WordPress.

## 3. Build while developing

Run the watcher so changes rebuild automatically as you work:

```bash
npm run dev
```

This runs webpack in watch mode with `NODE_ENV=development`, writing unminified output with source maps to `public/js/bundle.js` and `public/css/style.css`, and proxies through BrowserSync (see `browserSyncProxy` in `webpack.config.js` — update it if your Local site runs on a different port).

## 4. Build for production

Before pushing, always generate the production build:

```bash
npm run build
```

This runs webpack with `NODE_ENV=production`, which:

- Clears out the old files in `public/js` and `public/css`
- Outputs minified, content-hashed bundles (e.g. `public/js/bundle.<hash>.js`, `public/css/style.<hash>.css`)
- Regenerates `public/webpack.manifest.json`, which maps `bundle.js`/`style.css` to the current hashed filenames — this is what PHP reads to enqueue the right built file, so it must be committed alongside the bundles.

## 5. Commit and push

The compiled output in `public/` is committed to the repo (it's not gitignored) since WordPress serves the theme directly from these files — there is no build step on the server.

```bash
git status                 # review what changed (PHP + public/js, public/css, webpack.manifest.json)
git add <changed files>
git commit -m "Describe what changed"
git push origin master
```

Notes:

- Always run `npm run build` (not just `npm run dev`) before your final commit — dev output is unminified and not content-hashed, and shouldn't be pushed.
- Make sure `public/webpack.manifest.json` is included in the commit whenever `public/js` or `public/css` change — a stale manifest will point PHP at bundle filenames that no longer exist.
- The repo currently has one branch (`master`) with direct pushes — there is no PR/staging branch in use.
