# Runbook — Rollback

Rollback means **redeploying a previous image tag** (ADR-010). It does not
touch the database, and it does not revert git.

Two routes. Prefer the first: the approval on the production environment is
the audit trail.

---

## 1. Through the pipeline (preferred)

```bash
# staging
gh workflow run release.yml -f image_tag=sha-<known-good-40-hex> -f environment=staging

# production (waits for a required reviewer)
gh workflow run release.yml -f image_tag=sha-<known-good-40-hex> -f environment=production

gh run watch
```

`image_tag` is regex-validated by the workflow (`^sha-[0-9a-f]{40}$` or
`^v[0-9]+\.[0-9]+\.[0-9]+$`) and again by the host wrapper, so a typo fails
fast rather than half-deploying.

Finding the tag to go back to:

```bash
ssh deploy@<host> status                 # current_tag and previous_tag on the box
gh run list --workflow release.yml -L 20 # recent deploys
git log --oneline -20                    # the sha- tag is the commit SHA
```

## 2. Straight from the host (when GitHub or the workflow is unavailable)

```bash
ssh deploy@<host> rollback
```

That redeploys whatever the host recorded as `previous_tag` in
`/var/lib/cdpi/previous_tag`, through exactly the same nine steps as a deploy —
including a fresh backup before it starts and the smoke check after. If you
need a specific older tag instead of the immediate predecessor:

```bash
ssh deploy@<host> deploy sha-<older-40-hex>
```

Nothing else is accepted over that SSH connection; there is no shell.

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
ssh deploy@<host> status                  # current and previous tags
git diff <prev-sha>..<current-sha> -- Dockerfile composer.lock
```

Also weigh against rolling back:

- **Content changed since the deploy.** Rolling the image back is safe for
  content (uploads and the database are outside the image), but if the incident
  involved deleted content, restore the content first.
- **A plugin data migration ran.** Same reasoning as core: check
  `composer.lock` in the diff.
- **The deploy is not the cause.** A CDN, DNS or MySQL problem is not fixed by
  a different image tag. `ssh deploy@<host> status` plus
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
