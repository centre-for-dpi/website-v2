#!/bin/bash
# install.sh — bring a CDPI host to the state the deploy tooling expects.
#
# Run as root, on the host, from a copy of deploy/ (see
# docs/runbooks/01-provision-host.md). Idempotent: run it again after editing
# admins.txt or pulling a new version of the scripts.
#
#   install.sh [--check] [--env staging|production]
#              [--admins-file PATH] [--seal-default-user]
#
#   --check              report what would change; touch nothing
#   --env ENV            staging (default) or production. production also
#                        opens MySQL to the compose subnet in ufw.
#   --admins-file PATH   default: admins.txt next to this script
#   --seal-default-user  turn the cloud provider's default account into
#                        break-glass. Run this only AFTER a named admin login
#                        has been verified; the script refuses otherwise.
#
# Environment:
#   CDPI_INSTALL_SKIP="docker swap ufw fail2ban auditd"
#       Space-separated step names to skip. Used to exercise the pure-file
#       steps in a container, where there is no systemd.
#   DOCKER_APT_SUITE=noble
#       Override the download.docker.com apt suite when the running release
#       has no Docker repository yet (Ubuntu 26.04 at the time of writing).
#   CDPI_SEAL_FORCE=1
#       Seal the default account even though no named login was found in the
#       journal. Only for hosts whose journal has been rotated away, and only
#       when you have verified a named login another way.
#
# Steps, in order:
#   apt docker swap accounts dirs scripts admins sshd ufw fail2ban auditd
#   logrotate seal
set -euo pipefail

SELF_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)

CHECK=0
ENV_NAME=staging
ADMINS_FILE="$SELF_DIR/admins.txt"
SEAL=0
COMPOSE_SUBNET=172.30.0.0/24
SKIP=${CDPI_INSTALL_SKIP:-}

CHANGES=()
ALREADY=()
WARNINGS=()

# ---------------------------------------------------------------------------
# plumbing
# ---------------------------------------------------------------------------

log() { printf '%s  %s\n' "$(date -u '+%H:%M:%SZ')" "$*"; }
die() {
	printf 'install.sh: ERROR: %s\n' "$*" >&2
	exit 1
}
changed() {
	CHANGES+=("$1")
	log "CHANGED  $1"
}
already() {
	ALREADY+=("$1")
	log "ok       $1"
}
would() {
	CHANGES+=("WOULD: $1")
	log "WOULD    $1"
}
warn() {
	WARNINGS+=("$1")
	log "WARN     $1"
}

skipped() {
	local step=$1 s
	for s in $SKIP; do [[ $s == "$step" ]] && return 0; done
	return 1
}

have() { command -v "$1" >/dev/null 2>&1; }

# True only when systemd is actually running, so the file steps work inside a
# container while the service steps quietly stand down.
systemd_running() { [[ -d /run/systemd/system ]] && have systemctl; }

# shellcheck source=/dev/null
os_release_get() { (. /etc/os-release && printf '%s' "${!1-}"); }

# install_file <src> <dest> <mode> <owner:group> — content-aware and idempotent.
install_file() {
	local src=$1 dest=$2 mode=$3 owner=$4
	if [[ -f $dest ]] && cmp -s "$src" "$dest"; then
		local cur
		cur=$(stat -c '%a %U:%G' "$dest")
		if [[ $cur == "${mode#0} $owner" || $cur == "$mode $owner" ]]; then
			already "$dest up to date"
			return 0
		fi
	fi
	if [[ $CHECK == 1 ]]; then
		would "install $src -> $dest ($mode $owner)"
		return 0
	fi
	install -o "${owner%%:*}" -g "${owner##*:}" -m "$mode" "$src" "$dest"
	changed "installed $dest ($mode $owner)"
}

# write_file <dest> <mode> <owner:group> — content on stdin.
write_file() {
	local dest=$1 mode=$2 owner=$3 tmp
	tmp=$(mktemp)
	cat >"$tmp"
	if [[ -f $dest ]] && cmp -s "$tmp" "$dest"; then
		rm -f "$tmp"
		already "$dest up to date"
		return 0
	fi
	if [[ $CHECK == 1 ]]; then
		would "write $dest ($mode $owner)"
		rm -f "$tmp"
		return 0
	fi
	install -D -o "${owner%%:*}" -g "${owner##*:}" -m "$mode" "$tmp" "$dest"
	rm -f "$tmp"
	changed "wrote $dest ($mode $owner)"
}

