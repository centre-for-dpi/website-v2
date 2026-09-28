# Runbook 30 — GHCR image retention

Purpose: keep the container registry from growing without bound, without ever
removing an image an environment might need to roll back to.

Workflow: `.github/workflows/ghcr-retention.yml`
Package: `ghcr.io/centre-for-dpi/website-v2` (private)
Decision: ADR-004, "Retention policy".

## 1. The policy

| Class | Rule |
| --- | --- |
| Untagged manifests | Deleted once older than 1 day. |
| `sha-<40hex>` tags | Deleted once older than `older_than_days` (default 90). |
| `v<X.Y.Z>` release tags | Never deleted, at any age. |
| Safety floor | The newest **10** `sha-` tagged versions always survive, whatever their age. |

`sha-` tags are produced on every merge to `main`; `v*` tags on release tags.
There is no `latest` tag, and a `sha-` tag is never re-pushed (the release
workflow checks `docker manifest inspect` and skips the push if the tag already
exists), so an untagged manifest is always debris, never a live artifact.

### Why the safety floor exists

The 90-day rule is an age rule, and age is not the same as "in use". If the
repository goes quiet for four months, the running staging and production images
are both older than 90 days. Without a floor, the first scheduled run after a
quiet period would delete the very image production is running and every image
it could roll back to. `keep-n-tagged: 10` makes that impossible: at least the
10 newest `sha-` versions are kept no matter how old they are.

In practice the floor is more generous than 10, because the age filter is
applied first: everything younger than the threshold is already out of scope, and
the floor then keeps the 10 newest of what remains. Release tags are excluded
before any rule runs and do not consume floor slots.

## 2. How the policy maps onto the action

The workflow uses `dataaxiom/ghcr-cleanup-action` (multi-architecture and
attestation aware), pinned by commit SHA with the version in a trailing comment.
Its algorithm, per package, is:

1. Load every package version and its manifest.
2. Drop child images (multi-arch platform layers, referrers, cosign artifacts).
3. Drop `exclude-tags` matches — these are removed from the working set, so they
   are immune to every later rule.
4. Drop anything *younger* than `older-than`.
5. Stage `delete-tags` matches for deletion.
6. Apply `keep-n-tagged` / `keep-n-untagged` / `delete-untagged`.
7. Delete the staged versions, including their children.

Two consequences shape the workflow:

- **`older-than` narrows every rule, including `delete-untagged`.** A single
  step with `older-than: 90 days` and `delete-untagged: true` would leave
  untagged debris younger than 90 days in place for months. The two classes have
  different age thresholds — 90 days for `sha-` tags, 1 day for untagged debris
  — so the workflow runs **two steps**, each with its own `older-than`. The tag
  pass runs first so anything it strands is mopped up in the same run.
- **With `delete-tags` set, `keep-n-tagged` applies only to the matched subset.**
  That is exactly the floor semantics we want: of the `sha-` tags older than the
  threshold, keep the newest 10 and delete the tail. (With `delete-tags` unset it
  would instead operate on *all* tagged images, which is not what we want.)

Step inputs, for reference:

```yaml
# pass 1 - aged sha- tags
delete-tags: 'sha-*'
exclude-tags: 'v*'
older-than: '<older_than_days> days'
keep-n-tagged: '10'

# pass 2 - untagged debris
delete-untagged: true
exclude-tags: 'v*'
```

`validate: true` is set on both passes. It is informational: after the run it
re-checks that every multi-architecture image still has its platform children
and warns if not. It never fails the job.

`owner` is the organisation `centre-for-dpi` and `packages` is `website-v2`. The
`repository` input is deliberately omitted — in this version of the action it is
diagnostic only and does not affect which packages are touched.

## 3. Triggers and permissions

- **Schedule:** Monday 03:00 UTC (`cron: '0 3 * * 1'`). Scheduled runs are
  always real, never dry-run.
- **Manual:** `workflow_dispatch` with two inputs:
  - `dry_run` (boolean, **default true**) — list candidates, delete nothing.
  - `older_than_days` (string, default `90`) — validated as an integer between
    1 and 3650. A value below 1 is rejected, because the action treats a
    zero-length interval as "no age filter" and the run would fall back to the
    bare 10-version floor.
- `permissions: packages: write` + `contents: read`. No other secret is used;
  the token is the injected `GITHUB_TOKEN`.
- `concurrency: group: ghcr-retention`, `cancel-in-progress: false`. The action
  is not safe to run twice in parallel against the same package, and a
  half-finished deletion pass is worse than a delayed one, so runs queue and are
  never cancelled.

Only the copy of the workflow on the default branch is scheduled, and manual
dispatch also runs the default-branch copy. Changes to the schedule take effect
only after merge to `main`.

## 4. First real run (do this after WP3)

