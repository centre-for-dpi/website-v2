#!/usr/bin/env bash
#
# apply-governance.sh — apply the WP2 repository governance decisions
# (ADR-002, ADR-003, ADR-007) to a GitHub repository, idempotently.
#
# Everything this script does is an API call through `gh api`. It reads the
# current state first and only writes what differs, so it is safe to rerun.
# Each item prints exactly one line, prefixed:
#
#   already:  the repo is already in the desired state, nothing was sent
#   changed:  an API call was made and the state now matches
#   would:    --check mode only; this is what a real run would send
#   manual:   the call is not possible with this token; a human must do it
#   info:     read-only output (audit, verification, ids)
#   warn:     something you should look at
#
# Requires: gh (authenticated, scopes repo + workflow + read:org), jq.
#
# See docs/runbooks/10-repo-governance.md for the full call list, the
# activation procedure, and what to do about `manual:` items.

set -euo pipefail

# ---------------------------------------------------------------------------
# Parameters (override via the environment)
# ---------------------------------------------------------------------------
REPO="${REPO:-centre-for-dpi/website-v2}"
# Third-party vendor accounts that must hold Write (push), never Admin.
VENDOR_LOGINS="${VENDOR_LOGINS:-bandekarkunal}"
# Required reviewers on the production environment.
PROD_REVIEWERS="${PROD_REVIEWERS:-adammwaniki justMuriithi}"
# Accounts legitimately holding Admin. Any other admin is reported each run,
# which is the recurring audit ADR-002 asks for.
ARCHITECT_LOGINS="${ARCHITECT_LOGINS:-adammwaniki justMuriithi vvujjini kamyachandra}"
RULESET_NAME="${RULESET_NAME:-protect-main}"
# evaluate = log only (Settings -> Rules -> Insights), active = enforce.
# Never set this to active here; use --activate, which is deliberate.
RULESET_ENFORCEMENT="${RULESET_ENFORCEMENT:-evaluate}"
# GitHub Actions is app (integration) id 15368; required_status_checks pins the
# check to that app so a third party cannot post a passing "lint" status.
ACTIONS_APP_ID="${ACTIONS_APP_ID:-15368}"
ENVIRONMENTS="${ENVIRONMENTS:-build staging production}"

DRY_RUN=0
ACTIVATE=0

# ---------------------------------------------------------------------------
# Output helpers
# ---------------------------------------------------------------------------
CHANGED_COUNT=0
WOULD_COUNT=0
MANUAL_ITEMS=()
WARN_ITEMS=()
NOTE_ITEMS=()

already() { printf 'already: %s\n' "$*"; }
changed() { printf 'changed: %s\n' "$*"; CHANGED_COUNT=$((CHANGED_COUNT + 1)); }
would() { printf 'would:   %s\n' "$*"; WOULD_COUNT=$((WOULD_COUNT + 1)); }
info() { printf 'info:    %s\n' "$*"; }
manual() {
    printf 'manual:  %s\n' "$*"
    MANUAL_ITEMS+=("$*")
}
warn() {
    printf 'warn:    %s\n' "$*"
    WARN_ITEMS+=("$*")
}
note() {
    printf 'note:    %s\n' "$*"
    NOTE_ITEMS+=("$*")
}
section() { printf '\n== %s\n' "$*"; }
die() {
    printf 'error:   %s\n' "$*" >&2
    exit 1
}

usage() {
    cat <<'USAGE'
Usage: scripts/github/apply-governance.sh [--check] [--activate] [--help]

  --check     Dry run. Read everything, write nothing; print `would:` lines
              for each change a real run would make.
  --activate  Set enforcement=active on the existing ruleset and nothing else.
              Requires CONFIRM_ACTIVATE=yes in the environment.
  --help      This text.

Environment (defaults in parentheses):
  REPO                (centre-for-dpi/website-v2)
  VENDOR_LOGINS       (bandekarkunal)          space separated
  PROD_REVIEWERS      (adammwaniki justMuriithi)
  ARCHITECT_LOGINS    (adammwaniki justMuriithi vvujjini kamyachandra)
  RULESET_NAME        (protect-main)
  RULESET_ENFORCEMENT (evaluate)               evaluate|disabled
  ACTIONS_APP_ID      (15368)
USAGE
}