ensure_dir() {
	local dir=$1 mode=$2 owner=$3
	if [[ -d $dir ]]; then
		if [[ $CHECK == 1 ]]; then
			already "$dir exists"
			return 0
		fi
		chmod "$mode" "$dir"
		chown "$owner" "$dir"
		already "$dir exists"
		return 0
	fi
	if [[ $CHECK == 1 ]]; then
		would "mkdir $dir ($mode $owner)"
		return 0
	fi
	install -d -o "${owner%%:*}" -g "${owner##*:}" -m "$mode" "$dir"
	changed "created $dir ($mode $owner)"
}

ensure_group() {
	local g=$1
	if getent group "$g" >/dev/null; then
		already "group $g exists"
		return 0
	fi
	if [[ $CHECK == 1 ]]; then
		would "groupadd $g"
		return 0
	fi
	groupadd "$g"
	changed "created group $g"
}

# ---------------------------------------------------------------------------
# argument parsing
# ---------------------------------------------------------------------------

while [[ $# -gt 0 ]]; do
	case $1 in
	--check) CHECK=1 ;;
	--seal-default-user) SEAL=1 ;;
	--env)
		ENV_NAME=${2:-}
		shift
		;;
	--env=*) ENV_NAME=${1#*=} ;;
	--admins-file)
		ADMINS_FILE=${2:-}
		shift
		;;
	--admins-file=*) ADMINS_FILE=${1#*=} ;;
	-h | --help)
		sed -n '2,40p' "$0"
		exit 0
		;;
	*) die "unknown argument: $1" ;;
	esac
	shift
done

[[ $ENV_NAME == staging || $ENV_NAME == production ]] ||
	die "--env must be staging or production (got '$ENV_NAME')"
[[ $EUID -eq 0 ]] || die "must run as root"
[[ -r $ADMINS_FILE ]] || die "cannot read admins file: $ADMINS_FILE"

for f in cdpi-deploy cdpi-deploy-root cdpi-breakglass-notify sudoers.d-cdpi-deploy \
	sshd_config.d-cdpi-deploy.conf sshd_config.d-cdpi-keys.conf; do
	[[ -f $SELF_DIR/$f ]] || die "missing $SELF_DIR/$f — copy the whole deploy/ tree"
done

# Named admin usernames, in file order.
ADMIN_USERS=()
while IFS=$'\t' read -r _u _k; do
	[[ -z ${_u:-} || ${_u:0:1} == '#' ]] && continue
	[[ -n ${_k:-} ]] || continue
	for _existing in ${ADMIN_USERS[@]+"${ADMIN_USERS[@]}"}; do
		[[ $_existing == "$_u" ]] && continue 2
	done
	ADMIN_USERS+=("$_u")
