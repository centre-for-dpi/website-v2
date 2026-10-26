#!/bin/bash
# Self-test for cdpi-deploy-theme: runs without network, root or production.
#
#   ./deploy/host/test-cdpi-deploy-theme.sh
#
# Builds a throw-away bare "origin" repository with three commits on main
# (c1, c2, c3; v1.0.0 on c3) and one commit on a feature branch (f1), clones
# it as THEME_DIR sitting at c1 with an untracked junk file, and points the
# script's smoke check at stand-in executables (CDPI_SMOKE_CMD) that answer
# from THEME_DIR/.cdpi-build instead of a web server. Then exercises the
# parser, the dry run, deploy, the refusals, the automatic revert, rollback,
# status and the cron-driven `sync` against a moving `production` tag.
# Exit 0 = every case behaved as specified.
set -uo pipefail

SCRIPT=${1:-"$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/cdpi-deploy-theme"}
SHA40=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

tmp=$(mktemp -d "${TMPDIR:-/tmp}/cdpi-deploy-theme-test.XXXXXX")
trap 'rm -rf "$tmp"' EXIT

pass=0
fail=0
ok() { pass=$((pass + 1)); printf 'ok    %s\n' "$1"; }
bad() { fail=$((fail + 1)); printf 'FAIL  %s\n' "$1"; }

# assert_eq <label> <want> <got>
assert_eq() {
	if [[ $2 == "$3" ]]; then ok "$1"; else bad "$1: want '$2', got '$3'"; fi
}
# assert_contains <label> <needle> <haystack>
assert_contains() {
	if [[ $3 == *"$2"* ]]; then ok "$1"; else bad "$1: output lacks '$2'"; fi
}

# ------------------------------------------------------------- fixtures --

export HOME=$tmp/home
mkdir -p "$HOME"
export GIT_AUTHOR_NAME=test GIT_AUTHOR_EMAIL=test@example.invalid
export GIT_COMMITTER_NAME=test GIT_COMMITTER_EMAIL=test@example.invalid

origin=$tmp/origin.git
git init -q --bare "$origin"
git --git-dir="$origin" symbolic-ref HEAD refs/heads/main

author=$tmp/author
git clone -q "$origin" "$author" 2>/dev/null
a() { git -C "$author" "$@"; }
a checkout -q -b main
commit() { # commit <message> -> hash
	printf '<?php // %s\n' "$1" >"$author/functions.php"
	printf '<html><!-- %s --></html>\n' "$1" >"$author/index.php"
	a add -A && a commit -q -m "$1" && a rev-parse HEAD
}
c1=$(commit c1)
c2=$(commit c2)
c3=$(commit c3)
a tag v1.0.0 "$c3"
a checkout -q -b feature "$c2"
f1=$(commit f1)
a push -q origin main feature v1.0.0 2>/dev/null

# tag_production <commit> [annotated]: what the Release workflow does. Its
# message carries the run id, so each dispatch is a distinct tag object even
# for the same commit; a nanosecond timestamp stands in for the run id here.
tag_production() {
	if [[ ${2:-} == annotated ]]; then
		a tag -f -a -m "release $1 run $(date +%s%N)" production "$1" >/dev/null
	else
		a tag -f production "$1" >/dev/null
	fi
	a push -q -f origin refs/tags/production 2>/dev/null
	git --git-dir="$origin" rev-parse refs/tags/production
}

theme=$tmp/theme
git clone -q "$origin" "$theme" 2>/dev/null
git -C "$theme" checkout -q main
git -C "$theme" reset -q --hard "$c1"
echo junk >"$theme/old-backup.tar.gz" # untracked; must survive every deploy
t() { git -C "$theme" "$@"; }

state=$tmp/state

