#!/usr/bin/env bash
#
# Verify the webpack asset manifest produced by `npm run build`.
#
# functions.php reads public/webpack.manifest.json at runtime and enqueues the
# "main.js" and "main.css" entries relative to the theme directory. If a build
# emits a manifest whose entries do not exist, the site loads with no CSS and no
# JavaScript and nothing else notices, so CI asserts the same three things the
# theme depends on: both keys are present, each value lives under /public/, and
# the file each value points at exists and is not empty.
#
# Run from the repository root. Uses `node -e` rather than jq because the job
# that runs this has already installed Node, so Node is the guaranteed parser.
set -euo pipefail

manifest='public/webpack.manifest.json'
prefix='/public/'

fail() {
  printf 'check-manifest: FAIL: %s\n' "$*" >&2
  exit 1
}

[[ -f $manifest ]] || fail "$manifest does not exist - did 'npm run build' run?"

# Emits one "<key>\t<manifest value>\t<path on disk>" line per required entry,
# or exits non-zero after describing every problem it found.
# shellcheck disable=SC2016  # the JS below is quoted on purpose: its template
# literals and $-expressions are evaluated by node, never by the shell.
entries="$(
  node -e '
    const fs = require("node:fs");
    const [file, prefix] = process.argv.slice(1);
    let data;
    try {
      data = JSON.parse(fs.readFileSync(file, "utf8"));
    } catch (err) {
      console.error(`${file} is not valid JSON: ${err.message}`);
      process.exit(1);
    }
    if (data === null || typeof data !== "object" || Array.isArray(data)) {
      console.error(`${file} must contain a JSON object, got ${Array.isArray(data) ? "an array" : typeof data}`);
      process.exit(1);
    }
    const problems = [];
    const rows = [];
    for (const key of ["main.js", "main.css"]) {
      const value = data[key];
      if (typeof value !== "string" || value === "") {
        problems.push(`key "${key}" is missing or is not a non-empty string (found ${JSON.stringify(value)})`);
        continue;
      }
      if (!value.startsWith(prefix)) {
        problems.push(`key "${key}" is "${value}", which does not start with "${prefix}"`);
        continue;
      }
      rows.push([key, value, `public/${value.slice(prefix.length)}`].join("\t"));
    }
    if (problems.length > 0) {
      console.error(problems.join("\n"));
      process.exit(1);
    }
    process.stdout.write(`${rows.join("\n")}\n`);
  ' "$manifest" "$prefix"
)" || fail "$manifest is not a usable manifest (see the errors above)"

printf 'check-manifest: %s\n' "$manifest"

while IFS=$'\t' read -r key value path; do
  [[ -n $key ]] || continue
  [[ -e $path ]] || fail "$key is \"$value\" but $path does not exist"
  [[ -f $path ]] || fail "$key is \"$value\" but $path is not a regular file"
  [[ -s $path ]] || fail "$key is \"$value\" but $path is empty"
  printf '  %-8s -> %s (%s bytes)\n' "$key" "$value" "$(wc -c <"$path")"
done <<<"$entries"

printf 'check-manifest: OK\n'