done <"$ADMINS_FILE"
((${#ADMIN_USERS[@]} > 0)) || die "$ADMINS_FILE lists no admins"

SUDO_FLAVOUR=classic
if have sudo && sudo --version 2>/dev/null | head -1 | grep -qi 'sudo-rs'; then
	SUDO_FLAVOUR=sudo-rs
fi

log "install.sh: env=$ENV_NAME check=$CHECK seal=$SEAL sudo=$SUDO_FLAVOUR"
log "admins: ${ADMIN_USERS[*]}"
[[ -n $SKIP ]] && log "skipping steps: $SKIP"

# ---------------------------------------------------------------------------
# (a) packages and Docker
# ---------------------------------------------------------------------------

step_apt() {
	local pkgs=(ca-certificates curl gnupg rsync fail2ban auditd ufw jq)
	local missing=() p
	for p in "${pkgs[@]}"; do
		dpkg -s "$p" >/dev/null 2>&1 || missing+=("$p")
	done
	if ((${#missing[@]} == 0)); then
		already "apt packages present: ${pkgs[*]}"
		return 0
	fi
	if [[ $CHECK == 1 ]]; then
		would "apt-get install ${missing[*]}"
		return 0
	fi
	export DEBIAN_FRONTEND=noninteractive
	apt-get update -qq
	apt-get install -y --no-install-recommends "${missing[@]}"
	changed "apt packages installed: ${missing[*]}"
}

step_docker() {
	if docker compose version >/dev/null 2>&1; then
		already "docker + compose v2 present ($(docker --version 2>/dev/null | cut -d, -f1))"
		return 0
	fi
	if [[ $CHECK == 1 ]]; then
		would "add download.docker.com apt repo and install docker-ce docker-ce-cli containerd.io docker-compose-plugin"
		return 0
	fi
	local distro suite arch
	distro=$(os_release_get ID)
	suite=${DOCKER_APT_SUITE:-$(os_release_get VERSION_CODENAME)}
	arch=$(dpkg --print-architecture)
	[[ -n $distro && -n $suite ]] || die "cannot determine distro/suite from /etc/os-release"

	export DEBIAN_FRONTEND=noninteractive
	install -d -m 0755 /etc/apt/keyrings
	curl -fsSL "https://download.docker.com/linux/$distro/gpg" -o /etc/apt/keyrings/docker.asc
	chmod a+r /etc/apt/keyrings/docker.asc
	printf 'deb [arch=%s signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/%s %s stable\n' \
		"$arch" "$distro" "$suite" >/etc/apt/sources.list.d/docker.list
	if ! apt-get update -qq; then
		die "apt update failed. download.docker.com may not publish '$suite' yet; rerun with DOCKER_APT_SUITE=<previous LTS codename>"
	fi
	apt-get install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
	systemd_running && systemctl enable --now docker
	docker compose version >/dev/null || die "docker compose plugin is not working after install"
	changed "Docker CE and the compose v2 plugin installed (suite $suite)"
}

# ---------------------------------------------------------------------------
# (b) swap — 2 GB box, Docker plus MySQL will not fit without it
# ---------------------------------------------------------------------------

step_swap() {
	if [[ -n $(swapon --noheadings --show 2>/dev/null || true) ]]; then
		already "swap already active"
	elif [[ $CHECK == 1 ]]; then
		would "create /swapfile (2G), mkswap, swapon, add an fstab entry"
	else
		if ! fallocate -l 2G /swapfile 2>/dev/null; then
			dd if=/dev/zero of=/swapfile bs=1M count=2048 status=none
		fi
		chmod 600 /swapfile
		mkswap /swapfile >/dev/null
		swapon /swapfile
		grep -qE '^/swapfile[[:space:]]' /etc/fstab ||
			printf '/swapfile none swap sw 0 0\n' >>/etc/fstab
		changed "created and enabled a 2 GB /swapfile"
	fi
	printf 'vm.swappiness=10\n' | write_file /etc/sysctl.d/99-cdpi-swappiness.conf 0644 root:root
	if [[ $CHECK != 1 ]] && have sysctl; then
		sysctl -q -w vm.swappiness=10 2>/dev/null || warn "could not set vm.swappiness at runtime"
	fi
}

# ---------------------------------------------------------------------------
# (c) groups and the deploy account
# ---------------------------------------------------------------------------

step_accounts() {
	ensure_group cdpi-admins
	ensure_group breakglass

	if id deploy >/dev/null 2>&1; then
		already "user deploy exists"
	elif [[ $CHECK == 1 ]]; then
		would "useradd deploy (home /home/deploy, shell /bin/bash, no password)"
	else
		# A real shell is required: sshd runs ForceCommand through the user's
		# login shell. Login is still impossible without a key, and the key
		# can only ever reach the wrapper.
		useradd --create-home --home-dir /home/deploy --shell /bin/bash \
			--comment 'CDPI deploy (forced command only)' deploy
		passwd -l deploy >/dev/null
		changed "created user deploy (locked password, shell /bin/bash)"
	fi

	# The deploy user must NOT be in group docker. Membership of that group is
	# root-equivalent (it can bind-mount / into a privileged container), and it
	# would grant that power outside the ForceCommand wrapper entirely — which
	# is exactly what the ADR-008 correction rules out: sudo limited to one
	# root script, nothing else. cdpi-deploy-root already runs as root via
	# `sudo -n`, so every docker call it makes is root's, not deploy's.
	#
	# Enforced rather than merely omitted, so a host where an earlier version
	# of this script (or a hand edit) added it gets fixed on the next run.
	if id -nG deploy 2>/dev/null | tr ' ' '\n' | grep -qx docker; then
		if [[ $CHECK == 1 ]]; then
			would "gpasswd -d deploy docker (docker group membership is root-equivalent)"
		else
			gpasswd -d deploy docker >/dev/null
			changed "removed deploy from group docker (root-equivalent access outside the forced command)"
		fi
	else
		already "deploy is not in group docker (correct: that would be root-equivalent)"
	fi
}

# ---------------------------------------------------------------------------
# (d) directories
# ---------------------------------------------------------------------------

step_dirs() {
	ensure_dir /opt/cdpi 0755 root:root
	ensure_dir /etc/cdpi 0700 root:root
	ensure_dir /var/lib/cdpi 0700 root:root
	ensure_dir /var/backups/cdpi 0700 root:root
	ensure_dir /etc/ssh/authorized_keys.d 0755 root:root
}

# ---------------------------------------------------------------------------
# (e) scripts and sudoers
# ---------------------------------------------------------------------------

step_scripts() {
	install_file "$SELF_DIR/cdpi-deploy" /usr/local/bin/cdpi-deploy 0755 root:root
	install_file "$SELF_DIR/cdpi-deploy-root" /usr/local/sbin/cdpi-deploy-root 0750 root:root
	install_file "$SELF_DIR/cdpi-breakglass-notify" /usr/local/sbin/cdpi-breakglass-notify 0750 root:root

	# Validate the sudoers fragment in a staging location first: a syntax
	# error inside /etc/sudoers.d can lock every admin out of sudo.
	local tmp
	tmp=$(mktemp)
	cp "$SELF_DIR/sudoers.d-cdpi-deploy" "$tmp"
	chmod 0440 "$tmp"
	if have visudo; then
		if visudo -cf "$tmp" >/dev/null 2>&1; then
			already "sudoers fragment for deploy validates"
		else
			rm -f "$tmp"
			die "sudoers.d-cdpi-deploy failed visudo -cf; not installing"
		fi
	else
		warn "visudo not found (sudo-rs hosts may not ship it); installing the sudoers fragment unvalidated — check 'sudo -n -l -U deploy' afterwards"
	fi
	install_file "$tmp" /etc/sudoers.d/cdpi-deploy 0440 root:root
	rm -f "$tmp"
}

# ---------------------------------------------------------------------------
# (f) named admin accounts
# ---------------------------------------------------------------------------

step_admins() {
	local u keys_tmp user key
	for u in "${ADMIN_USERS[@]}"; do
		if id "$u" >/dev/null 2>&1; then
			already "user $u exists"
		elif [[ $CHECK == 1 ]]; then
			would "adduser --disabled-password $u"
		else
			adduser --disabled-password --gecos "CDPI admin $u" "$u" >/dev/null
			changed "created user $u"
		fi

		if id -nG "$u" 2>/dev/null | tr ' ' '\n' | grep -qx cdpi-admins; then
			already "$u is in cdpi-admins"
		elif [[ $CHECK == 1 ]]; then
			would "usermod -aG cdpi-admins $u"
		else
			usermod -aG cdpi-admins "$u"
			changed "added $u to cdpi-admins"
		fi

		# Rebuild the key file from admins.txt so a removed line revokes.
		keys_tmp=$(mktemp)
		{
			printf '# Managed by deploy/host/install.sh from admins.txt. Do not edit by hand.\n'
			while IFS=$'\t' read -r user key; do
				[[ -z ${user:-} || ${user:0:1} == '#' ]] && continue
				[[ $user == "$u" ]] || continue
				[[ -n ${key:-} ]] && printf '%s\n' "$key"
			done <"$ADMINS_FILE"
		} >"$keys_tmp"
		write_file "/etc/ssh/authorized_keys.d/$u" 0644 root:root <"$keys_tmp"
		rm -f "$keys_tmp"

		# cloud-init already wrote ~/.ssh/authorized_keys for the accounts it
		# created. Leave it alone: removing it during a rerun could lock
		# someone out mid-session. It is still a second, self-writable key
		# source, so say so.
		if [[ -s /home/$u/.ssh/authorized_keys ]]; then
			warn "/home/$u/.ssh/authorized_keys exists (cloud-init). Left untouched; keys there are NOT managed by admins.txt and $u can edit them. Remove it once /etc/ssh/authorized_keys.d/$u is confirmed working."
		fi
	done

	# Revocation. Any key file this script previously managed (it carries the
	# header below) whose user is no longer in admins.txt is removed, which is
	# what makes "delete the line and rerun" actually revoke access. Files
	# without the header — /etc/ssh/authorized_keys.d/deploy above all — are
	# never touched.
	local f base keep
	for f in /etc/ssh/authorized_keys.d/*; do
		[[ -f $f ]] || continue
		base=$(basename "$f")
		[[ $base == deploy ]] && continue
		grep -q 'Managed by deploy/host/install.sh' "$f" || continue
		keep=0
		for u in "${ADMIN_USERS[@]}"; do
			[[ $u == "$base" ]] && keep=1
		done
		((keep == 1)) && continue
		if [[ $CHECK == 1 ]]; then
			would "remove /etc/ssh/authorized_keys.d/$base ($base is no longer in admins.txt)"
		else
			rm -f "$f"
			changed "revoked SSH access for $base (removed from admins.txt); the account itself was left in place"
		fi
	done

	printf '%%cdpi-admins ALL=(ALL) NOPASSWD:ALL\n' |
		write_file /etc/sudoers.d/cdpi-admins 0440 root:root

	if [[ $SUDO_FLAVOUR == classic ]]; then
		ensure_dir /var/log/sudo-io 0700 root:root
		{
			printf '# Classic sudo: full command and session (I/O) logging, so a\n'
			printf '# privileged session can be replayed and attributed.\n'
			printf 'Defaults log_input, log_output\n'
			printf 'Defaults iolog_dir=/var/log/sudo-io\n'
			printf 'Defaults logfile=/var/log/sudo.log\n'
		} | write_file /etc/sudoers.d/cdpi-admins-logging 0440 root:root
	else
		already "sudo-rs detected: per-command journal logging only, no I/O session logging available (see the ADR note)"
		if [[ -f /etc/sudoers.d/cdpi-admins-logging ]]; then
			if [[ $CHECK == 1 ]]; then
				would "remove /etc/sudoers.d/cdpi-admins-logging (unsupported by sudo-rs)"
			else
				rm -f /etc/sudoers.d/cdpi-admins-logging
				changed "removed /etc/sudoers.d/cdpi-admins-logging (sudo-rs does not support it)"
			fi
		fi
	fi
}

# ---------------------------------------------------------------------------
# (e/g) sshd drop-ins
# ---------------------------------------------------------------------------

sshd_reload() {
	if [[ $CHECK == 1 ]]; then
		would "sshd -t && systemctl reload ssh"
		return 0
	fi
	if ! have sshd && ! [[ -x /usr/sbin/sshd ]]; then
		warn "sshd not installed; drop-ins written but neither tested nor reloaded"
		return 0
	fi
	local sshd_bin=${SSHD_BIN:-/usr/sbin/sshd}
	if ! "$sshd_bin" -t; then
		die "sshd -t FAILED after writing the drop-ins. Fix /etc/ssh/sshd_config.d before reloading, the running sshd is still healthy."
	fi
	already "sshd -t passes"
	if systemd_running; then
		systemctl reload ssh 2>/dev/null || systemctl reload sshd 2>/dev/null ||
			warn "could not reload ssh; do it by hand"
		changed "reloaded sshd"
	else
		warn "no systemd; reload sshd by hand for the drop-ins to take effect"
	fi
}

step_sshd() {
	# (g) Baseline hardening. cloud-init already writes this file on staging,
	# with a Match Group breakglass block at the end; do not clobber it.
	if [[ -f /etc/ssh/sshd_config.d/00-cdpi-access.conf ]]; then
		already "/etc/ssh/sshd_config.d/00-cdpi-access.conf already present (cloud-init or an earlier run); left as-is"
	else
		{
			printf '# Installed by deploy/host/install.sh\n'
			printf 'PermitRootLogin no\n'
			printf 'PasswordAuthentication no\n'
			printf 'KbdInteractiveAuthentication no\n'
			printf 'AllowGroups cdpi-admins deploy breakglass\n'
		} | write_file /etc/ssh/sshd_config.d/00-cdpi-access.conf 0644 root:root
	fi

	install_file "$SELF_DIR/sshd_config.d-cdpi-keys.conf" \
		/etc/ssh/sshd_config.d/01-cdpi-keys.conf 0644 root:root
	install_file "$SELF_DIR/sshd_config.d-cdpi-deploy.conf" \
		/etc/ssh/sshd_config.d/10-cdpi-deploy.conf 0644 root:root

	sshd_reload
}

# ---------------------------------------------------------------------------
# (h) firewall
# ---------------------------------------------------------------------------

step_ufw() {
	have ufw || {
		warn "ufw not installed; skipping firewall step"
		return 0
	}
	if [[ $CHECK == 1 ]]; then
		would "ufw default deny incoming / allow outgoing; allow 22,80,443/tcp$([[ $ENV_NAME == production ]] && printf ', 3306/tcp from %s' "$COMPOSE_SUBNET"); ufw --force enable"
		ufw status verbose 2>/dev/null | sed 's/^/         /' || true
		return 0
	fi
	ufw --force default deny incoming >/dev/null
	ufw --force default allow outgoing >/dev/null
	local p
	for p in 22/tcp 80/tcp 443/tcp; do
		ufw allow "$p" >/dev/null
	done
	if [[ $ENV_NAME == production ]]; then
		# The WordPress container reaches the host's MySQL over the bridge.
		ufw allow from "$COMPOSE_SUBNET" to any port 3306 proto tcp >/dev/null
		changed "ufw allows 3306/tcp from $COMPOSE_SUBNET"
	fi
	ufw --force enable >/dev/null
	changed "ufw enabled: deny incoming, allow 22/80/443 tcp"
}

# ---------------------------------------------------------------------------
# (i) fail2ban
# ---------------------------------------------------------------------------

step_fail2ban() {
	if [[ ! -d /etc/fail2ban ]]; then
		warn "fail2ban is not installed (no /etc/fail2ban); jail not written"
		return 0
	fi
	{
		printf '# Installed by deploy/host/install.sh\n'
		printf '[sshd]\n'
		printf 'enabled = true\n'
		printf 'bantime = 1h\n'
		printf 'findtime = 10m\n'
		printf 'maxretry = 5\n'
	} | write_file /etc/fail2ban/jail.d/cdpi.conf 0644 root:root
	if [[ $CHECK != 1 ]] && systemd_running; then
		systemctl enable --now fail2ban >/dev/null 2>&1 || warn "could not enable fail2ban"
		systemctl reload fail2ban >/dev/null 2>&1 || systemctl restart fail2ban >/dev/null 2>&1 || true
	fi
}

# ---------------------------------------------------------------------------
# (j) auditd
# ---------------------------------------------------------------------------

step_auditd() {
	local tmp
	tmp=$(mktemp)
	{
		printf '# Installed by deploy/host/install.sh\n'
		printf -- '-w /opt/cdpi -p wa -k cdpi\n'
		printf -- '-w /etc/cdpi -p wa -k cdpi\n'
		printf -- '-w /etc/ssh -p wa -k cdpi-ssh\n'
		printf -- '-w /etc/sudoers.d -p wa -k cdpi-sudo\n'
		# The April 2026 incident was a write spree across the live docroot.
		[[ -d /var/www ]] && printf -- '-w /var/www -p wa -k cdpi-www\n'
		printf -- '-a always,exit -F arch=b64 -S execve -F euid=0 -k cdpi-root-exec\n'
	} >"$tmp"
	ensure_dir /etc/audit/rules.d 0750 root:root
	write_file /etc/audit/rules.d/cdpi.rules 0640 root:root <"$tmp"
	rm -f "$tmp"
	if [[ $CHECK != 1 ]] && have augenrules; then
		augenrules --load >/dev/null 2>&1 || warn "augenrules --load failed (auditd may not be running yet)"
	fi
	if [[ $CHECK != 1 ]] && systemd_running; then
		systemctl enable --now auditd >/dev/null 2>&1 || warn "could not enable auditd"
	fi
}

# ---------------------------------------------------------------------------
# (l) log rotation
# ---------------------------------------------------------------------------

step_logrotate() {
	{
		printf '/var/log/cdpi-deploy.log {\n'
		printf '\tweekly\n'
		printf '\trotate 12\n'
		printf '\tmissingok\n'
		printf '\tnotifempty\n'
		printf '\tcompress\n'
		printf '\tdelaycompress\n'
		printf '\tcreate 0640 root adm\n'
		printf '}\n'
	} | write_file /etc/logrotate.d/cdpi-deploy 0644 root:root
}

# ---------------------------------------------------------------------------
# (k) seal the provider's default account as break-glass
# ---------------------------------------------------------------------------

detect_default_user() {
	if id ubuntu >/dev/null 2>&1; then
		printf 'ubuntu'
		return 0
	fi
	local u
	u=$(getent passwd 1000 | cut -d: -f1)
	if [[ -n $u ]]; then
		local a
		for a in "${ADMIN_USERS[@]}"; do
			[[ $a == "$u" ]] && return 1
		done
		printf '%s' "$u"
		return 0
	fi
	return 1
}

named_login_seen() {
	local u out
	for u in "${ADMIN_USERS[@]}"; do
		out=$(journalctl -u ssh -u sshd --no-pager 2>/dev/null |
			grep -c "Accepted publickey for $u " || true)
		[[ ${out:-0} -gt 0 ]] && return 0
		out=$(journalctl _COMM=sshd --no-pager 2>/dev/null |
			grep -c "Accepted publickey for $u " || true)
		[[ ${out:-0} -gt 0 ]] && return 0
	done
	return 1
}

step_seal() {
	local du
	du=$(detect_default_user) || {
		already "no provider default account found; nothing to seal"
		return 0
	}
	log "seal: default provider account is '$du'"

	if named_login_seen; then
		already "seal: a named admin login is present in the journal"
	elif [[ ${CDPI_SEAL_FORCE:-0} == 1 ]]; then
		warn "seal: no named admin login found in the journal, proceeding because CDPI_SEAL_FORCE=1"
	else
		die "refusing to seal '$du': no 'Accepted publickey for <named admin>' line found in the journal. Log in once as a named admin, confirm sudo works, then rerun. (CDPI_SEAL_FORCE=1 overrides, for hosts whose journal has rotated.)"
	fi

	# Its sudo is deliberately kept: break-glass must be able to fix the box.
	if id -nG "$du" 2>/dev/null | tr ' ' '\n' | grep -qx cdpi-admins; then
		if [[ $CHECK == 1 ]]; then
			would "gpasswd -d $du cdpi-admins"
		else
			gpasswd -d "$du" cdpi-admins >/dev/null
			changed "removed $du from cdpi-admins (it is not a named human account)"
		fi
	else
		already "$du is not in cdpi-admins"
	fi

	if id -nG "$du" 2>/dev/null | tr ' ' '\n' | grep -qx breakglass; then
		already "$du is in breakglass"
	elif [[ $CHECK == 1 ]]; then
		would "usermod -aG breakglass $du"
	else
		usermod -aG breakglass "$du"
		changed "added $du to breakglass"
	fi

	{
		printf '*** BREAK-GLASS ACCOUNT ***\n'
		printf 'This login uses the shared organisation key pair. It is logged and alerting.\n'
		printf 'Record what you did and why in BREAKGLASS.md within 24 hours.\n'
	} | write_file /etc/ssh/breakglass-banner 0644 root:root

	# cloud-init may already carry the Match Group breakglass banner block.
	if grep -qs 'Match Group breakglass' /etc/ssh/sshd_config.d/00-cdpi-access.conf; then
		already "Match Group breakglass banner already in 00-cdpi-access.conf (cloud-init); not adding 20-cdpi-breakglass.conf"
		if [[ -f /etc/ssh/sshd_config.d/20-cdpi-breakglass.conf ]]; then
			if [[ $CHECK == 1 ]]; then
				would "remove the now-duplicate /etc/ssh/sshd_config.d/20-cdpi-breakglass.conf"
			else
				rm -f /etc/ssh/sshd_config.d/20-cdpi-breakglass.conf
				changed "removed duplicate 20-cdpi-breakglass.conf"
			fi
		fi
	else
		{
			printf '# Installed by deploy/host/install.sh --seal-default-user\n'
			printf 'Match Group breakglass\n'
			printf '\tBanner /etc/ssh/breakglass-banner\n'
		} | write_file /etc/ssh/sshd_config.d/20-cdpi-breakglass.conf 0644 root:root
	fi

	# PAM hook so every break-glass session raises an alert, not just a banner.
	local pam_line='session optional pam_exec.so seteuid /usr/local/sbin/cdpi-breakglass-notify'
	if [[ ! -f /etc/pam.d/sshd ]]; then
		warn "/etc/pam.d/sshd not found; break-glass login alerting not installed"
	elif grep -qF 'cdpi-breakglass-notify' /etc/pam.d/sshd; then
		already "pam_exec break-glass hook already in /etc/pam.d/sshd"
	elif [[ $CHECK == 1 ]]; then
		would "append the pam_exec break-glass hook to /etc/pam.d/sshd"
	else
		cp -a /etc/pam.d/sshd "/etc/pam.d/sshd.cdpi-backup.$(date -u +%Y%m%d%H%M%S)"
		printf '\n# CDPI break-glass login alerting (install.sh --seal-default-user)\n%s\n' \
			"$pam_line" >>/etc/pam.d/sshd
		changed "appended the pam_exec break-glass hook to /etc/pam.d/sshd (backup kept)"
	fi

	sshd_reload
	warn "seal: '$du' KEEPS its sudo on purpose — break-glass must be able to fix the host. Its .pem belongs in the org vault with checkout logging and nowhere else."
}

# ---------------------------------------------------------------------------
# run
# ---------------------------------------------------------------------------

STEPS=(apt docker swap accounts dirs scripts admins sshd ufw fail2ban auditd logrotate)
[[ $SEAL == 1 ]] && STEPS+=(seal)

for step in "${STEPS[@]}"; do
	if skipped "$step"; then
		log "----- step $step: SKIPPED (CDPI_INSTALL_SKIP)"
		continue
	fi
	log "----- step $step"
	"step_$step"
done

# ---------------------------------------------------------------------------
# (m) summary
# ---------------------------------------------------------------------------

echo
echo "==================== summary ===================="
printf 'host:    %s\n' "$(hostname)"
printf 'env:     %s\n' "$ENV_NAME"
printf 'mode:    %s\n' "$([[ $CHECK == 1 ]] && echo 'check only, nothing changed' || echo 'apply')"
printf 'sudo:    %s\n' "$SUDO_FLAVOUR"
echo
printf 'changed (%d):\n' "${#CHANGES[@]}"
if ((${#CHANGES[@]} == 0)); then
	echo "  (nothing — the host was already in the desired state)"
else
	printf '  - %s\n' "${CHANGES[@]}"
fi
echo
printf 'already in place (%d):\n' "${#ALREADY[@]}"
printf '  - %s\n' ${ALREADY[@]+"${ALREADY[@]}"}
if ((${#WARNINGS[@]} > 0)); then
	echo
	printf 'warnings (%d):\n' "${#WARNINGS[@]}"
	printf '  ! %s\n' "${WARNINGS[@]}"
fi
echo
echo "next: docs/runbooks/02-staging-bootstrap.md (or 03-production-prepare.md)"
echo "      /etc/cdpi/{app,caddy,db,deploy,registry}.env do NOT exist yet — create"
echo "      them from deploy/*.env.example, root:root 0600."