# Smoke stand-ins: `<cmd> <url>` prints "<code>\n<body>".
mkdir -p "$tmp/bin"
cat >"$tmp/bin/smoke-ok" <<EOF
#!/bin/bash
h=\$(cat "$theme/.cdpi-build" 2>/dev/null || echo unknown)
printf '200\n<html><head><meta name="cdpi-build" content="%s"></head></html>\n' "\$h"
EOF
# Serves the marker except for the commit named in $tmp/smoke-bad, which gets 500.
cat >"$tmp/bin/smoke-flaky" <<EOF
#!/bin/bash
h=\$(cat "$theme/.cdpi-build" 2>/dev/null || echo unknown)
if [[ \$h == "\$(cat "$tmp/smoke-bad")" ]]; then printf '500\n<html>error</html>\n'; exit 0; fi
printf '200\n<html><head><meta name="cdpi-build" content="%s"></head></html>\n' "\$h"
EOF
# A site whose functions.php predates the .cdpi-build fallback.
cat >"$tmp/bin/smoke-legacy" <<'EOF'
#!/bin/bash
printf '200\n<html><head><meta name="cdpi-build" content="unknown"></head></html>\n'
EOF
cat >"$tmp/bin/smoke-500" <<'EOF'
#!/bin/bash
printf '500\n<html>error</html>\n'
EOF
chmod 0755 "$tmp/bin"/smoke-*

# run <args...>: the script as the operator or cron would call it;
# stdout+stderr in $out, exit code in $rc.
run() {
	out=$(THEME_DIR="$theme" STATE_DIR="$state" SITE_URL=https://example.invalid \
		SMOKE_ATTEMPTS=2 SMOKE_DELAY=0 CRON_LOG_MAX_BYTES="${CRON_MAX:-1048576}" \
		CDPI_SMOKE_CMD="${SMOKE:-$tmp/bin/smoke-ok}" \
		bash "$SCRIPT" "$@" 2>&1)
	rc=$?
}
head_of() { t rev-parse HEAD; }
state_of() { cat "$state/$1" 2>/dev/null || echo none; }
build_of() { cat "$theme/.cdpi-build" 2>/dev/null || echo none; }

echo "script: $SCRIPT"
echo "c1=$c1 c2=$c2 c3=$c3 (main, v1.0.0=c3) f1=$f1 (feature)"
echo

# -------------------------------------------------------------- parser --

echo "# parser"
reject() { # reject <label> <args...>
	local label=$1
	shift
	run "$@"
	if [[ $rc -eq 126 ]]; then ok "reject: $label"; else bad "reject: $label: rc=$rc ($out)"; fi
}
reject 'no arguments'
reject 'empty argument' ''
reject 'metachars: ; rm -rf /' 'deploy sha-x; rm -rf /'
reject 'status; ls' 'status; ls'
reject 'pipe' 'status | tee /tmp/x'
# shellcheck disable=SC2016  # the literal text is the point of these cases
reject 'command substitution' deploy '$(id)'
# shellcheck disable=SC2016
reject 'backticks' deploy '`id`'
reject 'newline smuggling' "$(printf 'status\nls')"
reject 'tab separator' "$(printf 'deploy\tsha-%s' "$SHA40")"
reject 'wrong case (Deploy)' Deploy "sha-$SHA40"
reject 'extra word' deploy "sha-$SHA40" extra
reject 'deploy without a target' deploy
reject 'rollback with a target' rollback "sha-$SHA40"
reject 'status with a word' status now
reject 'sync with a word' sync now
reject 'short sha (39 hex)' deploy "sha-${SHA40:1}"
reject 'long sha (41 hex)' deploy "sha-${SHA40}a"
reject 'uppercase sha' deploy "sha-${SHA40^^}"
reject 'v tag without patch' deploy v1.2
reject 'v tag with suffix' deploy v1.2.3-rc1
reject 'branch name as target' deploy main
reject 'tag name as target' deploy production
reject 'path traversal' deploy ../../etc/passwd
reject 'glob' deploy '*'
reject 'unknown verb' restart
reject 'ls /' ls /
reject 'env prefix' FOO=bar deploy "sha-$SHA40"
reject '--dry-run alone' --dry-run
reject '--dry-run not first' deploy --dry-run "sha-$SHA40"
reject '--dry-run twice' --dry-run --dry-run status
reject 'other option' --force deploy "sha-$SHA40"

# ---------------------------------------------------------- precondition --