while [ $# -gt 0 ]; do
    case "$1" in
    --check | -n | --dry-run) DRY_RUN=1 ;;
    --activate) ACTIVATE=1 ;;
    -h | --help)
        usage
        exit 0
        ;;
    *) die "unknown argument: $1 (try --help)" ;;
    esac
    shift
done

command -v gh >/dev/null 2>&1 || die "gh is not installed"
command -v jq >/dev/null 2>&1 || die "jq is not installed"

TMPDIR_GOV="$(mktemp -d)"
cleanup() { rm -rf "$TMPDIR_GOV"; }
trap cleanup EXIT

ORG="${REPO%%/*}"

# ---------------------------------------------------------------------------
# API helpers
# ---------------------------------------------------------------------------

# api_get <path> [jq-args...] — read-only, never gated by --check.
api_get() { gh api "$@"; }

# mutate <description> <gh api args...>
# Sends the call unless --check. Records, never aborts the run: a permission
# failure becomes a `manual:` item so the rest of the governance still lands.
mutate() {
    local desc="$1"
    shift
    if [ "$DRY_RUN" -eq 1 ]; then
        would "$desc"
        return 0
    fi
    local out
    if out="$(gh api "$@" 2>&1)"; then
        changed "$desc"
        [ -n "$out" ] && printf '         %s\n' "$(printf '%s' "$out" | head -c 400)"
        return 0
    fi
    manual "$desc — API call refused: $(printf '%s' "$out" | tr '\n' ' ' | head -c 300)"
    return 0
}

# login_id <login> — numeric user id, cached in TMPDIR_GOV.
login_id() {
    local login="$1" cache="$TMPDIR_GOV/id.$1"
    if [ ! -f "$cache" ]; then
        api_get "users/$login" --jq .id >"$cache" 2>/dev/null ||
            die "cannot resolve GitHub user '$login'"
    fi
    cat "$cache"
}

