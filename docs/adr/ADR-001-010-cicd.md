# **Architecture Decision Record: CI/CD Pipeline Setup**

## **Status:** Proposed

# **Context**

* The website/server is deployed manually: build locally, push, merge, SSH into the Lightsail production box, `git pull` (only works with root access), then eyeball the live site.  
* A third party vendor commits directly to `main` with no branch protection, no review, and no automated checks. (btw there is a stale reference in the readme note to a `master` branch that needs to be corrected)  
* Build output is produced locally and committed to the repository rather than built in CI, so what runs in production depends on whoever ran `npm build` last.  
* There is no staging environment, no automated tests, and no rollback path other than a manual `git` revert on a live box.  
* Production is a Lightsail instance (2 vCPU, 2 GB RAM, 60 GB SSD); staging will be a matching EC2 t3.small that does not yet exist.  
* **Incident driver (Service Ticket \#73176, August 2026):** the site suffered a full outage traced to an interactive root session on 16 April 2026 in which an unattributed actor, logged in as the shared ubuntu user with sudo, recursively ran gzip \-r against the live document root (/var/www/cdpi-website), destroying \~6,523 live files. Detection took over four months (no monitoring existed). Attribution failed (shared account). Recovery succeeded only because the destructive command left decompressible copies behind; no backup restore was ever performed.  
* **Stack**: the site is WordPress on Apache serving a custom theme from the repository. The theme has a real npm/webpack build: Sass and JS sources compile to content-hashed bundles in public/, and public/webpack.manifest.json maps entry names so PHP can enqueue the right files. PHP templates and WordPress core are not built at all.  
* **TBC:** the production MySQL database is assumed to be co-located on the Lightsail box and must never be touched by a deploy.

| Role | Actor | Responsibility |
| :---- | :---- | :---- |
| **Repo owner** | **CDPI \- architects** | GitHub settings, rulesets, vendor access, workflow files |
| **Infra owner** | **CDPI \- architects** | Hosts, Docker runtime, networking, TLS, credentials, backups, host access control |
| **Release owner** | **CDPI \- architects** | Deploy workflows, approval gates, rollback execution, monitoring and alerting |
| **Vendor** | **Think201** | External contributor building the site; adapts workflow to ADR-002/003/004 |
| **MSP** | **Locuz** | Managed server provider; holds host access and is bound by ADR-013/014 |

# **Phased Decisions**

# **Phase 1**

## **ADR-001: Use GitHub Actions as the CI/CD platform**

* **Status:** Proposed  
* **Owner:** Repo owner  
* **Scope:** CI/CD execution platform.  
* **Context:** The repository is already hosted as a public repository on GitHub. Introducing Jenkins or GitLab CI would mean provisioning and maintaining another server, which is a non-essential investment.  
* **Decision:** We run all CI and CD workflows in GitHub Actions, defined as YAML in `.github/workflows/` in the same repository.  
* **Consequences:**   
  * No new infrastructure to maintain and pipeline config is versioned with the code.   
  * We accept dependency on GitHub availability for deploys.   
  * Several protections this ADR set relies on (rulesets, required reviewers, Environment protection rules) are free because the repo is public. If the repo is ever made private, these require a paid GitHub plan (Team), in addition to metered Actions minutes. This migration would require a separate ADR.

## **ADR-002: Protect main, require pull requests and 3rd party vendor role safeguards**

* **Status:** Proposed  
* **Owner:** Repo owner  
* **Scope:** Repository access control and merge policy.  
* **Context:** Direct pushes to `main` by the external vendor are the single largest risk in the current process. Nothing prevents unreviewed, unbuilt code reaching production.  
* **Decision:**   
  * We enable a GitHub ruleset on `main` that blocks direct pushes and force pushes, requires a pull request with at least one approving review, and requires the CI status checks to pass before merge.  
  * Change the vendor's repository role to Write from Admin. The ruleset's bypass list is kept empty except for the documented break-glass identity below. Rulesets are bypassable by admins, so admin membership is audited when this ADR is accepted.  
  * Break-glass path (owned, dated): the Repo owner is the only identity permitted to bypass the ruleset. Bypassing requires a written note in the repo (issue or BREAKGLASS.md entry) within 24 hours stating what shipped and why. This path must be documented before the vendor's first blocked push, not after.  
* **Consequences:**   
  * Unreviewed code can no longer reach production.   
  * The vendor must change working habits and every change now costs a review cycle.   
  * We need a documented break-glass path for emergency fixes, since bypassing protection must be a deliberate, logged act.

## **ADR-003: Adopt short-lived branches off main**

* **Status:** Proposed  
* **Owner:** Repo owner (compliance applies to everyone, including the vendor)  
* **Context:** With `main` protected we need an agreed branching model. Long-lived develop/release branches add merge overhead that a single-site team does not need.  
* **Decision:** We branch from `main` for each change, keep branches short-lived, and merge back via pull request. `main` is always the deployable truth.  
* **Consequences:** Simple mental model and fewer merge conflicts. It requires small, frequent changes; large vendor workstreams will need to be broken down rather than parked on a branch for weeks.

## **ADR-004: Build once as a container image and publish it**

* **Status:** Proposed  
* **Owner:** Infra owner (Dockerfile, image); Repo owner (repo transition)  
* **Context:** The site is WordPress: the artifact is core \+ theme \+ plugins, and the most damaging incident to date was in-place modification of the live document root. The artifact decision is therefore also a tamper-resistance decision. `npm build` currently runs on a developer machine and the output is committed. Running it on a 2 GB production box during a deploy risks OOM and downtime. We also need staging and production to run byte-identical artifacts.  
* **Decision:**   
  * Image contents: pinned WordPress core, the full theme, and pinned plugins, resolved at build time from the repository (Composer/WordPress Packagist or committed dependencies).  
  * We build a multi-stage Docker image in CI : a Node stage runs npm ci and npm run build to compile the theme's Sass/JS into the content-hashed public/ bundles and webpack.manifest.json, then the final stage assembles pinned core \+ theme (PHP templates plus the freshly built assets, including the manifest PHP reads to enqueue them) \+ pinned plugins. The image is built in CI on every merge to `main`, tagged with the commit SHA (tags immutable, never re-pushed), and published to GHCR. Build output is removed from the repository and added to `.gitignore`.  
  * Mutable content lives outside the image: wp-content/uploads is a named volume, and the database is on the host (per header assumption).  
  * Transition sequencing (coordinated with the vendor): the committed public/ build output is removed from the repo and gitignored, since CI now builds it. This lands in a dedicated PR the Repo owner schedules with the vendor in advance, and it directly breaks the vendor's documented workflow (the README instructs committing public/ and the manifest), so the README is updated in the same PR and the vendor is walked through the new flow before it merges. Already-committed build artifacts stay in git history; no history rewrite.  
  * Retention policy: keep all SHA-tagged images 90 days; release-tagged images indefinitely; prune untagged weekly via scheduled workflow.  
* **Consequences:**   
  * The same tested artifact runs in both environments and no compilation happens on the servers.   
  * The uploads volume remains mutable by design  
  * WordPress admin convenience (one-click plugin installs) is deliberately sacrificed; content editors keep normal post/page/media workflows, which touch only the database and uploads.  
  * We take on Docker as a new runtime dependency on two small boxes (roughly 100 to 200 MB of RAM overhead, so swap should be configured), and both hosts need credentials to pull from GHCR.  
  * The April-2026 (resolved on 20-21 August 2026\) incident class is structurally bounded: in-place modification of PHP core inside the container is either impossible (read-only fs) or erased by a restart/redeploy, turning a multi-day manual reconstruction into a minutes-long action.

## **ADR-005: Deliver application configuration and secrets explicitly**

* **Status:** Proposed  
* **Owner:** Infra Owner  
* **Context:** Even with a secure deploy key we need a decision on how the application's own configuration reaches the boxes. Left implicit, staging and production configs will likely silently diverge.  
* **Decision:** Each host carries a single environment file (/etc/\<app\>/app.env) owned by root, readable only by the deploy user, managed by hand by the Infra owner. The file's keys (not values) are documented in the repo so divergence is checkable. No application secret is stored in the repository or in GitHub Actions secrets (those are for pipeline credentials only).  
* **Consequences:** Deliberately low-tech for two boxes; a known scaling limit. If a third host appears or secrets need rotation more than quarterly, a secrets manager becomes its own ADR. Staging and production configs are compared as part of the ADR-006 drift check.

## **ADR-006: Provision staging as an EC2 t3.small mirroring production**

* **Status:** Proposed  
* **Owner:**  Infra owner  
* **Context:** There is nowhere to verify a change before it hits users. Verification currently happens on the live site, including, in the August incident, after the first incomplete restoration pass.  
* **Decision:** We provision a t3.small staging instance (2 vCPU, 2 GB RAM) with the same OS, Docker version, and reverse proxy configuration as production, on its own subdomain and with its own data store.  
* **Consequences:** Changes are observable before release and load behaviour is representative. We accept a second instance to pay for and patch, and the risk of configuration drift between the two boxes until provisioning is scripted.

## **ADR-007: Deploy automatically to staging, gate production behind manual approval**

* **Status:** Proposed  
* **Context:** The team wants fast feedback but is not yet ready to trust an unattended production release, given there is no meaningful test suite.  
* **Decision:**   
  * Every merge to `main` deploys the new image to staging automatically. Production deploys use the same workflow and image tag but run against a GitHub Environment with a required reviewer, so a human approves the release.  
  * Post-deploy verification: after each deploy, the workflow runs a smoke check against the environment's health endpoint (HTTP 200 \+ expected marker) before declaring success. The production approver is approving a verified staging deploy, not just a green checkmark. A failed smoke check on production automatically triggers a rollback to the previous SHA.  
  * Workflow trigger security: deploy workflows run only on pushes to protected main and on manual dispatch. No workflow with access to secrets runs on pull\_request events, and pull\_request\_target is not used with code checkout: the repo is public and the vendor is third-party, so fork-triggered secret exposure is a real attack path.  
  * Deploy order: pre-deploy backup → image pull → migration step → container swap → smoke check → (on failure) rollback.  
* **Consequences:**   
  * Staging is always current and production releases stay deliberate and auditable.   
  * Production lags staging by however long approval takes, and this is explicitly a stepping stone: the gate should be reconsidered once test coverage justifies continuous deployment.

## **ADR-008: Deploy over SSH using a dedicated deploy user**

* **Status:** Proposed  
* **Owner:** Infra owner  
* **Context:** Deploys must reach a Lightsail instance and an EC2 instance. Lightsail does not share EC2's IAM instance role model, so a single mechanism that works for both is preferable to two different ones.  
* **Decision:**   
  * Each host gets a non-root `deploy` user whose sole job is to pull the tagged image and restart the service.   
  * The private key lives in a GitHub Environment secret, scoped per environment, with SSH restricted to GitHub Actions runner egress where practical.  
* **Consequences:**   
  * One deploy mechanism covers both hosts and no human needs interactive SSH for routine releases.   
  * We accept holding a long-lived SSH key in GitHub secrets, which requires a rotation schedule. Migrating to AWS SSM Session Manager later would remove the key entirely and is the intended follow-up.

## **ADR-009: Gate on lint and build now, defer the coverage threshold**

* **Status:** Proposed  
* **Context:** The reference pipeline design assumes unit tests, integration tests, and an 80 to 90 percent coverage gate. The codebase currently has no test suite, so enforcing a coverage threshold on day one would either block all work or be set so low it is meaningless.  
* **Decision:** Required PR status checks at launch are lint and a successful production build. Unit tests run in CI and are required as soon as they exist. A coverage threshold is deferred to a follow-up ADR once a baseline is measured.  
* **Consequences:** The pipeline ships now and catches syntax errors, broken imports, and failing builds before merge. This is accepted technical debt: logic regressions will still reach staging, and staging plus manual approval are the only real safety net until tests land.

## **ADR-010: Roll back by redeploying the previous image tag**

* **Status:** Proposed  
* **Owner:** Release Owner  
* **Context:** Rollback today means SSHing into production and reverting Git state by hand, under pressure, with a rebuild on a 2 GB box. In the August incident, "rollback" meant two days of manual decompression across two passes, with the repo owner catching the first pass's incompleteness by eyeballing the site.  
* **Decision:** Every image is tagged with its commit SHA and retained in GHCR. Rollback is re-running the deploy workflow with the last known good SHA.  
* **Consequences:** Recovery becomes a fast, repeatable action rather than improvised surgery. It does not roll back database or schema changes, which must be written to be backward compatible, and GHCR storage grows, so a retention policy is needed.

---

# **References**

* GitHub Actions workflow syntax: https://docs.github.com/en/actions/using-workflows/workflow-syntax-for-github-actions  
* Branch rulesets and required status checks: https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/managing-rulesets/about-rulesets  
* Environments and required reviewers: https://docs.github.com/en/actions/deployment/targeting-different-environments/using-environments-for-deployment  
* Publishing to GitHub Container Registry: https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry  
* Docker multi-stage builds: https://docs.docker.com/build/building/multi-stage/  
* EC2 T3 instance specifications: https://aws.amazon.com/ec2/instance-types/t3/  
* AWS Systems Manager Session Manager (future ADR-007 replacement): https://docs.aws.amazon.com/systems-manager/latest/userguide/session-manager.html