echo "# preconditions"
THEME_DIR_SAVE=$theme
theme=$tmp/not-a-repo
mkdir -p "$theme"
run deploy "sha-$c2"
assert_eq 'not a git checkout: refused (rc 2)' 2 "$rc"
run sync
assert_eq 'not a git checkout: sync refused (rc 2)' 2 "$rc"
theme=$THEME_DIR_SAVE

run status
assert_eq 'status before any deploy: rc 0' 0 "$rc"
assert_contains 'status shows HEAD on main' "head:            $c1 (main)" "$out"
assert_contains 'status shows dirty_tracked 0' 'dirty_tracked:   0' "$out"
assert_contains 'status shows paused no' 'paused:          no' "$out"

# ------------------------------------------------------------- dry run --

echo "# dry run"
run --dry-run deploy "sha-$c2"
assert_eq '"--dry-run deploy sha-c2": rc 0' 0 "$rc"
assert_contains 'dry run says what it would do' "would git checkout --detach $c2" "$out"
assert_contains 'dry run ends with the OK line' '=== DRY RUN OK: nothing changed ===' "$out"
assert_eq 'dry run: HEAD unchanged' "$c1" "$(head_of)"
assert_eq 'dry run: no .cdpi-build' none "$(build_of)"
assert_eq 'dry run: no previous' none "$(state_of previous)"
assert_eq 'dry run: no current' none "$(state_of current)"

out=$(CDPI_DRY_RUN=1 THEME_DIR="$theme" STATE_DIR="$state" SITE_URL=https://example.invalid \
	CDPI_SMOKE_CMD="$tmp/bin/smoke-ok" bash "$SCRIPT" deploy "sha-$c2" 2>&1)
rc=$?
assert_eq 'CDPI_DRY_RUN=1 env form: rc 0' 0 "$rc"
assert_contains 'CDPI_DRY_RUN=1 env form: dry' '=== DRY RUN OK' "$out"
assert_eq 'env dry run: HEAD unchanged' "$c1" "$(head_of)"

run --dry-run deploy "sha-$f1"
assert_eq 'dry run of a commit off main: refused (rc 2)' 2 "$rc"
assert_contains 'dry run refusal names the reason' 'not reachable from origin/main' "$out"

run --dry-run rollback
assert_eq 'dry run rollback with nothing recorded: refused (rc 2)' 2 "$rc"

run --dry-run sync
assert_eq 'dry run sync with no production tag: rc 0' 0 "$rc"
assert_contains 'dry run sync explains' 'nothing to do: origin has no tag production' "$out"

# -------------------------------------------------------------- deploy --

echo "# deploy"
run deploy "sha-$c2"
assert_eq 'deploy sha-c2: rc 0' 0 "$rc"
assert_contains 'deploy prints DEPLOY OK' '=== DEPLOY OK ===' "$out"
assert_eq 'deploy: HEAD = c2' "$c2" "$(head_of)"
assert_eq 'deploy: HEAD detached' detached "$(t symbolic-ref -q --short HEAD || echo detached)"
assert_eq 'deploy: .cdpi-build = c2' "$c2" "$(build_of)"
assert_eq 'deploy: previous = c1' "$c1" "$(state_of previous)"
assert_eq 'deploy: current = c2' "$c2" "$(state_of current)"
assert_eq 'deploy: tracked tree clean afterwards' '' "$(t status --porcelain --untracked-files=no)"
assert_eq 'deploy: untracked junk file untouched' junk "$(cat "$theme/old-backup.tar.gz")"
assert_eq 'deploy: log file written' 0 "$(grep -q 'DEPLOY OK' "$state/deploy.log"; echo $?)"
assert_eq 'deploy: .cdpi-build mode 0644' 644 "$(stat -c %a "$theme/.cdpi-build")"

run deploy "$c2"
assert_eq 'deploy bare 40hex of the current commit: rc 0' 0 "$rc"
assert_contains 'redeploy of HEAD says so' 'already at the target' "$out"
assert_eq 'redeploy: previous still c1' "$c1" "$(state_of previous)"

run deploy v1.0.0
assert_eq 'deploy v1.0.0 (= c3): rc 0' 0 "$rc"
assert_eq 'v tag: HEAD = c3' "$c3" "$(head_of)"
assert_eq 'v tag: previous = c2' "$c2" "$(state_of previous)"
assert_eq 'v tag: current = c3' "$c3" "$(state_of current)"

