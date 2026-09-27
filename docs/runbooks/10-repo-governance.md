# Runbook 10 — Repository governance

What `scripts/github/apply-governance.sh` does, how to run it, what state the
repository is in right now, and how to turn the ruleset on when the
preconditions are met.

Implements ADR-002 (protect `main`, vendor role, break-glass), ADR-003
(short-lived branches off `main`), ADR-007 (environments, no secrets on
`pull_request`) and ADR-009 (`lint` and `build` as the required checks).

- Repository: `centre-for-dpi/website-v2` (public)
- Script: `scripts/github/apply-governance.sh`
- Break-glass log: `BREAKGLASS.md`
- Code owners: `.github/CODEOWNERS`

## 1. What the script is for

Every governance setting in this project is an API call, and every API call is
in this one script. That is deliberate:

- **Reviewable.** The settings arrive by PR like everything else, instead of
  being clicked into a settings page where nobody can see what changed.
- **Idempotent.** It reads current state first and only writes what differs, so
  it is safe to rerun after a GitHub UI change, a new reviewer, or a doubt about
  whether something is still set. A second run prints only `already:` lines.
- **Auditable.** `--check` shows exactly what a run would change without
  changing anything.

Output prefixes:

| Prefix | Meaning |
| --- | --- |
| `already:` | Already in the desired state; nothing was sent |
| `changed:` | An API call was made; state now matches |
| `would:` | `--check` only: what a real run would send |
| `manual:` | The token cannot do this; a human must. Recorded in section 7 |
| `info:` | Read-only output (audit lists, verification, ids) |
| `warn:` / `note:` | Look at this |

## 2. Requirements

- `gh` authenticated as a **repository admin** with scopes `repo`, `workflow`,
  `read:org`. Check with `gh auth status`.
- `jq`.
- Organization **owner** rights are *not* required and are *not* used. That is
  why the ruleset's bypass actor is `OrganizationAdmin` rather than a dedicated
  `cdpi-breakglass` team: creating a team is an org-owner action, and the repo
  owner here is an org member. (This is the correction to ADR-002's "rulesets
  are bypassable by admins", which is true for classic branch protection and
  false for rulesets — rulesets bind admins unless they are listed as bypass
  actors, and individual users cannot be bypass actors at all.)

## 3. Parameters

All via environment variables, all with defaults:

| Variable | Default | Notes |
| --- | --- | --- |
| `REPO` | `centre-for-dpi/website-v2` | |
| `VENDOR_LOGINS` | `bandekarkunal` | Space separated. Third-party accounts that must hold Write |
| `PROD_REVIEWERS` | `adammwaniki justMuriithi` | Logins; the script resolves them to numeric ids |
| `ARCHITECT_LOGINS` | `adammwaniki justMuriithi vvujjini kamyachandra` | Admins not on this list are reported every run |
| `RULESET_NAME` | `protect-main` | |
| `RULESET_ENFORCEMENT` | `evaluate` | `evaluate` or `disabled`. `active` is rejected here — use `--activate` |
| `ACTIONS_APP_ID` | `15368` | GitHub Actions app id, pinned into the required checks |
| `ENVIRONMENTS` | `build staging production` | |

Flags: `--check` (dry run), `--activate` (see section 6), `--help`.

## 4. Every API call it makes, in order

### (a) Audit — ADR-002 "admin membership is audited"

```
GET /repos/{REPO}/collaborators?affiliation=direct&per_page=100
GET /repos/{REPO}/collaborators?affiliation=all&per_page=100
GET /user
GET /orgs/{ORG}/memberships/{me}
```

Prints login + `role_name` for both affiliations (the distinction matters: a
repo-level collaborator call cannot change access that comes from an org team),
prints the current user's org role, and **warns about every admin collaborator
not in `ARCHITECT_LOGINS`**. That warning is the recurring audit ADR-002 asks
for; it appears on every run so it cannot be forgotten.

### (b) Vendor role → Write

```
PUT /repos/{REPO}/collaborators/{login}   {"permission":"push"}
GET /repos/{REPO}/collaborators?affiliation=direct   (verify)
```

Only if the current `role_name` is not `write`. For an existing **direct**
collaborator this takes effect immediately — no invitation is created and the
user does not have to accept anything. The script re-reads and prints the new
role.

If the account is not a direct collaborator (access via an org team), the script
prints instructions instead of failing, because the collaborator endpoint would
not remove team-derived access:
Organization → Teams → *team* → Repositories → this repo → Write.

