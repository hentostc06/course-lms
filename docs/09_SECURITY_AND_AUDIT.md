# 09 — Security, Privacy, Audit and Operational Controls

## Identity and authorization

- Authentication via Laravel starter; verified email for privileged actions. Enforce MFA for admin if feasible/approved.
- Role-based permission + **resource-level policies** for each course, order, attempt and uploaded asset.
- Inertia props expose only necessary, authorized fields. No secret quiz answers in the browser before submission.
- Check student enrollment for each private lesson and playback token, including expired/revoked access.
- Rate-limit auth, coupon/review attempts, quiz submission, uploads, certificate verification and webhook handling as appropriate.
- CSRF for session-authenticated write requests; webhook endpoint relies on trusted provider verification and dedicated request controls.
- Parameterized queries through ORM/query builder. Sanitize permitted HTML/article content against XSS.

## Payments and secrets

- All provider secrets in secured configuration; no API secret in Vue/Vite public environment, code, logs or screenshots.
- Use scoped credentials and rotate on exposure; restrict callback endpoints and store only minimum metadata.
- Separate sandbox and production credentials/accounts/configurations.
- No production refunds/payouts without authorized client decision.

## Auditor-grade evidence

Audit relevant events: account role changes, instructor approval, course submit/reject/publish, price changes, grading policy changes, quiz submission/score, certificate issue/revoke, order/payment/refund/payout state change, exported reports.

Required audit fields: `event_type`, `actor_user_id nullable`, `subject_type`, `subject_id`, timestamp UTC, source/trace ID, old/new state summary or approved metadata, reason (when moderation), `request_id`. Avoid passwords, raw provider secrets, full bank details, unnecessary answer PII. Append-only audit logs with retention/access policy.

Certificate verification must disclose minimal metadata: valid/invalid status, course name, name as authorized by policy, issue date, issuer. Revocation or correction must preserve history. Avoid unverifiable claims of regulatory accreditation.

## Media

- Private R2 objects, time-limited signed download URLs; never expose bucket credentials.
- Signed video playback after enrollment authorization; token TTL short and revocation limitations documented.
- Direct upload session tied to instructor-owned draft with content type/size limits, status validation and quota.
- Streaming access controls are not full DRM and cannot prevent screen recording.

## Backups and observability

- Daily offsite PostgreSQL backup with encrypted transport/storage; retention documented and restore test scheduled.
- Log structured app errors and queue failures, uptime, disk space, DB health, queue age, payment webhook failures and reconciliation drift.
- Time synchronization (NTP), UTC persistence / Jakarta presentation.
- Minimum privilege for DB and deployment accounts; no root-only setup as default.

## Compliance workstream

Client legal/operations must review Indonesian PSE status, privacy notice, data collection/retention, training certificate claims, tax invoicing, merchant contracting, instructor KYC and consumer/refund policies. Engineering should create hooks/supporting records; **do not present this document as legal certification**.

## Minimum security test suite

Unauthorized object access, session/CSRF, payment webhook spoof/duplicate, price manipulation, instructor course ownership, quiz answer leakage, signed media invalid/expired token, XSS in course article, public certificate privacy, exported data permissions.