run deploy v9.9.9
assert_eq 'unknown tag: refused (rc 2)' 2 "$rc"
assert_eq 'unknown tag: HEAD unchanged' "$c3" "$(head_of)"

run deploy "sha-$SHA40"
assert_eq 'unknown commit: refused (rc 2)' 2 "$rc"
assert_contains 'unknown commit: reason' 'is not a commit this checkout knows' "$out"
assert_eq 'unknown commit: HEAD unchanged' "$c3" "$(head_of)"

# ------------------------------------------------------------- refusals --

echo "# refusals"
run deploy "sha-$f1"
assert_eq 'commit not on main: refused (rc 2)' 2 "$rc"
assert_contains 'commit not on main: reason' 'not reachable from origin/main' "$out"
assert_eq 'commit not on main: HEAD unchanged' "$c3" "$(head_of)"
assert_eq 'commit not on main: previous unchanged' "$c2" "$(state_of previous)"

echo '<?php // edited on the server' >"$theme/functions.php"
run deploy "sha-$c2"
assert_eq 'dirty tracked tree: refused (rc 2)' 2 "$rc"
assert_contains 'dirty tracked tree: reason' 'tracked files modified on the server' "$out"
assert_contains 'dirty tracked tree: names the file' ' M functions.php' "$out"
assert_eq 'dirty tracked tree: HEAD unchanged' "$c3" "$(head_of)"
run status
assert_contains 'status counts the dirty file' 'dirty_tracked:   1' "$out"
t checkout -q -- functions.php

exec 8>>"$state/lock"
flock -n 8
run deploy "sha-$c2"
assert_eq 'lock held elsewhere: deploy refused (rc 2)' 2 "$rc"
assert_contains 'lock held elsewhere: reason' 'another deploy is running' "$out"
exec 8>&-

# ------------------------------------------------------ failed smoke --

echo "# failed smoke reverts"
echo "$c2" >"$tmp/smoke-bad"
SMOKE=$tmp/bin/smoke-flaky run deploy "sha-$c2"
assert_eq 'smoke fails on the new commit: rc 1' 1 "$rc"
assert_contains 'revert line' "=== DEPLOY FAILED, REVERTED TO $c3 ===" "$out"
assert_eq 'reverted: HEAD = c3' "$c3" "$(head_of)"
assert_eq 'reverted: .cdpi-build = c3' "$c3" "$(build_of)"
assert_eq 'reverted: previous restored to c2 (not the failed commit)' "$c2" "$(state_of previous)"
assert_eq 'reverted: current still c3' "$c3" "$(state_of current)"
assert_eq 'reverted: junk file untouched' junk "$(cat "$theme/old-backup.tar.gz")"

SMOKE=$tmp/bin/smoke-legacy run deploy "sha-$c2"
assert_eq 'legacy site (no marker) after revert: rc 1' 1 "$rc"
assert_contains 'legacy site: revert accepted on 200 without marker' 'predates the .cdpi-build marker' "$out"
assert_contains 'legacy site: revert line' "=== DEPLOY FAILED, REVERTED TO $c3 ===" "$out"
assert_eq 'legacy site: HEAD = c3' "$c3" "$(head_of)"

# ------------------------------------------------------------- rollback --

echo "# rollback"
run --dry-run rollback
assert_eq 'dry run rollback: rc 0' 0 "$rc"
assert_contains 'dry run rollback targets previous' "would git checkout --detach $c2" "$out"
assert_eq 'dry run rollback: HEAD unchanged' "$c3" "$(head_of)"

run rollback
assert_eq 'rollback: rc 0' 0 "$rc"
assert_contains 'rollback prints DEPLOY OK' '=== DEPLOY OK ===' "$out"
assert_eq 'rollback: HEAD = c2' "$c2" "$(head_of)"
assert_eq 'rollback: .cdpi-build = c2' "$c2" "$(build_of)"
assert_eq 'rollback: previous = c3' "$c3" "$(state_of previous)"
assert_eq 'rollback: current = c2' "$c2" "$(state_of current)"

# --------------------------------------------------------------- status --