The package does not exist until the release workflow publishes the first image.
The workflow handles that: a step queries
`GET /orgs/centre-for-dpi/packages/container/website-v2` and, on a 404, records
a notice and skips both cleanup steps. Any *other* error (auth, rate limit) fails
the job loudly rather than being swallowed — a permanently skipping retention
workflow is a silent failure.

Once WP3 has merged and a few `sha-` images exist:

```bash
# 1. dry run at the real threshold
gh workflow run ghcr-retention.yml -f dry_run=true -f older_than_days=90
gh run watch "$(gh run list --workflow=ghcr-retention.yml --limit 1 --json databaseId --jq '.[0].databaseId')"
```

Read the log. Expect, with a young package, an empty candidate list for the tag
pass ("no matching tags found" / "no tagged images found to delete") and an empty
or short untagged list. Confirm that:

- no `v*` tag appears anywhere in a delete listing;
- the tags staging and production are currently running do not appear;
- the count of `sha-` versions listed as kept is at least 10 (or all of them, if
  there are fewer than 10 in total).

To see what the rule *would* do to an older estate without waiting 90 days, dry
run with a small threshold — this deletes nothing and is the quickest way to
prove the floor holds:

```bash
gh workflow run ghcr-retention.yml -f dry_run=true -f older_than_days=1
```

Only then run for real:

```bash
gh workflow run ghcr-retention.yml -f dry_run=false -f older_than_days=90
```

After a real run, verify the live tags still pull. From the staging host:

```bash
source /etc/cdpi/registry.env   # root-only
docker pull ghcr.io/centre-for-dpi/website-v2:<current staging tag>
docker pull ghcr.io/centre-for-dpi/website-v2:<previous staging tag>
```

Both must succeed; the second is the rollback target.

## 5. Scheduled workflows are disabled after 60 days of inactivity

GitHub disables `schedule` triggers in a repository with **no commit activity
for 60 days**. It emails repository admins before doing so. This repository is
low-traffic by design, so the condition is realistic — and retention silently
stopping is exactly the kind of drift nobody notices.

Check whether the workflow is still active:

```bash
gh api repos/centre-for-dpi/website-v2/actions/workflows \
  --jq '.workflows[] | select(.path | endswith("ghcr-retention.yml")) | {state, path}'
```

`"state": "active"` is healthy. `"disabled_inactivity"` means the schedule was
switched off. Re-enable it:

```bash
gh workflow enable ghcr-retention.yml
```

Re-enabling does not run the workflow; dispatch it manually once afterwards
(`dry_run=true` first) and confirm the next scheduled run appears.

Include this check in the periodic review alongside the Dependabot cadence
review (WP11). A merge to `main` resets the 60-day clock, so in normal operation
this never triggers.

A related reason to keep the weekly cadence: the action caches distilled
manifest data via `@actions/cache`, and GitHub evicts cache entries not read for
7 days. Running at least weekly keeps the cache warm and the run cheap. Do not
stretch the schedule beyond weekly.

## 6. Recovering a version deleted by mistake

Every deletion is logged with the package version ID. GitHub allows restoring a
deleted package version for **30 days**.

Find the ID in the run log, then:

```bash
gh api -X POST \
  /orgs/centre-for-dpi/packages/container/website-v2/versions/<version_id>/restore
```

If the image is past the restore window, rebuild it instead: check out the
commit and re-run the release workflow, or build and push locally. The image is
reproducible from the repository — that is the point of ADR-004 — so the
worst case is a rebuild, not a lost artifact.

## 7. Known limitations

- **Deletion needs package-level access.** The package must grant this
  repository the Admin role under *Package settings → Manage Actions access*.
  A package created by this repository's own workflow gets that link
  automatically; if the package is ever recreated by hand, re-check it. A
  missing link shows up as a 403 on the delete call, not as a silent no-op.
- **Public packages downloaded more than 5,000 times cannot be deleted** by
  GitHub policy, and there is no API to read the download count. Not a concern
  while the package stays private (ADR-004); if it is ever made public, the
  workaround is to add the affected tags to `exclude-tags`.
- **Nested manifest indices are not supported** by the action's child walk.
  Standard buildx output is a single-level index, and the release workflow sets
  `provenance: false` and `sbom: false` (attestations would create untagged
  manifests this workflow would then delete), so this does not arise here.
- **Race with an in-flight build.** The untagged sweep has no age condition by
  design. If a release build were pushing a multi-architecture image at the exact
  moment of the sweep, its platform manifests could briefly look like orphaned
  untagged debris — the parent index that identifies them as children is pushed
  last. The window is seconds, once a week, at 03:00 Monday UTC, and the failure
  mode is a failed build to re-run, not a lost release. If it ever happens, add
  `older-than: 1 day` to the *untagged sweep step only*; untagged debris is not
  urgent and a one-day grace period costs nothing.
