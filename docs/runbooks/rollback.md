# Runbook — Rollback

Rollback means **redeploying a previous image tag** (ADR-010). It does not
touch the database, and it does not revert git.

Two routes. Prefer the first: the approval on the production environment is
the audit trail.

---

## 1. Through the pipeline (preferred)

A rollback is a manual dispatch of the Release workflow with an older tag.
It is the same `deploy-<env>` job a merge runs, just with a tag you chose:

```bash
# staging
gh workflow run release.yml -f image_tag=sha-<known-good-40-hex> -f environment=staging

# production (held until a required reviewer approves)
gh workflow run release.yml -f image_tag=sha-<known-good-40-hex> -f environment=production

gh run list --workflow release.yml -L 3   # find the run id
gh run watch <run-id>
```

What the run does: a `validate` job rejects anything that is not
`sha-<40 hex>` or `vX.Y.Z` (the host wrapper checks the same pattern again),
then `deploy-<env>` sends `deploy <tag>` over SSH to the host, streams the
host's ten-step log, and finally fetches `https://<site>/` from the runner
until it serves HTTP 200 with `<meta name="cdpi-build" content="<sha>">`. A
red run means the host has already rolled itself back (section 3) or the
external check never saw the new marker; read the job log.

**The production approval gate.** The `production` GitHub environment has
required reviewers, so a production dispatch sits in "waiting" until one of
them approves it in the Actions UI (or `gh run watch` shows it). Nothing
touches the production host before that click. The approval, with the tag,
who dispatched, who approved and when, is the audit trail for the rollback;
there is no separate change ticket to raise. Two more things gate it:

- the repo variable `PRODUCTION_DEPLOYS_ENABLED` must be `true` (set at
  cutover, runbook 04). Before that, a production dispatch validates the tag
  and ends: the `deploy-production` job is skipped, nothing is contacted;
- the environment's deployment branch policy: dispatch from `main` (the
  default) or a `v*` tag.

Finding the tag to go back to (any of these):

```bash
git log --first-parent --oneline main -20   # each merge commit is a sha- tag
gh run list --workflow release.yml -L 20    # each run's summary names the tag it built or deployed
# on the host, as your named admin account:
ssh <you>@<host> 'sudo /usr/local/sbin/cdpi-deploy-root status'   # current_tag and previous_tag
```

The Release run summary for a deploy shows the tag, the commit the site
actually served after the deploy, and the URL. A `sha-` tag is the full merge
commit SHA on `main`; confirm it exists before dispatching if in doubt:
`docker manifest inspect ghcr.io/centre-for-dpi/website-v2:sha-<sha>`.

## 2. Straight from the host (when GitHub or the workflow is unavailable)

The deploy user's private key exists only as the GitHub secret, so from a
laptop you go in as your named admin account and call the same root script
the wrapper calls:

```bash
ssh <you>@<host> 'sudo /usr/local/sbin/cdpi-deploy-root rollback'
```

That redeploys whatever the host recorded as `previous_tag` in
`/var/lib/cdpi/previous_tag`, through exactly the same ten steps as a deploy —
including a fresh backup before it starts and the smoke check after. If you
need a specific older tag instead of the immediate predecessor:

```bash
ssh <you>@<host> 'sudo /usr/local/sbin/cdpi-deploy-root deploy sha-<older-40-hex>'
```

Both are logged under your account (sudo journal) and in
`/var/log/cdpi-deploy.log`. Note it in the incident record; a host-side
rollback has no approval trail in GitHub.

**Do not roll back by editing `/opt/cdpi/.env` and running
`docker compose up -d` as an admin.** The `wordpress:*-apache` base image
declares `VOLUME /var/www/html`, so a plain `up -d` (even with
`--force-recreate`) re-attaches the previous container's anonymous volume and
keeps serving the *current* files under the *old* tag's ENV — the
`cdpi-build` marker would even claim the rollback worked. The deploy script
runs `up -d --renew-anon-volumes` and prunes the orphaned volume afterwards;
it is the only supported path. See `deploy/README.md` § "The base image's
anonymous volume".

## 3. Automatic rollback

A deploy that fails its smoke check rolls itself back. The host log ends with
one of:

```
=== DEPLOY FAILED, ROLLED BACK TO sha-<prev> ===
=== DEPLOY FAILED, ROLLBACK ALSO FAILED (wanted sha-<prev>) ===
=== DEPLOY FAILED, NO PREVIOUS TAG TO ROLL BACK TO ===
```

and the deploy exits non-zero, so the workflow goes red. After an automatic
rollback the host deliberately leaves `previous_tag` pointing at the tag it
returned to, **not** at the tag that just failed — so a follow-up `rollback`
can never redeploy something already proven broken.

The second and third messages mean the site is down or serving the bad tag:
treat it as an incident, read `/var/log/cdpi-deploy.log`, and go to
`backup-restore.md` if content is involved.

## 4. When NOT to roll back

**Do not roll back across a WordPress core version.** `wp core update-db`
migrates the database forward as part of a deploy; an older image's core cannot
read a newer schema, and rolling the image back does not roll the database
back. If the bad deploy included a core bump (the `FROM` line in the
`Dockerfile` changed, usually via Dependabot):

1. Do **not** run `rollback`.
2. Restore the pre-deploy database dump from `/var/backups/cdpi/db-<ts>.sql.gz`
   — the deploy took one immediately before migrating — see
   `backup-restore.md`.
3. Then redeploy the previous tag.

Checking, before you roll back:

```bash
ssh <you>@<host> 'sudo /usr/local/sbin/cdpi-deploy-root status'   # current and previous tags
git diff <prev-sha>..<current-sha> -- Dockerfile composer.lock
```

Also weigh against rolling back:

- **Content changed since the deploy.** Rolling the image back is safe for
  content (uploads and the database are outside the image), but if the incident
  involved deleted content, restore the content first.
- **A plugin data migration ran.** Same reasoning as core: check
  `composer.lock` in the diff.
- **The deploy is not the cause.** A CDN, DNS or MySQL problem is not fixed by
  a different image tag. `cdpi-deploy-root status` plus
  `/var/log/cdpi-deploy.log` will say whether the current tag is the one you
  expect.

## 5. Last resort on production, pre-cutover shape

Until runbook 04 disables it, Apache and `/var/www/cdpi-website` are still on
the box and untouched:

```bash
cd /opt/cdpi && sudo docker compose -f compose.yaml -f compose.production.yaml down
sudo rsync -a "$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)/" \
  /var/www/cdpi-website/wp-content/uploads/
sudo systemctl start apache2
```

Under two minutes, and the database is untouched by design. `/var/www/cdpi-website`
is kept for 14 days after cutover for exactly this.

## 6. Afterwards

- Note what happened and which tag is live.
- If a ruleset bypass or a break-glass login was involved, add the
  `BREAKGLASS.md` entry within 24 hours.
- Fix forward: the reverted change still needs a PR.