# ---------------------------------------------------------------------------
# --activate: the one deliberate, loud step
# ---------------------------------------------------------------------------
do_activate() {
    section "ACTIVATE ruleset '$RULESET_NAME' on $REPO"
    cat <<BANNER
*******************************************************************************
*  THIS SETS THE RULESET TO enforcement=active.                               *
*                                                                             *
*  From the moment it returns:                                                *
*    - direct pushes and force pushes to the default branch are REJECTED      *
*    - every change to the default branch needs a pull request with 1          *
*      approving review from someone other than the author                    *
*    - CODEOWNERS review is required for the paths in .github/CODEOWNERS      *
*    - the 'lint' and 'build' checks must pass, on an up-to-date branch       *
*    - only an organization OWNER can bypass, and every bypass needs a         *
*      BREAKGLASS.md entry within 24 hours                                    *
*                                                                             *
*  Preconditions (see docs/runbooks/10-repo-governance.md):                   *
*    1. the vendor has been briefed                                           *
*    2. BREAKGLASS.md is merged to the default branch                         *
*    3. at least two architects are available to review each other's PRs      *
*       (a lone admin cannot self-approve and will be blocked by their own    *
*       ruleset)                                                              *
*                                                                             *
*  To undo: rerun the script normally, or PUT enforcement=disabled.           *
*******************************************************************************
BANNER
    if [ "${CONFIRM_ACTIVATE:-}" != "yes" ]; then
        die "refusing to activate without CONFIRM_ACTIVATE=yes in the environment"
    fi

    local id
    id="$(api_get "repos/$REPO/rulesets" \
        --jq "[.[] | select(.name == \"$RULESET_NAME\") | .id][0] // empty")"
    [ -n "$id" ] || die "no ruleset named '$RULESET_NAME' on $REPO — run the script without --activate first"

    local current
    current="$(api_get "repos/$REPO/rulesets/$id" --jq .enforcement)"
    if [ "$current" = "active" ]; then
        already "ruleset $RULESET_NAME (id $id) enforcement=active"
        return 0
    fi

    # Read the ruleset back, flip only enforcement, PUT the whole thing: the
    # rulesets PUT replaces the resource, so a partial body would drop rules.
    local body="$TMPDIR_GOV/activate.json"
    api_get "repos/$REPO/rulesets/$id" --jq \
        '{name, target, enforcement: "active", bypass_actors, conditions, rules}' >"$body"
    mutate "ruleset $RULESET_NAME (id $id) enforcement $current -> active" \
        --method PUT "repos/$REPO/rulesets/$id" --input "$body"
    info "enforcement now: $(api_get "repos/$REPO/rulesets/$id" --jq .enforcement)"
}

# ---------------------------------------------------------------------------
# (a) Audit
# ---------------------------------------------------------------------------
step_audit() {
    section "(a) Audit — collaborators and admin membership (ADR-002)"

    local direct all
    direct="$TMPDIR_GOV/collab-direct.json"
    all="$TMPDIR_GOV/collab-all.json"
    api_get "repos/$REPO/collaborators?affiliation=direct&per_page=100" >"$direct"
    api_get "repos/$REPO/collaborators?affiliation=all&per_page=100" >"$all"

    info "direct collaborators (invited to this repo specifically):"
    jq -r '.[] | "           \(.login)\t\(.role_name)"' "$direct"
    info "all collaborators (direct + via organization/team):"
    jq -r '.[] | "           \(.login)\t\(.role_name)"' "$all"

    local me role=""
    me="$(api_get user --jq .login)"
    if role="$(api_get "orgs/$ORG/memberships/$me" --jq '.role + " (" + .state + ")"' 2>/dev/null)"; then
        info "current user $me is $ORG org: $role"
    else
        info "current user $me: organization membership not readable (needs read:org)"
    fi
    if [ "${role%% *}" != "admin" ]; then
        info "$me is not an organization owner, so org-level objects (teams, org"
        info "  settings) cannot be created from here — this is why the ruleset"
        info "  bypass actor is OrganizationAdmin and not a break-glass team."
    fi

    # Admins who are not architects — the recurring ADR-002 audit.
    local extra=()
    while IFS= read -r login; do
        case " $ARCHITECT_LOGINS " in
        *" $login "*) ;;
        *) extra+=("$login") ;;
        esac
    done < <(jq -r '.[] | select(.role_name == "admin") | .login' "$all")

    if [ "${#extra[@]}" -gt 0 ]; then
        warn "admin collaborators who are NOT in ARCHITECT_LOGINS: ${extra[*]}"
        warn "  ADR-002 requires admin membership to be audited. Rulesets bind"
        warn "  admins, but an admin can still edit repo settings and workflows."
        warn "  Reduce each of these to write or read unless there is a reason."
    else
        info "every admin collaborator is a declared architect ($ARCHITECT_LOGINS)"
    fi
}

