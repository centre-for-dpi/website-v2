# Runbook 03 — Production in checkout mode (pull-based)

Production is the existing MSP-managed Lightsail box: Ubuntu 20.04, Apache,
PHP 8.1, MySQL on the host, `https://cdpi.dev` with a public certificate, no
CDN. It is reachable only through a bastion with a private key that stays with
the release owner, and the release owner's account on it (`adam`) owns
`/var/www/cdpi-website` and has no usable sudo. So, for production only:

- **Nothing connects in.** The GitHub runner never SSHes to production. The
  approved `deploy-production` job moves the git tag `production` to the
  released commit and then watches `https://cdpi.dev/` until it serves it.
- **The box pulls.** `~/bin/cdpi-deploy-theme sync` runs from the operator's
  own crontab every minute: `git ls-remote` the tag; if it points at a commit
  other than the one checked out, fetch, refuse anything not reachable from
  `origin/main` or any hand-edited tracked file, `git checkout --detach` it,
  write `.cdpi-build`, GET the site on loopback (`--resolve cdpi.dev:443:127.0.0.1`)
  until it serves `<meta name="cdpi-build" content="<commit>">`, and revert
  to the previous commit if it does not. One attempt per tag object; a failed
  release is never retried every minute.
- **Nothing needs root.** The script, its state (`~/.local/state/cdpi-deploy/`)
  and the theme checkout are the only things touched. Core, plugins, Apache,
  PHP, MySQL and the host stay the MSP's.
- **Build output stays committed** (`public/js`, `public/css`,
  `public/webpack.manifest.json`): the checkout *is* the deploy. See the
  ADR-004 amendment of 2026-10-26.

The container model of `03-production-prepare.md` and `04` is not in use.
Script reference: the header of `deploy/host/cdpi-deploy-theme`;
self-test: `deploy/host/test-cdpi-deploy-theme.sh`.

`cdpi-prod` below is your usual ssh alias for the box through the bastion
(`ProxyJump` in `~/.ssh/config`); `scp` works through it the same way.

---

## 1. Pre-flight on the host (read-only)

```bash
ssh cdpi-prod
T=/var/www/cdpi-website/wp-content/themes/cdpi-wp-theme
git -C $T remote get-url origin          # https://github.com/centre-for-dpi/website-v2(.git)
git -C $T status --porcelain --untracked-files=no | wc -l   # must be 0 (see §9 if not)
git -C $T rev-parse HEAD                 # note it: this is what rollback returns to
ls -ld $T $T/.git                        # owned by you
curl -sS -o /dev/null -w '%{http_code}\n' --resolve cdpi.dev:443:127.0.0.1 https://cdpi.dev/   # 200
which git curl flock logger realpath     # all present (coreutils, git, curl, util-linux)
df -h /var/www
```

Untracked files in the theme directory (the `*.gz` incident debris and the
like) are fine: the script never touches or counts them. `git status` *with*
untracked files will keep listing them; that is expected.

Two things the smoke check depends on: the loopback request must answer `200`
for `https://cdpi.dev/` (no redirect to `www.`, no maintenance page), and no
full-page cache may sit between PHP and the response. PHP's opcache is fine:
`.cdpi-build` is read on every request and templates revalidate within two
seconds, inside the smoke window (10 attempts, 3 s apart).

## 2. Install the script

From the repository on your laptop, on `main` after this PR is merged:

```bash
ssh cdpi-prod 'mkdir -p ~/bin'
scp deploy/host/cdpi-deploy-theme cdpi-prod:~/bin/cdpi-deploy-theme
ssh cdpi-prod 'chmod 0755 ~/bin/cdpi-deploy-theme && ~/bin/cdpi-deploy-theme status'
```

`status` creates `~/.local/state/cdpi-deploy/` and prints HEAD, `origin/main`,
the local `production` tag (none yet), previous/current (none yet),
`.cdpi-build` (none yet), the count of modified tracked files, and `paused`.
Nothing changed.

Updating the script later is the same `scp` + `chmod`; it is not deployed by
the pipeline (it is the thing that deploys).

## 3. Dry runs (change nothing, logged)

```bash
ssh cdpi-prod
~/bin/cdpi-deploy-theme --dry-run deploy sha-<a 40-hex commit on main>
~/bin/cdpi-deploy-theme --dry-run sync
~/bin/cdpi-deploy-theme --dry-run rollback      # refused: nothing recorded yet
```

