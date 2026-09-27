# Runbook — Backup and restore

Every deploy and every rollback takes a backup **before** it changes anything.
That is the only automatic backup in this design; off-box copies are a WP11
follow-up.

---

## 1. Where backups live

All under `$BACKUP_DIR` from `/etc/cdpi/deploy.env`, default
`/var/backups/cdpi`, mode 0700 root-only:

| Path | What |
|---|---|
| `db-<ts>.sql.gz` | gzipped `mysqldump --single-transaction --quick --routines --triggers`, taken before the pull |
| `uploads-<ts>/` | an rsync snapshot of the `cdpi_uploads` volume |
| `uploads-latest` | symlink to the newest snapshot; it is the `--link-dest` base for the next one |

`<ts>` is UTC, `YYYYMMDDTHHMMSSZ`.

Snapshots are hard-linked against the previous one, so N snapshots of an
unchanged uploads directory cost roughly one copy. **Consequence worth knowing:**
editing a file in one snapshot in place would edit it in every snapshot that
shares the inode. Never write into a snapshot; copy out of it.

```bash
sudo ls -lh /var/backups/cdpi/
sudo du -sh /var/backups/cdpi/
sudo df -h /var/backups/cdpi
```

## 2. Retention

`KEEP_BACKUPS` in `/etc/cdpi/deploy.env` (default 5) applies to dumps and
snapshots independently. Pruning runs on every deploy, keeping the newest N by
mtime. Five is roughly five deploys, not five days — a busy week rotates
faster. Increase it (and check the disk) before a risky change.

Related: `KEEP_IMAGES` (default 3) controls how many image tags stay on the
box, on top of the current and previous tags, which are never removed.

## 3. Take a backup by hand

```bash
sudo /usr/local/sbin/cdpi-deploy-root status      # confirm the host's state first

TS=$(date -u +%Y%m%dT%H%M%SZ)

# container DB (staging)
cd /opt/cdpi
sudo docker compose -f compose.yaml -f compose.staging.yaml exec -T db \
  sh -c 'exec mysqldump --single-transaction --quick --routines --triggers -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' \
  | gzip -c | sudo tee "/var/backups/cdpi/db-$TS.sql.gz" >/dev/null

# host DB (production)
sudo mysqldump --defaults-extra-file=/etc/cdpi/my.cnf --single-transaction --quick \
  --routines --triggers <dbname> | gzip -c | sudo tee "/var/backups/cdpi/db-$TS.sql.gz" >/dev/null

# uploads
MP=$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)
sudo rsync -a --delete --link-dest=/var/backups/cdpi/uploads-latest \
  "$MP/" "/var/backups/cdpi/uploads-$TS/"
sudo ln -sfn "/var/backups/cdpi/uploads-$TS" /var/backups/cdpi/uploads-latest
```

Check a dump before trusting it:

```bash
sudo gzip -t /var/backups/cdpi/db-<ts>.sql.gz && echo "gzip ok"
sudo zcat /var/backups/cdpi/db-<ts>.sql.gz | head -5
sudo zcat /var/backups/cdpi/db-<ts>.sql.gz | grep -c 'INSERT INTO'
```

## 4. Restore the database

**This overwrites live content. Take a fresh dump first** — you may need to get
back to where you are now.

### Container DB (staging)

```bash
cd /opt/cdpi
DC="docker compose -f compose.yaml -f compose.staging.yaml"

sudo $DC stop wordpress                       # stop writers
sudo zcat /var/backups/cdpi/db-<ts>.sql.gz \
  | sudo $DC exec -T db sh -c 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"'
sudo $DC start wordpress
sudo $DC run --rm --user www-data wordpress wp cache flush
```

If the dump does not contain `DROP TABLE` / `CREATE TABLE` statements (it does,
`mysqldump` adds them by default), drop and recreate the schema first.

### Host DB (production)

```bash
cd /opt/cdpi
DC="docker compose -f compose.yaml -f compose.production.yaml"

sudo $DC stop wordpress
sudo zcat /var/backups/cdpi/db-<ts>.sql.gz | sudo mysql --defaults-extra-file=/etc/cdpi/my.cnf <dbname>
sudo $DC start wordpress
sudo $DC run --rm --no-deps --user www-data wordpress wp cache flush
```

`/etc/cdpi/my.cnf` holds the backup account, which has no write grants. For a
restore use a credential that does — either root interactively, or a second
defaults file. Do not add write grants to the backup account.

Afterwards: purge the CDN, and check the URLs are what you expect
(`wp option get home`, `wp option get siteurl`). A dump taken from production
and restored onto staging still needs the `search-replace` from runbook 02 §11.

## 5. Restore uploads

```bash
MP=$(sudo docker volume inspect -f '{{.Mountpoint}}' cdpi_uploads)

# dry run first — always
sudo rsync -a --delete --dry-run --itemize-changes \
  /var/backups/cdpi/uploads-<ts>/ "$MP/" | head -50

sudo rsync -a --delete /var/backups/cdpi/uploads-<ts>/ "$MP/"
sudo chown -R 33:33 "$MP"          # www-data inside the container
sudo du -sh "$MP"
```

`--delete` makes the volume match the snapshot exactly, removing anything
uploaded since. Drop `--delete` to merge instead, which is usually what you
want if the problem was deletion rather than corruption.

To recover a single file without touching anything else:

```bash
sudo cp -a "/var/backups/cdpi/uploads-<ts>/2026/04/thing.jpg" "$MP/2026/04/thing.jpg"
sudo chown 33:33 "$MP/2026/04/thing.jpg"
```

## 6. Full-site recovery order

1. `ssh deploy@<host> status` — what tag is live, what the previous one is.
2. Restore the database (§4) if content or settings are wrong.
3. Restore uploads (§5) if media is missing.
4. Redeploy the correct image tag (`rollback.md`) if the code is wrong.
5. Purge the CDN.
6. Verify: homepage, a post permalink, `/daas/`, the XLSX export, an image, the
   admin login.

Restoring the database and rolling the image back at the same time is the case
to be careful about — see `rollback.md` §4 on core versions.

## 7. Off-box copies

Not yet automated (WP11). Until then, before anything risky:

```bash
# production: a Lightsail snapshot is the cheapest whole-box copy
# staging: an EBS snapshot, or pull the dump to your own machine
scp <you>@<host>:/tmp/db-<ts>.sql.gz ./      # after sudo cp'ing it somewhere readable
```

Backups in `/var/backups/cdpi` are on the same disk as the site. A disk loss
takes both. Treat that as the known gap it is.

## 8. Practise it

A restore you have never run is not a backup. The April 2026 incident was
survived by luck, not by a restore: no backup was ever restored. Restore the
latest production dump onto staging as part of WP5, and again whenever the
retention settings or MySQL version change.