# ---------------------------------------------------------------------------
# (b) Vendor role -> Write
# ---------------------------------------------------------------------------
step_vendor_role() {
    section "(b) Vendor repository role -> write (ADR-002)"

    local direct all login role is_direct
    direct="$TMPDIR_GOV/collab-direct.json"
    all="$TMPDIR_GOV/collab-all.json"

    for login in $VENDOR_LOGINS; do
        role="$(jq -r --arg l "$login" \
            '[.[] | select(.login == $l) | .role_name][0] // empty' "$all")"
        if [ -z "$role" ]; then
            warn "vendor $login is not a collaborator on $REPO at all — nothing to downgrade"
            continue
        fi
        is_direct="$(jq -r --arg l "$login" \
            '[.[] | select(.login == $l) | .login][0] // empty' "$direct")"

        if [ "$role" = "write" ]; then
            already "vendor $login role=write"
            continue
        fi

        if [ -z "$is_direct" ]; then
            manual "vendor $login has role=$role but is NOT a direct collaborator"
            info "  Their access comes from an organization team, so a repo-level"
            info "  collaborator call would not remove it. An org owner must change"
            info "  the team's repository permission (or remove $login from the team"
            info "  and add them as a direct collaborator with Write):"
            info "    Organization -> Teams -> <team> -> Repositories -> $REPO -> Write"
            continue
        fi

        # Direct collaborator: PUT changes the role immediately, no invitation.
        mutate "vendor $login role $role -> write (push)" \
            --method PUT "repos/$REPO/collaborators/$login" -f permission=push

        if [ "$DRY_RUN" -eq 0 ]; then
            local now
            now="$(api_get "repos/$REPO/collaborators?affiliation=direct&per_page=100" \
                --jq "[.[] | select(.login == \"$login\") | .role_name][0] // \"absent\"")"
            info "verified: $login role_name is now '$now'"
            [ "$now" = "write" ] || warn "$login is '$now', expected 'write'"
        fi
    done
}

# ---------------------------------------------------------------------------
# (c) Actions workflow permissions and fork-PR approval
# ---------------------------------------------------------------------------
step_workflow_permissions() {
    section "(c) Actions permissions (ADR-007)"

    local cur def approve
    cur="$(api_get "repos/$REPO/actions/permissions/workflow")"
    def="$(printf '%s' "$cur" | jq -r .default_workflow_permissions)"
    approve="$(printf '%s' "$cur" | jq -r .can_approve_pull_request_reviews)"

    if [ "$def" = "read" ] && [ "$approve" = "false" ]; then
        already "workflow token: default_workflow_permissions=read, can_approve_pull_request_reviews=false"
    else
        local wbody="$TMPDIR_GOV/workflow-perms.json"
        cat >"$wbody" <<'JSON'
{"default_workflow_permissions":"read","can_approve_pull_request_reviews":false}
JSON
        mutate "workflow token: (read/false) was ($def/$approve)" \
            --method PUT "repos/$REPO/actions/permissions/workflow" --input "$wbody"
    fi

    # Fork PR approval. The repo is public and the vendor is third-party, so a
    # fork PR must never start a workflow without a maintainer saying so.
    local fork out
    if fork="$(api_get "repos/$REPO/actions/permissions/fork-pr-contributor-approval" 2>/dev/null)"; then
        local policy
        policy="$(printf '%s' "$fork" | jq -r .approval_policy)"
        if [ "$policy" = "all_external_contributors" ]; then
            already "fork PR workflow approval: approval_policy=all_external_contributors"
        else
            local fbody="$TMPDIR_GOV/fork-pr.json"
            cat >"$fbody" <<'JSON'
{"approval_policy":"all_external_contributors"}
JSON
            mutate "fork PR workflow approval: all_external_contributors (was $policy)" \
                --method PUT "repos/$REPO/actions/permissions/fork-pr-contributor-approval" \
                --input "$fbody"
        fi
    else
        out="$(api_get "repos/$REPO/actions/permissions/fork-pr-contributor-approval" 2>&1 || true)"
        manual "fork PR workflow approval not readable via the API: $(printf '%s' "$out" | tr '\n' ' ' | head -c 160)"
        info "  Set it in the UI: Settings -> Actions -> General ->"
        info "  \"Approval for running fork pull request workflows\" ->"
        info "  \"Require approval for all external contributors\""
    fi
}

# ---------------------------------------------------------------------------
# (d) Secret scanning + push protection
# ---------------------------------------------------------------------------
step_secret_scanning() {
    section "(d) Secret scanning and push protection"

    local repo_json ss pp private
    repo_json="$(api_get "repos/$REPO")"
    private="$(printf '%s' "$repo_json" | jq -r .private)"
    ss="$(printf '%s' "$repo_json" | jq -r '.security_and_analysis.secret_scanning.status // "unknown"')"
    pp="$(printf '%s' "$repo_json" | jq -r '.security_and_analysis.secret_scanning_push_protection.status // "unknown"')"
    info "repo private=$private, secret_scanning=$ss, push_protection=$pp"
    [ "$private" = "false" ] || warn "repo is private: secret scanning and push protection need GitHub Advanced Security"

    if [ "$ss" = "enabled" ] && [ "$pp" = "enabled" ]; then
        already "secret scanning and push protection enabled"
        return 0
    fi

    local body="$TMPDIR_GOV/security.json"
    cat >"$body" <<'JSON'
{
  "security_and_analysis": {
    "secret_scanning": { "status": "enabled" },
    "secret_scanning_push_protection": { "status": "enabled" }
  }
}
JSON
    mutate "secret_scanning=enabled, secret_scanning_push_protection=enabled (were $ss/$pp)" \
        --method PATCH "repos/$REPO" --input "$body"

    if [ "$DRY_RUN" -eq 0 ]; then
        info "verified: $(api_get "repos/$REPO" --jq \
            '"secret_scanning=" + (.security_and_analysis.secret_scanning.status // "unknown")
             + ", push_protection=" + (.security_and_analysis.secret_scanning_push_protection.status // "unknown")')"
    fi
}

# ---------------------------------------------------------------------------
# (e) Environments
# ---------------------------------------------------------------------------

# env_desired_body <name> <outfile>
env_desired_body() {
    local name="$1" out="$2"
    if [ "$name" = "production" ]; then
        local reviewers login
        reviewers=""
        for login in $PROD_REVIEWERS; do
            reviewers="${reviewers}${reviewers:+,}{\"type\":\"User\",\"id\":$(login_id "$login")}"
        done
        cat >"$out" <<JSON
{
  "wait_timer": 0,
  "prevent_self_review": true,
  "reviewers": [$reviewers],
  "deployment_branch_policy": { "protected_branches": false, "custom_branch_policies": true }
}
JSON
    else
        cat >"$out" <<'JSON'
{
  "wait_timer": 0,
  "reviewers": null,
  "deployment_branch_policy": { "protected_branches": false, "custom_branch_policies": true }
}
JSON
    fi
}

step_environments() {
    section "(e) Environments build / staging / production (ADR-007)"

    local name body cur exists dbp_ok want_rev got_rev psr
    for name in $ENVIRONMENTS; do
        body="$TMPDIR_GOV/env-$name.json"
        env_desired_body "$name" "$body"

        exists=1
        cur="$(api_get "repos/$REPO/environments/$name" 2>/dev/null)" || exists=0

        if [ "$exists" -eq 1 ]; then
            dbp_ok="$(printf '%s' "$cur" | jq -r \
                '(.deployment_branch_policy.protected_branches == false)
                 and (.deployment_branch_policy.custom_branch_policies == true)')"
            got_rev="$(printf '%s' "$cur" | jq -r \
                '[.protection_rules[]? | select(.type == "required_reviewers")
                  | .reviewers[]? | .reviewer.id] | sort | @csv')"
            psr="$(printf '%s' "$cur" | jq -r \
                '[.protection_rules[]? | select(.type == "required_reviewers")
                  | .prevent_self_review][0] // false')"
            want_rev="$(jq -r '[.reviewers[]? | .id] | sort | @csv' "$body")"

            if [ "$dbp_ok" = "true" ] && [ "$got_rev" = "$want_rev" ] &&
                { [ "$name" != "production" ] || [ "$psr" = "true" ]; }; then
                already "environment $name (branch policy custom, reviewers [$got_rev], prevent_self_review=$psr)"
            else
                mutate "environment $name: reviewers [$got_rev]->[$want_rev], custom branch policy, prevent_self_review" \
                    --method PUT "repos/$REPO/environments/$name" --input "$body"
            fi
        else
            want_rev="$(jq -r '[.reviewers[]? | .id] | sort | @csv' "$body")"
            mutate "environment $name created (custom branch policy, reviewers [$want_rev])" \
                --method PUT "repos/$REPO/environments/$name" --input "$body"
        fi

        step_branch_policies "$name"
    done

    if [ "$DRY_RUN" -eq 0 ]; then
        info "verified environments:"
        api_get "repos/$REPO/environments" --jq \
            '.environments[] | "           \(.name): branch_policy=\(.deployment_branch_policy // "none"), reviewers=\([.protection_rules[]?|select(.type=="required_reviewers")|.reviewers[]?|.reviewer.login]|join(",")), prevent_self_review=\([.protection_rules[]?|select(.type=="required_reviewers")|.prevent_self_review][0] // false)"' ||
            warn "could not read environments back"
    fi
}