### (c) Actions permissions — ADR-007

```
GET  /repos/{REPO}/actions/permissions/workflow
PUT  /repos/{REPO}/actions/permissions/workflow
     {"default_workflow_permissions":"read","can_approve_pull_request_reviews":false}
GET  /repos/{REPO}/actions/permissions/fork-pr-contributor-approval
PUT  /repos/{REPO}/actions/permissions/fork-pr-contributor-approval
     {"approval_policy":"all_external_contributors"}
```

Read-only `GITHUB_TOKEN` by default (workflows that need more ask for it
explicitly), and Actions cannot approve pull requests — otherwise a workflow
could satisfy the ruleset's review requirement. Fork PRs from external
contributors need a maintainer to press "Approve and run"; the repo is public
and the vendor is third-party, so an unreviewed fork PR must never start a
workflow. If the fork-PR endpoint returns 404/403 on your token, set it in the
UI: **Settings → Actions → General → "Approval for running fork pull request
workflows" → "Require approval for all external contributors"**.

### (d) Secret scanning and push protection

```
GET   /repos/{REPO}                     (.security_and_analysis)
PATCH /repos/{REPO}
      {"security_and_analysis":{"secret_scanning":{"status":"enabled"},
                                "secret_scanning_push_protection":{"status":"enabled"}}}
```

Free on public repositories. Push protection rejects a commit containing a
recognised credential at push time rather than after it is public.

### (e) Environments — ADR-007

```
GET /repos/{REPO}/environments/{name}
PUT /repos/{REPO}/environments/{name}
POST /repos/{REPO}/environments/{name}/deployment-branch-policies
GET  /repos/{REPO}/environments/{name}/deployment-branch-policies   (verify)
```

`build` and `staging`:

```json
{"wait_timer":0,"reviewers":null,
 "deployment_branch_policy":{"protected_branches":false,"custom_branch_policies":true}}
```

`production`:

```json
{"wait_timer":0,"prevent_self_review":true,
 "reviewers":[{"type":"User","id":160830609},{"type":"User","id":44572764}],
 "deployment_branch_policy":{"protected_branches":false,"custom_branch_policies":true}}
```

Then for each environment, if not already present:

```json
{"name":"main","type":"branch"}
{"name":"v*","type":"tag"}
```

Why this matters: ADR-007 as written says "no secrets on `pull_request`", which
is not enough. A same-repo PR branch can read **repository-level** secrets, and
the vendor has Write, so they can open such a branch. Putting every secret in an
environment with a deployment branch policy of `main` + tags `v*` means a PR
branch cannot reach them at all, regardless of what a workflow asks for.

`prevent_self_review: true` on `production` means the person who triggered the
deployment cannot be the one who approves it.

### (f) Ruleset on the default branch — ADR-002, ADR-009

```
GET  /repos/{REPO}/rulesets
GET  /repos/{REPO}/rulesets/{id}
POST /repos/{REPO}/rulesets           (create)
PUT  /repos/{REPO}/rulesets/{id}      (update)
```

Body:

```json
{
  "name": "protect-main",
  "target": "branch",
  "enforcement": "evaluate",
  "bypass_actors": [
    { "actor_id": 1, "actor_type": "OrganizationAdmin", "bypass_mode": "always" }
  ],
  "conditions": { "ref_name": { "include": ["~DEFAULT_BRANCH"], "exclude": [] } },
  "rules": [
    { "type": "deletion" },
    { "type": "non_fast_forward" },
    { "type": "pull_request", "parameters": {
        "required_approving_review_count": 1,
        "dismiss_stale_reviews_on_push": true,
        "require_code_owner_review": true,
        "require_last_push_approval": true,
        "required_review_thread_resolution": false,
        "allowed_merge_methods": ["merge", "squash"] } },
    { "type": "required_status_checks", "parameters": {
        "strict_required_status_checks_policy": true,
        "do_not_enforce_on_create": false,
        "required_status_checks": [
          { "context": "lint",  "integration_id": 15368 },
          { "context": "build", "integration_id": 15368 } ] } }
  ]
}
```

Notes on the comparison logic, so a future reader does not "fix" it:

- GitHub echoes back **more** than we send (`required_reviewers: []`,
  `dismissal_restriction`, `require_extra_approval_for_unattributed_changes`,
  …). The script therefore checks that the desired ruleset is a *subset* of the
  live one rather than equal to it, otherwise every run would report a change.
- GitHub returns `"actor_id": null` for `OrganizationAdmin` even though the API
  requires `"actor_id": 1` on the way in. The comparison normalises that.
