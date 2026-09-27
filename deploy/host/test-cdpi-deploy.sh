#!/bin/bash
# Parser self-test for the cdpi-deploy ForceCommand wrapper.
#
# Runs without root and without sudo: CDPI_DEPLOY_DRY=1 makes the wrapper
# print the command it would hand to sudo instead of executing it.
#
#   ./deploy/host/test-cdpi-deploy.sh
#
# Exit 0 = every case behaved as specified.
set -uo pipefail

WRAPPER=${1:-"$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)/cdpi-deploy"}
SHA40=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

pass=0
fail=0

# check <expect: accept|reject> <label> <SSH_ORIGINAL_COMMAND>
check() {
	local expect=$1 label=$2 cmd=$3 out rc got
	out=$(SSH_ORIGINAL_COMMAND="$cmd" CDPI_DEPLOY_DRY=1 SSH_CLIENT='198.51.100.7 1234 22' \
		bash "$WRAPPER" 2>&1)
	rc=$?
	if [[ $rc -eq 0 ]]; then
		got=accept
	elif [[ $rc -eq 126 ]]; then
		got=reject
	else
		got="rc=$rc"
	fi

	if [[ $got == "$expect" ]]; then
		pass=$((pass + 1))
		printf 'ok    %-8s %-34s %s\n' "$got" "$label" "${out%%$'\n'*}"
	else
		fail=$((fail + 1))
		printf 'FAIL  want=%s got=%s %-20s %s\n' "$expect" "$got" "$label" "${out%%$'\n'*}"
	fi
}

echo "wrapper: $WRAPPER"
echo

check accept 'deploy sha-<40hex>'   "deploy sha-$SHA40"
check accept 'deploy v1.2.3'        'deploy v1.2.3'
check accept 'rollback'             'rollback'
check accept 'status'               'status'

check reject 'metachars: ; rm -rf /' 'deploy sha-x; rm -rf /'
check reject 'extra word'            "deploy sha-$SHA40 extra"
check reject 'status; ls'            'status; ls'
check reject 'empty command'         ''
check reject 'wrong case (Deploy)'   "Deploy sha-$SHA40"
# shellcheck disable=SC2016  # the literal text is the point of these cases
check reject 'command substitution'  'deploy $(id)'
# shellcheck disable=SC2016
check reject 'backticks'             'deploy `id`'
check reject 'newline smuggling'     "$(printf 'status\nls')"
check reject 'tab separator'         "$(printf 'deploy\tsha-%s' "$SHA40")"
check reject 'short sha (39 hex)'    "deploy sha-${SHA40:1}"
check reject 'long sha (41 hex)'     "deploy sha-${SHA40}a"
check reject 'uppercase sha'         "deploy sha-${SHA40^^}"
check reject 'non-hex sha'           'deploy sha-zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz'
check reject 'v tag without patch'   'deploy v1.2'
check reject 'v tag with suffix'     'deploy v1.2.3-rc1'
check reject 'deploy without a tag'  'deploy'
check reject 'rollback with a tag'   "rollback sha-$SHA40"
check reject 'glob'                  'deploy *'
check reject 'path traversal'        'deploy ../../etc/passwd'
check reject 'unknown verb'          'restart'
check reject 'pipe'                  'status | tee /tmp/x'
check reject 'env prefix'            "FOO=bar deploy sha-$SHA40"

echo
printf '%d passed, %d failed\n' "$pass" "$fail"
[[ $fail -eq 0 ]]