# step_branch_policies <env> — ensure branch 'main' and tag 'v*' policies exist.
step_branch_policies() {
    local name="$1" listed have body
    if ! listed="$(api_get "repos/$REPO/environments/$name/deployment-branch-policies" 2>/dev/null)"; then
        if [ "$DRY_RUN" -eq 1 ]; then
            would "environment $name deployment branch policies: main (branch), v* (tag)"
        else
            manual "environment $name: cannot list deployment branch policies"
        fi
        return 0
    fi

    local spec pname ptype
    for spec in "main:branch" "v*:tag"; do
        pname="${spec%%:*}"
        ptype="${spec##*:}"
        have="$(printf '%s' "$listed" | jq -r --arg n "$pname" --arg t "$ptype" \
            '[.branch_policies[]? | select(.name == $n and ((.type // "branch") == $t))] | length')"
        if [ "${have:-0}" -gt 0 ]; then
            already "environment $name deployment branch policy $ptype '$pname'"
        else
            body="$TMPDIR_GOV/dbp-$name-$ptype.json"
            printf '{"name":"%s","type":"%s"}\n' "$pname" "$ptype" >"$body"
            mutate "environment $name deployment branch policy $ptype '$pname'" \
                --method POST "repos/$REPO/environments/$name/deployment-branch-policies" \
                --input "$body"
        fi
    done

    if [ "$DRY_RUN" -eq 0 ]; then
        info "  $name policies: $(api_get "repos/$REPO/environments/$name/deployment-branch-policies" \
            --jq '[.branch_policies[]? | "\(.type // "branch"):\(.name)"] | join(" ")' 2>/dev/null || echo '?')"
    fi
}

# ---------------------------------------------------------------------------
# (f) Ruleset on the default branch
# ---------------------------------------------------------------------------

ruleset_body() {
    local out="$1" enforcement="$2" full="$3"
    local pr_extra='"allowed_merge_methods": ["merge", "squash"],'
    local rsc_extra='"do_not_enforce_on_create": false,'
    if [ "$full" != "full" ]; then
        pr_extra=""
        rsc_extra=""
    fi
    cat >"$out" <<JSON
{
  "name": "$RULESET_NAME",
  "target": "branch",
  "enforcement": "$enforcement",
  "bypass_actors": [
    { "actor_id": 1, "actor_type": "OrganizationAdmin", "bypass_mode": "always" }
  ],
  "conditions": {
    "ref_name": { "include": ["~DEFAULT_BRANCH"], "exclude": [] }
  },
  "rules": [
    { "type": "deletion" },
    { "type": "non_fast_forward" },
    {
      "type": "pull_request",
      "parameters": {
        "required_approving_review_count": 1,
        "dismiss_stale_reviews_on_push": true,
        "require_code_owner_review": true,
        "require_last_push_approval": true,
        "required_review_thread_resolution": false,
        $pr_extra
        "__placeholder_removed": false
      }
    },
    {
      "type": "required_status_checks",
      "parameters": {
        "strict_required_status_checks_policy": true,
        $rsc_extra
        "required_status_checks": [
          { "context": "lint", "integration_id": $ACTIONS_APP_ID },
          { "context": "build", "integration_id": $ACTIONS_APP_ID }
        ]
      }
    }
  ]
}
JSON
    # Drop the placeholder that keeps the JSON valid whether or not the
    # optional pull_request field is present.
    jq 'del(.rules[].parameters.__placeholder_removed)' "$out" >"$out.tmp" &&
        mv "$out.tmp" "$out"
}

# Normalise a ruleset (ours or GitHub's) so the two can be compared: keep only
# the fields we manage, and sort every array whose order is not meaningful.
RULESET_NORM_JQ='
def norm:
  {
    name: .name,
    target: .target,
    enforcement: .enforcement,
    bypass_actors: ([ .bypass_actors[]?
                      # GitHub echoes actor_id: null for OrganizationAdmin even
                      # though the API requires actor_id: 1 on the way in.
                      | { actor_id: (if .actor_type == "OrganizationAdmin"
                                     then null else .actor_id end),
                          actor_type, bypass_mode } ]
                    | sort_by([(.actor_type|tostring), (.actor_id|tostring)])),
    conditions: { ref_name: {
      include: ((.conditions.ref_name.include // []) | sort),
      exclude: ((.conditions.ref_name.exclude // []) | sort) } },
    rules: ([ .rules[]? | { type: .type, parameters: ((.parameters // {})
              | if has("required_status_checks")
                then .required_status_checks |= sort_by(.context) else . end
              | if has("allowed_merge_methods")
                then .allowed_merge_methods |= sort else . end) } ]
            | sort_by(.type))
  };
norm'

# Deep "want is contained in got": GitHub echoes back extra defaults we do not
# manage, so equality would report a change on every run.
# $want/$got below are jq variables, not shell ones — hence the single quotes.
# shellcheck disable=SC2016
RULESET_SUBSET_JQ='
def sub($want; $got):
  if ($want | type) == "object" then
    ($got | type) == "object"
    and ([ $want | keys_unsorted[] ] | all(. as $k | sub($want[$k]; $got[$k])))
  elif ($want | type) == "array" then
    ($got | type) == "array"
    and ($want | length) == ($got | length)
    and ([ range(0; $want | length) ] | all(. as $i | sub($want[$i]; $got[$i])))
  else $want == $got end;
sub(.want; .got)'

step_ruleset() {
    section "(f) Ruleset '$RULESET_NAME' on ~DEFAULT_BRANCH (ADR-002, ADR-009)"

    case "$RULESET_ENFORCEMENT" in
    evaluate | disabled) ;;
    active)
        die "RULESET_ENFORCEMENT=active is not accepted here; use --activate with CONFIRM_ACTIVATE=yes"
        ;;
    *) die "RULESET_ENFORCEMENT must be evaluate or disabled (got '$RULESET_ENFORCEMENT')" ;;
    esac

    local id current_enf desired_enf
    id="$(api_get "repos/$REPO/rulesets" \
        --jq "[.[] | select(.name == \"$RULESET_NAME\") | .id][0] // empty")"

    desired_enf="$RULESET_ENFORCEMENT"
    if [ -n "$id" ]; then
        current_enf="$(api_get "repos/$REPO/rulesets/$id" --jq .enforcement)"
        if [ "$current_enf" = "active" ] && [ "$desired_enf" != "active" ]; then
            # Someone has already activated it deliberately. Do not undo that
            # just because this script's default is evaluate.
            desired_enf="active"
            note "ruleset is already enforcement=active; keeping it active rather than downgrading to $RULESET_ENFORCEMENT (set RULESET_ENFORCEMENT=disabled and edit this guard if you really want to relax it)"
        fi
    fi

    local body="$TMPDIR_GOV/ruleset.json"
    ruleset_body "$body" "$desired_enf" full

    if [ -n "$id" ]; then
        local got want cmp
        got="$TMPDIR_GOV/ruleset-got.json"
        want="$TMPDIR_GOV/ruleset-want.json"
        api_get "repos/$REPO/rulesets/$id" | jq "$RULESET_NORM_JQ" >"$got"
        jq "$RULESET_NORM_JQ" "$body" >"$want"
        cmp="$(jq -n --slurpfile w "$want" --slurpfile g "$got" \
            '{want: $w[0], got: $g[0]}' | jq "$RULESET_SUBSET_JQ")"
        if [ "$cmp" = "true" ]; then
            already "ruleset $RULESET_NAME (id $id) enforcement=$desired_enf, 4 rules, bypass=OrganizationAdmin"
            ruleset_report "$id"
            return 0
        fi
        apply_ruleset "$body" "PUT" "repos/$REPO/rulesets/$id" \
            "ruleset $RULESET_NAME (id $id) updated, enforcement=$desired_enf"
    else
        apply_ruleset "$body" "POST" "repos/$REPO/rulesets" \
            "ruleset $RULESET_NAME created, enforcement=$desired_enf, bypass=OrganizationAdmin"
    fi

    [ "$DRY_RUN" -eq 1 ] && return 0
    id="$(api_get "repos/$REPO/rulesets" \
        --jq "[.[] | select(.name == \"$RULESET_NAME\") | .id][0] // empty")"
    if [ -n "$id" ]; then
        ruleset_report "$id"
    else
        warn "ruleset $RULESET_NAME still does not exist after the call above"
    fi
}

# apply_ruleset <body> <method> <path> <description>
# Retries once without the optional fields some API versions reject.
apply_ruleset() {
    local body="$1" method="$2" path="$3" desc="$4"
    if [ "$DRY_RUN" -eq 1 ]; then
        would "$desc"
        info "  body: $(jq -c . "$body")"
        return 0
    fi
    local out
    if out="$(gh api --method "$method" "$path" --input "$body" 2>&1)"; then
        changed "$desc"
        return 0
    fi
    warn "ruleset $method rejected: $(printf '%s' "$out" | tr '\n' ' ' | head -c 300)"
    local reduced="$TMPDIR_GOV/ruleset-reduced.json"
    ruleset_body "$reduced" "$(jq -r .enforcement "$body")" minimal
    note "retrying the ruleset without the optional fields allowed_merge_methods and do_not_enforce_on_create"
    if out="$(gh api --method "$method" "$path" --input "$reduced" 2>&1)"; then
        changed "$desc (without allowed_merge_methods / do_not_enforce_on_create)"
        note "allowed_merge_methods and do_not_enforce_on_create were NOT set — check them in the UI (Settings -> Rules)"
        return 0
    fi
    manual "$desc — both attempts refused: $(printf '%s' "$out" | tr '\n' ' ' | head -c 300)"
    return 0
}

ruleset_report() {
    local id="$1"
    info "ruleset id=$id enforcement=$(api_get "repos/$REPO/rulesets/$id" --jq .enforcement)"
    api_get "repos/$REPO/rulesets/$id" --jq \
        '"           rules: " + ([.rules[].type] | join(", "))
         + "\n           bypass: " + ([.bypass_actors[]? | .actor_type + "/" + (.actor_id|tostring) + "/" + .bypass_mode] | join(", "))
         + "\n           refs: " + ((.conditions.ref_name.include // []) | join(", "))'
}

# ---------------------------------------------------------------------------
# (g) Summary
# ---------------------------------------------------------------------------
step_summary() {
    section "(g) Summary"
    if [ "$DRY_RUN" -eq 1 ]; then
        info "--check: $WOULD_COUNT change(s) would be made, nothing was sent"
    else
        info "$CHANGED_COUNT change(s) applied"
    fi

    local item
    if [ "${#NOTE_ITEMS[@]}" -gt 0 ]; then
        info "notes:"
        for item in "${NOTE_ITEMS[@]}"; do printf '         - %s\n' "$item"; done
    fi
    if [ "${#WARN_ITEMS[@]}" -gt 0 ]; then
        info "warnings:"
        for item in "${WARN_ITEMS[@]}"; do printf '         - %s\n' "$item"; done
    fi
    if [ "${#MANUAL_ITEMS[@]}" -gt 0 ]; then
        info "NEEDS A HUMAN (record these in docs/runbooks/10-repo-governance.md):"
        for item in "${MANUAL_ITEMS[@]}"; do printf '         - %s\n' "$item"; done
    else
        info "nothing needs a human"
    fi

    if [ "$ACTIVATE" -eq 0 ]; then
        info "the ruleset is intentionally NOT enforcing yet. When the vendor is"
        info "briefed, BREAKGLASS.md is merged and two architects can review each"
        info "other's PRs, enforce it with:"
        info "    CONFIRM_ACTIVATE=yes scripts/github/apply-governance.sh --activate"
    fi
}

# ---------------------------------------------------------------------------
main() {
    info "repo=$REPO dry_run=$DRY_RUN ruleset=$RULESET_NAME enforcement=$RULESET_ENFORCEMENT"
    if [ "$ACTIVATE" -eq 1 ]; then
        do_activate
        step_summary
        return 0
    fi
    step_audit
    step_vendor_role
    step_workflow_permissions
    step_secret_scanning
    step_environments
    step_ruleset
    step_summary
}

main "$@"