echo "# status"
run status
assert_eq 'status: rc 0' 0 "$rc"
assert_contains 'status: head' "head:            $c2 (detached)" "$out"
assert_contains 'status: origin/main' "origin/main:     $c3" "$out"
assert_contains 'status: previous' "previous:        $c3" "$out"
assert_contains 'status: current' "current:         $c2" "$out"
assert_contains 'status: build_file' "build_file:      $c2" "$out"
assert_contains 'status: last log line' '=== DEPLOY OK ===' "$out"

# --------------------------------------------------- revert also failed --

echo "# revert also failed"
SMOKE=$tmp/bin/smoke-500 run deploy "sha-$c3"
assert_eq 'site down: rc 1' 1 "$rc"
assert_contains 'site down: revert also failed line' "=== DEPLOY FAILED, REVERT ALSO FAILED (wanted $c2) ===" "$out"
assert_eq 'site down: files are back on c2 anyway' "$c2" "$(head_of)"
assert_eq 'site down: .cdpi-build = c2' "$c2" "$(build_of)"
assert_eq 'site down: previous = c2, the last known-good, so rollback retries it' "$c2" "$(state_of previous)"
assert_eq 'site down: current still c2' "$c2" "$(state_of current)"

run rollback
assert_eq 'rollback after a failed revert re-verifies the last known-good: rc 0' 0 "$rc"
assert_eq 'rollback after a failed revert: HEAD = c2' "$c2" "$(head_of)"
assert_eq 'rollback after a failed revert: previous untouched' "$c2" "$(state_of previous)"

# ----------------------------------------------------------------- sync --

echo "# sync (cron)"
run sync
assert_eq 'sync, no production tag: rc 0' 0 "$rc"
assert_eq 'sync, no production tag: silent' '' "$out"
assert_eq 'sync, no production tag: HEAD unchanged' "$c2" "$(head_of)"

tag_id=$(tag_production "$c3")
run --dry-run sync
assert_eq 'dry run sync, tag moved to c3: rc 0' 0 "$rc"
assert_contains 'dry run sync names the tag and commit' "tag production ($tag_id) -> commit $c3" "$out"
assert_contains 'dry run sync ends dry' '=== DRY RUN OK: nothing changed ===' "$out"
assert_eq 'dry run sync: HEAD unchanged' "$c2" "$(head_of)"

run sync
assert_eq 'sync, tag moved to c3: rc 0' 0 "$rc"
assert_contains 'sync logs that it is deploying' "tag production ($tag_id) -> commit $c3, HEAD is $c2: deploying" "$out"
assert_contains 'sync ran the deploy' '=== DEPLOY OK ===' "$out"
assert_contains 'sync reports the result' "sync: production is now at $c3" "$out"
assert_eq 'sync: HEAD = c3' "$c3" "$(head_of)"
assert_eq 'sync: .cdpi-build = c3' "$c3" "$(build_of)"
assert_eq 'sync: previous = c2' "$c2" "$(state_of previous)"
assert_eq 'sync: current = c3' "$c3" "$(state_of current)"
assert_eq 'sync: junk file untouched' junk "$(cat "$theme/old-backup.tar.gz")"

run sync
assert_eq 'sync, tag unchanged: rc 0' 0 "$rc"
assert_eq 'sync, tag unchanged: silent' '' "$out"
run --dry-run sync
assert_contains 'dry run sync, tag unchanged: explains' "tag production ($c3) is already checked out" "$out"
run status
assert_contains 'status shows the local production tag' "tag production:  $c3" "$out"

tag_id=$(tag_production "$f1")
run sync
assert_eq 'sync, tag off main: refused (rc 2)' 2 "$rc"
assert_contains 'sync, tag off main: reason logged' 'not reachable from origin/main' "$out"
assert_contains 'sync, tag off main: not retried' 'not retried until tag production moves' "$out"
assert_eq 'sync, tag off main: HEAD unchanged' "$c3" "$(head_of)"
assert_eq 'sync, tag off main: failed-tag recorded' "$tag_id" "$(state_of failed-tag)"
run sync
assert_eq 'sync again, same bad tag: rc 0' 0 "$rc"
assert_eq 'sync again, same bad tag: silent (no retry every minute)' '' "$out"
run --dry-run sync
assert_contains 'dry run sync explains the skipped tag' "tag object $tag_id already failed" "$out"

