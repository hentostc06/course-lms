# 13 — Infrastructure Requirements and RAB Boundary

**Pricing boundary:** IDR 20,000,000 is the **developer fee**. VPS, domain, object storage, video streaming, meeting licenses, gateway fees, backup, email and maintenance are **paid separately by the client**. The RAB must not silently combine them with dev compensation.

## Baseline sizing — assumption, not capacity guarantee

| Profile | vCPU | RAM | SSD/NVMe | Appropriate use |
|---|---:|---:|---:|---|
| Development/staging | 2 | 4 GiB | 40–80 GB | Small non-production testing |
| Production baseline | 4 | 8 GiB | 100–160 GB | Initial 100–500 registered accounts, media offloaded |
| Higher concurrency | 8 | 16 GiB | 160+ GB | Consider if load tests show database/worker/quiz pressure |

Actual sizing depends on simultaneous users, question submissions per second, SQL complexity, background jobs, caches, streaming architecture, uploads, uptime and backup operations. 500 registered accounts ≠ 500 simultaneously active users. Media should use external video/object storage.

## Operational components

- Ubuntu Server LTS, Nginx, PHP-FPM, Laravel queue worker/Horizon, scheduler.
- PostgreSQL with tuned backups, Redis persistence/memory policy appropriate to cache vs queues.
- TLS/DNS, Cloudflare proxy/WAF (if required), transactional email provider, Sentry-like error monitoring and uptime checks.
- Offsite encrypted backups, periodic restore test, logging retention and alert thresholds.
- Staging separate from production. Payment sandbox and live credentials isolated.
- Document actual CPU policy, IOPS, backup feature, DC location, SLA, IPv4, renewal price and VAT from each provider before selecting by price.

## RAB spreadsheet columns recommendation

`Item | Specification | Quantity | Unit | Unit Cost IDR | Billing period | Year-1 cost | Provider candidate | Renewal / notes | Mandatory?`

Group: domain/DNS; application compute; database/Redis; backups; object storage; adaptive video; live meetings; email/SMS; payment gateway fees; monitoring/security; SSL if paid (otherwise ACME); implementation/one-time setup if separately charged; taxes; contingency.

## Media budget assumptions

Capture course count, total uploaded recorded minutes, monthly viewing minutes, median PDF size, monthly R2 upload/download operation volume, expected live sessions and participant concurrency. Calculate cost with **official current vendor price**, not static hard-coded estimates. Provider terms may change.

## Deployment and acceptance

Use non-root deployment user, key-based SSH, minimal ports, secret file permissions, supervised workers, deploy rollback strategy and verified restoration; do not restart production or perform destructive operations without authorization.
