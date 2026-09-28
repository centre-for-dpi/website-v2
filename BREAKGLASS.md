# Break-glass log and procedure

## Purpose

Some protections in this project are deliberately hard to get around: the
`protect-main` ruleset on GitHub, and the sealed default accounts on the staging
and production hosts. Hard protections need an escape hatch, or the first real
emergency turns into an improvised one — which is exactly how service ticket
#73176 happened (an unattributed session as the shared `ubuntu` user destroyed
~6,523 files in the live document root, undetected for four months).

This file is that escape hatch **and** its audit trail. Using a break-glass path
is allowed. Using it without an entry in this file is not.

Related decisions: ADR-002 (protect `main`, break-glass path owned and dated),
ADR-004 (immutable image, no in-place edits), ADR-008 (the `deploy` user is the
only non-human account).

## Who may bypass

| Path | Who | What they get |
| --- | --- | --- |
| GitHub `protect-main` ruleset | An **organization owner** of `centre-for-dpi` (the ruleset's only bypass actor is `OrganizationAdmin`) | Push to `main`, or merge a PR, without review or passing checks |
| Staging host (EC2 `cdpi-staging`) | The sealed provider account `ubuntu`, unlocked by the vaulted `.pem` for the `adam-cdpi-staging` key pair | Root-capable shell, outside the named-account attribution model |
| Production host (Lightsail) | The sealed Lightsail default account, unlocked by the vaulted `.pem` | The same |

Nobody else, and no other mechanism. In particular:

- Repository **admins are not bypass actors.** Rulesets bind admins unless they
  are explicitly listed, and they are not. An admin who needs an emergency merge
  asks an org owner.
- Relaxing the ruleset (`enforcement: disabled`, deleting a rule, editing the
  bypass list) is **not** a break-glass path. It is a change to the controls
  themselves and needs its own PR, not an entry here.
- The vendor (Think201) has Write on the repository and no host access at all.
  They have no break-glass path.
- The `.pem` files live **only** in the organisation credential store with
  checkout logging. Not on a laptop, not in chat, not in an MSP share.

## When

Two reasons, and nothing else:

1. **Production is down or degraded** for users and the normal path (PR → review
   → checks → merge → approved deploy) is too slow to stop the bleeding.
2. **A security fix** that must ship before it can be reviewed in the open.

Not for: a deadline, a red check you believe is a false positive, a reviewer
being asleep, a "tiny" change, convenience, or a first attempt at something you
have not tried through the normal path. If the normal path has not been tried,
it is not an emergency yet.

## How

**GitHub.** An org owner merges or pushes using the ruleset bypass. Every
bypassed push and merge is recorded by GitHub in the repository's rule insights
(Settings → Rules → Insights) and in the commit history, so the entry below is
the *explanation*, not the only evidence.

**Hosts.** Check the `.pem` out of the organisation credential store, log in as
the sealed default account (`ubuntu` on staging, the Lightsail default user on
production), do the minimum, log out. Every such login prints a break-glass
banner and raises an alert; `wtmpdb last <account>` and the journal are expected
to be empty except for logins that have a matching entry below.

## The rule

> **An entry in the log below, within 24 hours of the bypass.**

No exceptions. If you bypassed, you write the entry — not the person who
reviewed it afterwards, not "we'll document it in the retro". A bypass without
an entry within 24 hours is itself an incident and gets escalated to the repo
owner.

Every entry also needs a follow-up: the PR that brings the bypassed change back
through the normal path (tests, review, CI), or an issue if the fix needs more
work. "Follow-up: none" is only correct for a verification test that changed
nothing.

## Entry template

Copy this, fill it in, add it to the bottom of the log, open a PR. (If the
ruleset is what you bypassed, you may push the entry directly with the same
bypass — that is the one case where the log entry itself does not wait.)

```
YYYY-MM-DD HH:MM UTC — <github login or host account> — <environment / host> —
<what was done: commits, commands, image tags> —
<why it could not wait through the normal path> —
follow-up: <PR #, issue #, or "none" and why>
```

## Log

Newest entries at the bottom.

- 2026-09-27 ~14:2x UTC — adammwaniki — staging (EC2 `cdpi-staging`) — one login
  as `ubuntu` with the org key pair to verify the break-glass path works (banner
  shown, echo test) — verification test during provisioning, no changes made —
  follow-up: none