echo "$c2" >"$tmp/smoke-bad"
tag_id=$(tag_production "$c2" annotated)
assert_eq 'annotated tag object differs from its commit' 1 "$([[ $tag_id != "$c2" ]] && echo 1 || echo 0)"
SMOKE=$tmp/bin/smoke-flaky run sync
assert_eq 'sync, failed smoke: rc 1' 1 "$rc"
assert_contains 'sync, failed smoke: reverted' "=== DEPLOY FAILED, REVERTED TO $c3 ===" "$out"
assert_eq 'sync, failed smoke: HEAD = c3' "$c3" "$(head_of)"
assert_eq 'sync, failed smoke: failed-tag = the tag object' "$tag_id" "$(state_of failed-tag)"
SMOKE=$tmp/bin/smoke-flaky run sync
assert_eq 'sync, failed smoke, again: rc 0' 0 "$rc"
assert_eq 'sync, failed smoke, again: silent, no second attempt' '' "$out"
assert_eq 'sync, failed smoke, again: HEAD still c3' "$c3" "$(head_of)"

tag_id2=$(tag_production "$c2" annotated)
assert_eq 're-dispatch of the same commit is a new tag object' 1 "$([[ $tag_id2 != "$tag_id" ]] && echo 1 || echo 0)"
run sync
assert_eq 'sync, re-dispatched same commit with the site fixed: rc 0' 0 "$rc"
assert_eq 'sync, re-dispatch: HEAD = c2' "$c2" "$(head_of)"
assert_eq 'sync, re-dispatch: failed-tag cleared' none "$(state_of failed-tag)"
assert_eq 'sync, re-dispatch: previous = c3' "$c3" "$(state_of previous)"

touch "$state/paused"
tag_production "$c1" >/dev/null
run sync
assert_eq 'sync while paused: rc 0' 0 "$rc"
assert_eq 'sync while paused: silent' '' "$out"
assert_eq 'sync while paused: HEAD unchanged' "$c2" "$(head_of)"
run status
assert_contains 'status shows paused yes' 'paused:          yes' "$out"
rm -f "$state/paused"

exec 8>>"$state/lock"
flock -n 8
run sync
assert_eq 'sync while the lock is held: rc 0' 0 "$rc"
assert_eq 'sync while the lock is held: silent' '' "$out"
assert_eq 'sync while the lock is held: HEAD unchanged' "$c2" "$(head_of)"
exec 8>&-

run sync
assert_eq 'sync after unpausing: rc 0' 0 "$rc"
assert_eq 'sync after unpausing: HEAD = c1' "$c1" "$(head_of)"

echo '<?php // edited on the server' >"$theme/functions.php"
tag_id=$(tag_production "$c3")
run sync
assert_eq 'sync with a dirty tree: refused (rc 2)' 2 "$rc"
assert_contains 'sync with a dirty tree: reason logged' 'tracked files modified on the server' "$out"
assert_eq 'sync with a dirty tree: HEAD unchanged' "$c1" "$(head_of)"
assert_eq 'sync with a dirty tree: failed-tag recorded' "$tag_id" "$(state_of failed-tag)"
t checkout -q -- functions.php
run deploy "sha-$c3"
assert_eq 'interactive deploy after fixing the tree: rc 0' 0 "$rc"
assert_eq 'interactive deploy clears failed-tag' none "$(state_of failed-tag)"
run sync
assert_eq 'sync afterwards: silent, tag already checked out' '' "$out"

head -c 300 /dev/zero | tr '\0' x >"$state/cron.log"
CRON_MAX=100 run sync
assert_eq 'sync with an oversized cron.log: rc 0' 0 "$rc"
assert_contains 'sync truncates cron.log and says so' 'cron.log was 300 bytes; truncated' "$out"
assert_eq 'cron.log is empty afterwards' 0 "$(stat -c %s "$state/cron.log")"

echo
printf '%d passed, %d failed\n' "$pass" "$fail"
[[ $fail -eq 0 ]]
