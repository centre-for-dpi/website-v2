# Runbook 01 — Provision a host

Purpose: take a freshly launched Ubuntu box to the state the deploy tooling
expects — Docker, swap, the `deploy` account, named admin accounts, firewall,
fail2ban, auditd, and the forced-command SSH path. Nothing site-specific
happens here; that is runbook 02 (staging) or 03 (production).

Run by: the infra owner (production) or the builder agent (staging), as a
**named** account, never as the provider's default account.

Applies to: EC2 staging (Ubuntu 26.04, cloud-init has already created the
named accounts and `/etc/ssh/sshd_config.d/00-cdpi-access.conf`) and the
Lightsail production box (Ubuntu 22.04/24.04, nothing pre-created).

`install.sh` is idempotent. Rerun it whenever `deploy/host/admins.txt` or the
host scripts change. It never writes `/etc/cdpi/*.env` — those are runbook 02/03.

---

## 1. Check the admin list is current

`deploy/host/admins.txt` is the source of truth for who can log in. One line
per human, `username<TAB>ssh public key`. Adding or removing someone is a PR
against that file plus a rerun of `install.sh` on every host — never an edit
on the box.

```bash
cat deploy/host/admins.txt
```

## 2. Copy the deploy tree to the host

From a checkout on your own machine, as your named account:

```bash
rsync -a --delete deploy/ <you>@<host>:/tmp/deploy/
```

The whole tree is needed: `install.sh` refuses to run if any of the scripts or
drop-ins next to it are missing.

## 3. Dry run first

```bash
ssh <you>@<host>
sudo /tmp/deploy/host/install.sh --check \
  --env staging \
  --admins-file /tmp/deploy/host/admins.txt
```

Use `--env production` on the Lightsail box: it is the only difference, and it
adds a ufw rule allowing 3306/tcp from the compose subnet `172.30.0.0/24`
(production keeps MySQL on the host).

Read the `WOULD:` lines. On a host that has already been provisioned, this
should be a short list or empty.

## 4. Apply

```bash
sudo /tmp/deploy/host/install.sh \
  --env staging \
  --admins-file /tmp/deploy/host/admins.txt
```

What it does, in order: apt packages (`ca-certificates curl gnupg rsync
fail2ban auditd ufw jq`) → Docker CE + compose v2 from download.docker.com →
2 GB `/swapfile` and `vm.swappiness=10` → groups `cdpi-admins` and
`breakglass` and the `deploy` user → `/opt/cdpi`, `/etc/cdpi`, `/var/lib/cdpi`,
`/var/backups/cdpi`, `/etc/ssh/authorized_keys.d` → the three scripts and the
`deploy` sudoers fragment (validated with `visudo -cf` before it is moved into
place) → named admin accounts and their root-owned key files → sshd drop-ins
followed by `sshd -t` and a reload only if the test passes → ufw → fail2ban →
auditd rules → logrotate.

### If the Docker repo has no release for this Ubuntu version

Ubuntu 26.04 is newer than download.docker.com's suites at the time of
writing. The script says so explicitly; rerun that step with the previous LTS
codename:

```bash
sudo DOCKER_APT_SUITE=noble /tmp/deploy/host/install.sh --env staging --admins-file /tmp/deploy/host/admins.txt
```

## 5. Verify

```bash
# Docker
docker compose version
docker run --rm hello-world

# swap
swapon --show; free -h

# accounts and groups
getent group cdpi-admins breakglass docker
id deploy
ls -l /etc/ssh/authorized_keys.d/

# the deploy user's privilege is exactly one script
sudo -l -U deploy

# sshd: effective settings for the deploy user, and globally
sudo sshd -T -C user=deploy | grep -iE 'forcecommand|permittty|allowtcpforwarding|allowagentforwarding|permittunnel'
sudo sshd -T | grep -iE 'permitrootlogin|passwordauthentication|allowgroups|authorizedkeysfile'

# firewall, jail, audit rules
sudo ufw status verbose
sudo fail2ban-client status sshd
sudo auditctl -l | grep cdpi
```

Expect: `forcecommand /usr/local/bin/cdpi-deploy`, `permittty no`, all
forwarding `no`; `permitrootlogin no`, `passwordauthentication no`,
`allowgroups` listing `cdpi-admins`, `deploy`, `breakglass` (26.04 prints them
on separate lines); ufw allowing only 22/80/443 (plus 3306 from
`172.30.0.0/24` on production).

The parser that guards the SSH entry point has its own test; run it from a
checkout at any time:

```bash
./deploy/host/test-cdpi-deploy.sh
```

## 6. Confirm a named login works — before sealing anything

Each admin logs in once as themselves, with their own key, and confirms sudo:

```bash
ssh -i ~/.ssh/cdpi-staging <name>@<host> 'id; sudo -n true && echo sudo-ok'
```

Then, from the box, confirm the journal recorded it under their name:

```bash
sudo journalctl -u ssh | grep 'Accepted publickey'
```

On Ubuntu 26.04 `last` does not exist. Use `wtmpdb last` or the journal.

## 7. Seal the provider's default account as break-glass

Only after step 6. This turns `ubuntu` (EC2) or the Lightsail default user
into a labelled, alerting break-glass account.

```bash
sudo /tmp/deploy/host/install.sh \
  --env staging \
  --admins-file /tmp/deploy/host/admins.txt \
  --seal-default-user
```

It **refuses** unless it can find an `Accepted publickey for <named admin>`
line in the journal, so you cannot lock yourself out. (`CDPI_SEAL_FORCE=1`
overrides that, for a host whose journal has already rotated — use it only
when you have verified a named login another way.)

What sealing does:

- removes the account from `cdpi-admins` (it is not a human);
- **keeps its sudo** — break-glass must be able to fix the host;
- adds it to group `breakglass`;
- writes `/etc/ssh/breakglass-banner` and a `Match Group breakglass` block
  with `Banner` (skipped if cloud-init already put that block in
  `00-cdpi-access.conf`, which is the case on staging);
- appends `session optional pam_exec.so seteuid /usr/local/sbin/cdpi-breakglass-notify`
  to `/etc/pam.d/sshd` (a timestamped backup of that file is kept), so every
  break-glass session raises an `auth.warning` under tag `BREAKGLASS` and, if
  `BREAKGLASS_NOTIFY_CMD` is set in `/etc/cdpi/deploy.env`, fires that webhook.

### The break-glass rule

The provider key pair (`.pem`) lives **only** in the org credential store with
checkout logging. Not on a laptop, not in an MSP share, not in chat history.
Every login to the break-glass account requires a `BREAKGLASS.md` entry in
this repo within 24 hours saying what was done and why — the same rule as a
GitHub ruleset bypass. `wtmpdb last ubuntu` should always be empty except for
entries with a matching `BREAKGLASS.md` line. The supervisor checks this as
part of WP5 and WP8 verification.

Verify the alerting works, once, deliberately (that test is itself the first
`BREAKGLASS.md` entry):

```bash
ssh -i /path/to/org.pem ubuntu@<host> true     # expect the banner
sudo journalctl -t BREAKGLASS --since '5 min ago'
```

## 8. Clean up

```bash
rm -rf /tmp/deploy
```

Next: **runbook 02** (staging) or **runbook 03** (production).