The first fetches `origin/main`, resolves the commit, checks it is reachable
from `main` and the tracked tree is clean, then prints each step it would
take and `=== DRY RUN OK: nothing changed ===`. The second says
`nothing to do: origin has no tag production`. Accepted forms, exactly:

```
cdpi-deploy-theme [--dry-run] sync
cdpi-deploy-theme [--dry-run] status
cdpi-deploy-theme [--dry-run] deploy <sha-40hex | 40hex | vX.Y.Z>
cdpi-deploy-theme [--dry-run] rollback
```

`--dry-run` only as the first word (`CDPI_DRY_RUN=1` in the environment is
the same). Anything else, including shell metacharacters, exits 126.

## 4. GitHub side: deploy key, secret, SITE_URL, tag ruleset

The workflow moves the tag with a **write deploy key**, because GitHub does
not accept the Actions app as a bypass actor on a repository ruleset in this
organisation (HTTP 422, "must be part of the ruleset source or owner
organization"). The private half lives only in the `production` environment,
behind its required reviewers.

```bash
ssh-keygen -t ed25519 -a 64 -f cdpi-production-tag -C production-tag -N ''
gh repo deploy-key add cdpi-production-tag.pub --allow-write --title production-tag
gh secret   set PRODUCTION_TAG_SSH_KEY --env production < cdpi-production-tag
gh variable set SITE_URL               --env production --body 'https://cdpi.dev'
shred -u cdpi-production-tag            # the secret is the only copy
```

Then the ruleset that makes the deploy key (and org admins) the only things
that can create, move or delete `refs/tags/production`:

```bash
scripts/github/apply-governance.sh --check     # expect one `would:` line, (f2)
scripts/github/apply-governance.sh             # creates protect-production-tag, enforcement=active
gh api repos/centre-for-dpi/website-v2/rulesets \
  --jq '.[] | select(.name=="protect-production-tag") | {id, enforcement, target}'
gh api repos/centre-for-dpi/website-v2/rulesets/<id> \
  --jq '{rules: [.rules[].type], bypass: .bypass_actors, refs: .conditions.ref_name.include}'
# expect: rules creation, update, deletion; bypass OrganizationAdmin + DeployKey; refs/tags/production
```

Record the id in `10-repo-governance.md` §6. Negative check, from your laptop
with your own account (admin, but not org admin):

```bash
git tag -f production origin/main && git push -f origin refs/tags/production   # refused by the ruleset
git tag -d production
```

The SSH values the container model would have used (`DEPLOY_SSH_KEY`,
`DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_KNOWN_HOSTS`, `SMOKE_CA_CERT`) are **not
read** by the production job. If any were set, remove them:

```bash
gh secret   delete DEPLOY_SSH_KEY     --env production
gh variable delete DEPLOY_HOST        --env production
gh variable delete DEPLOY_USER        --env production
gh variable delete DEPLOY_KNOWN_HOSTS --env production
gh variable list --env production     # SITE_URL only
```

## 5. The crontab line

On the box, as the operator. `status` in §2 already created the state
directory the redirection needs.

```bash
crontab -l 2>/dev/null
(crontab -l 2>/dev/null; echo '* * * * * /home/adam/bin/cdpi-deploy-theme sync >> /home/adam/.local/state/cdpi-deploy/cron.log 2>&1') | crontab -
crontab -l
```

The line, exactly:

```
* * * * * /home/adam/bin/cdpi-deploy-theme sync >> /home/adam/.local/state/cdpi-deploy/cron.log 2>&1
```

Overlap is prevented inside the script (`flock -n`; a run that finds the lock
held exits 0 silently). `cron.log` receives output only when a sync acts or
fails; the script truncates it when it passes 1 MB (no logrotate needed).
The full, timestamped record of every deploy is `deploy.log` beside it.

Verify cron runs the job: `rm -f ~/.local/state/cdpi-deploy/cron.log`, wait a
minute, `ls -l ~/.local/state/cdpi-deploy/cron.log` — the shell redirection
recreates it (empty) on the next tick. `tail ~/.local/state/cdpi-deploy/cron.log`
stays empty until there is something to do.

## 6. First release through the pipeline

Pick the commit: the tip of `main` after this PR (any commit on `main` works;
the first one carrying the `.cdpi-build` fallback in `functions.php` is this
PR's merge commit, and earlier commits would fail the smoke because they
cannot serve the marker).

```bash
gh variable set PRODUCTION_DEPLOYS_ENABLED --body true      # repo variable; the job is skipped without it
gh workflow run release.yml -f image_tag=sha-<40-hex on main> -f environment=production
gh run list --workflow release.yml -L 1
gh run watch <run-id>        # waits for a reviewer's approval, then moves the tag and polls
```

A required reviewer (`adammwaniki` or `justMuriithi`, not the dispatcher)
approves in the Actions UI. The job then: checks the commit is on `main`,
moves `production` (annotated tag, run id in the message), and polls
`https://cdpi.dev/` every 15 s for up to 8 minutes. On the box, within a
minute, `sync` sees the tag and deploys; `cron.log` shows the full deploy log
ending in `sync: production is now at <sha>`; the run goes green and its
summary shows the tag's previous target (the rollback target).

Afterwards: `ssh cdpi-prod '~/bin/cdpi-deploy-theme status'` shows HEAD
detached at the commit, `current` = `.cdpi-build` = that commit, `previous` =
the HEAD you noted in §1.

Note the order: the variable gates the job's *existence*, so it must be set
before the dispatch; the dispatch still does nothing until approved.

## 7. Rollback

Preferred (approval = audit trail): dispatch the previous commit. The run
summary of every production deploy prints the exact command.

```bash
gh workflow run release.yml -f image_tag=sha-<previous 40-hex> -f environment=production
```

From the box, when GitHub is unavailable (no approval trail; note it in the
incident record):

```bash
ssh cdpi-prod
touch ~/.local/state/cdpi-deploy/paused     # or the next sync moves it back to the tag
~/bin/cdpi-deploy-theme --dry-run rollback
~/bin/cdpi-deploy-theme rollback             # = deploy of ~/.local/state/cdpi-deploy/previous
# later, once the production tag has been moved to match (dispatch), or you want cron back:
rm ~/.local/state/cdpi-deploy/paused
```

`previous` always holds the commit that was live before the last successful
deploy: after a rollback it points at the commit you rolled back from, so a
second `rollback` goes forward again. After an automatic revert it is left
unchanged, so `rollback` never redeploys the commit that just failed.

## 8. When a release fails

The runner's poll times out with: *production did not pick up the release
within 8 minutes … check cron.log …* Read on the box:

```bash
tail -n 60 ~/.local/state/cdpi-deploy/cron.log
~/bin/cdpi-deploy-theme status
```

- `=== DEPLOY FAILED, REVERTED TO <prev> ===`: the site is back on the
  previous commit, verified. The tag object is recorded in `failed_tag` and
  **not retried**. Fix forward (new PR, new release) or re-dispatch the same
  commit (a new tag object, so a new attempt) once the cause is understood.
- `=== DEPLOY FAILED, REVERT ALSO FAILED (wanted <prev>) ===`: the files are
  back on `<prev>` but the smoke did not pass; the site may be down. Check
  Apache/PHP (`curl -sS --resolve cdpi.dev:443:127.0.0.1 https://cdpi.dev/`),
  then `rollback` re-verifies `<prev>`. Treat as an incident.
- `REFUSED: tracked files modified on the server`: §9.
- `REFUSED: commit … is not reachable from origin/main`: the tag points at an
  unmerged commit; it cannot happen through the workflow (it checks the same
  thing) and means someone moved the tag by hand with a bypass.
- Nothing in `cron.log` at all: cron is not running the line (`crontab -l`),
  or the state directory is missing, or the tag was not moved (run log).

## 9. If `status` shows a dirty tree

`dirty_tracked` > 0 means someone changed tracked theme files on the server.
The script refuses to deploy over that, because a checkout would silently
discard the edit. Decide what the edit is:

```bash
cd /var/www/cdpi-website/wp-content/themes/cdpi-wp-theme
git status --porcelain --untracked-files=no
git diff
```

- A hotfix someone needs: get it into a PR, merge, release; then discard the
  server copy: `git checkout -- .`
- Noise (mode changes after a `chmod -R`, CRLF): `git diff` shows `old mode /
  new mode` only → `git config core.fileMode false`, or `git checkout -- .`
- Unknown: ask; do not discard before you know. Meanwhile releases are
  refused, not broken.

## 10. Limits of this mode

- `git checkout` writes changed files one by one: a request during that
  sub-second window can see two commits mixed. Accepted for a theme of this
  size; the container model is what removes it.
- A release takes up to a minute to start (cron) plus the smoke window.
- The checkout is detached after the first deploy. Do not `git pull` in it;
  `sync`, `deploy` and `rollback` are the only supported ways to move it.
- Interactive `deploy`/`rollback` are overridden by the next `sync` unless
  `paused` exists (§7).