- If the API rejects an optional field (`allowed_merge_methods`,
  `do_not_enforce_on_create` are the likely candidates on older API versions),
  the script drops both, retries once, and prints a `note:` saying they were not
  set so you can check them in Settings → Rules. On the run recorded below both
  were accepted; no retry happened.
- **If the ruleset is already `active` and `RULESET_ENFORCEMENT` is `evaluate`,
  the script keeps it `active`** and prints a note. A routine rerun after
  activation must not quietly disarm the repository.

### (g) Summary

Counts changes, and lists every `note:`, `warn:` and `manual:` item so nothing
that needs a human is buried in the scroll-back.

## 5. Running it

Always dry-run first:

```bash
./scripts/github/apply-governance.sh --check
```

Then apply, then rerun to confirm convergence (the second run must print only
`already:` lines and `0 change(s) applied`):

```bash
./scripts/github/apply-governance.sh
./scripts/github/apply-governance.sh
```

## 6. Current state after WP2, and how to activate

The ruleset was created with **`enforcement: evaluate`**, deliberately. Verified
on 2026-09-27:

| Item | State |
| --- | --- |
| Vendor `bandekarkunal` | `write` (was `admin`) |
| Ruleset `protect-main` | id **24081976**, **`enforcement: evaluate`**, rules `deletion`, `non_fast_forward`, `pull_request`, `required_status_checks`, bypass `OrganizationAdmin/always`, refs `~DEFAULT_BRANCH` |
| Environments | `build`, `staging`, `production` — all `custom_branch_policies: true`, each with branch `main` + tag `v*` |
| `production` reviewers | `adammwaniki`, `justMuriithi`, `prevent_self_review: true` |
| Workflow token | `read`, `can_approve_pull_request_reviews: false` |
| Fork PR approval | `all_external_contributors` |
| Secret scanning | `enabled`; push protection `enabled` |

### What `evaluate` means

The ruleset is fully configured but **enforces nothing**. Pushes and merges that
would have been blocked are allowed and *recorded* instead. Read them at
**Settings → Rules → Insights** (filter by the `protect-main` ruleset): each row
shows the actor, the ref, and which rule would have failed. That is a dry run of
the policy against real traffic.

### Why it is not active yet

Two reasons, both temporary:

1. **The vendor has not been briefed.** ADR-002 requires the break-glass path to
   be documented *before* the vendor's first blocked push, not after. Turning
   enforcement on before the briefing means their next push fails with no
   explanation and no documented escape hatch.
2. **During the rollout the repo admin is the author of every PR**, and
   `required_approving_review_count: 1` plus `require_last_push_approval: true`
   means an author cannot approve their own PR. A single active architect would
   be locked out of their own repository by their own ruleset, and the only way
   out would be an org owner bypass — a break-glass entry per PR, which makes
   the log meaningless.

### Activation (a deliberate, separate step)

Preconditions, all three:

1. The vendor (Think201, `bandekarkunal`) has been briefed on the new flow:
   branch → PR → checks → review → merge, and on `BREAKGLASS.md`.
2. `BREAKGLASS.md` is merged to `main` (so the break-glass path is documented
   before the first blocked push).
3. **At least two architects are available to review each other's PRs.** Both
   `@adammwaniki` and `@justMuriithi` must have Write or better — they are also
   the CODEOWNERS, so without a second one no PR touching `.github/`,
   `Dockerfile`, `docker/`, `deploy/`, `composer.*`, `scripts/`, `BREAKGLASS.md`
   or `docs/adr/` can ever be merged.

Then:

```bash
CONFIRM_ACTIVATE=yes scripts/github/apply-governance.sh --activate
```

The flag prints a loud banner describing exactly what changes, and refuses to
proceed without `CONFIRM_ACTIVATE=yes` in the environment. It reads the ruleset
back, flips only `enforcement`, and `PUT`s the whole resource (the rulesets `PUT`
replaces rather than patches, so a partial body would drop the rules).

Verify:

```bash
gh api repos/centre-for-dpi/website-v2/rulesets \
  --jq '.[] | select(.name=="protect-main") | {id, enforcement}'
```

Acceptance tests from the plan, to run right after activation:

```bash
# direct push to main must be rejected
git push origin HEAD:main        # expect: protected branch / ruleset violation
```

…and open a throwaway PR: green checks but no approval must still show "Merging
is blocked"; an approval must unblock it.

### Backing it out

```bash
# relax without deleting (keeps the configuration and the insights)
gh api --method PUT repos/centre-for-dpi/website-v2/rulesets/24081976 \
  --input <(gh api repos/centre-for-dpi/website-v2/rulesets/24081976 \
            --jq '{name,target,enforcement:"disabled",bypass_actors,conditions,rules}')
```

Restoring the vendor's admin role, if that is ever wanted:
`gh api --method PUT repos/centre-for-dpi/website-v2/collaborators/bandekarkunal -f permission=admin`.

## 7. Items that needed manual action

**None.** Every call in section 4 succeeded with the `adammwaniki` token
(scopes `repo`, `workflow`, `read:org`). Specifically, the two calls most likely
to need an org owner did not:

- `fork-pr-contributor-approval` was readable and already
  `all_external_contributors`, so no write was needed.
- The ruleset accepted `allowed_merge_methods` and `do_not_enforce_on_create`;
  no reduced-body retry was required.

If a future run prints `manual:` lines, add them here with the date and who
cleared them.

### Recommended follow-ups that this script does **not** do

- **`can_admins_bypass` is `true` on all three environments** (GitHub's
  default). A repository admin can therefore skip the `production` required
  reviewers when deploying. Both required reviewers are currently admins, so it
  changes little today, but it should be tightened once WP8/WP9 make production
  deploys real:

  ```bash
  gh api --method PUT repos/centre-for-dpi/website-v2/environments/production \
    -F can_admins_bypass=false -F prevent_self_review=true \
    -F 'reviewers[][type]=User' -F 'reviewers[][id]=160830609' \
    -F 'reviewers[][type]=User' -F 'reviewers[][id]=44572764'
  ```

  Left as a decision for the release owner rather than applied silently, because
  it can block an emergency production deploy.
- Reduce the non-architect admins (see section 8).

## 8. Admin audit

As of 2026-09-27, after this work package:

| Login | Role | Architect? |
| --- | --- | --- |
| `adammwaniki` | admin | yes |
| `justMuriithi` | admin | yes |
| `vvujjini` | admin | yes |
| `kamyachandra` | admin | yes |
| `bandekarkunal` | **write** (was admin) | no — vendor, Think201 |
| `dabadie` | read | |
| `ysaias-alvarez-cdpi` | read | |
| `antonyCdpi` | read | |

**Recommendation.** Four admins is more than this repository needs. Admin is not
about merging — the ruleset governs that — it is about *changing the controls*:
editing workflows, rewriting the ruleset, reading and setting secrets, deleting
the repository. Reduce `vvujjini` and `kamyachandra` to Write unless they
actively administer repository settings, and keep the admin set to the people who
own ADR-002. The script reports any admin outside `ARCHITECT_LOGINS` on every
run, so if a vendor or contractor is re-promoted it shows up on the next
governance run rather than at the next incident.

`bandekarkunal` at Write can still open PRs, push branches and review — the only
things lost are direct pushes to `main`, repository settings, and secrets.

## 9. Re-running after a change

The script is the only way these settings should be edited; if someone changes
something in the UI, the next run reports and corrects it.

**Adding or removing a production reviewer:**

```bash
PROD_REVIEWERS="adammwaniki justMuriithi someoneElse" \
  ./scripts/github/apply-governance.sh --check
PROD_REVIEWERS="adammwaniki justMuriithi someoneElse" \
  ./scripts/github/apply-governance.sh
```

The script resolves each login to a numeric id itself. Make the same change to
the default in the script and commit it, or the next plain run reverts it.

**Adding or removing an architect** (changes only the audit warning):
`ARCHITECT_LOGINS="..." ./scripts/github/apply-governance.sh`.

**Adding a vendor account:** `VENDOR_LOGINS="bandekarkunal otherVendor"`.

**Changing the required checks:** edit `ruleset_body()` in the script. If a job
in `.github/workflows/ci.yml` is renamed, the ruleset keeps requiring the old
context and every PR blocks forever — rename in both places in the same PR. The
current contexts are `lint` and `build`, both pinned to `integration_id` 15368
(GitHub Actions) so no third-party app can post a passing status under those
names.

**After adding a new environment:** `ENVIRONMENTS="build staging production new"`.

## 10. See also

- `BREAKGLASS.md` — who may bypass, when, and the log
- `.github/CODEOWNERS` — the paths requiring an architect's review
- `docs/adr/ADR-001-010-cicd.md` — ADR-002, ADR-003, ADR-007, ADR-009
- Settings → Rules → Insights — what `evaluate` mode has recorded
