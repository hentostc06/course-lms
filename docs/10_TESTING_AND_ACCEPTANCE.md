# 10 — Test Strategy and Definition of Done

## Test pyramid

- **Unit (Pest/PHPUnit):** money allocations/bps rounding, completion thresholds, grading, permitted state transitions, discount policy, certificate eligibility.
- **Feature/integration (Pest/PHPUnit):** auth, policies, checkout, verified payment callback, enrollment, quiz attempt, moderation, exports, payout event/reconciliation.
- **Frontend (Vitest/Vue Test Utils):** card, filters, forms, checkout states, player state, accessibility interactions.
- **Browser end-to-end (Playwright):** discover → purchase sandbox → learn → quiz → certificate; instructor apply → course submit → approval → publishing; refund/payout states in controlled sandbox.
- **Non-functional:** load, backup restore, failure injection, mobile, keyboard, contrast, security scans.

## Critical test checklist

| ID | Scenario | Expected |
|---|---|---|
| SEC-01 | Student changes URL to another user's order | Denied |
| SEC-02 | Instructor changes URL to another instructor's draft | Denied |
| SEC-03 | Student without active enrollment requests video token | Denied |
| SEC-04 | Media signed token expired | Access rejected by provider |
| SEC-05 | Quiz API queried before submit | No answer key in response |
| PAY-01 | Browser redirects with fabricated `success` | No order/enrollment mutation |
| PAY-02 | Identical valid paid webhook delivered 3x | One paid transition, one enrollment, one allocation |
| PAY-03 | Valid signature but mismatched amount | No fulfillment; discrepancy recorded |
| PAY-04 | Provider fails after checkout creation | Order recoverable/retryable |
| PAY-05 | Refunded order | Refund journal, access policy and payout adjustment verified |
| PAY-06 | Failed or delayed settlement | Separate status and reconciliation warning |
| IN-01 | Instructor submits incomplete draft | Blocked with actionable missing fields |
| IN-02 | Student searches unpublished course | Not shown |
| LN-01 | Progress sent for other course's lesson | Rejected |
| LN-02 | Completion threshold not reached | No certificate |
| LN-03 | Eligible completion | One signed/versioned certificate |
| AUD-01 | Admin rejects course | Audit event contains actor, reason and timestamp |
| UX-01 | Checkout and player at 360px width | No overflow, primary action accessible |
| OPS-01 | Restore encrypted database backup in staging | Restorable and documented |

## Performance budgets (initial targets, to be validated)

- Public catalog and common authenticated pages: benchmark p95 app response around 800ms or lower at an **agreed realistic concurrency**; do not count external video network in Laravel server time.
- Document workload: request rate, median/95th percentile, DB/CPU/memory, cache hit, queue lag, 1% error budget target, dataset size and infrastructure config.
- Specific 500 *registered accounts* target is **not** a 500 concurrent-load acceptance criterion. Agree separate load profile for simultaneous quiz events before sign-off.

## Definition of Done per issue

- Requirement/acceptance criteria agreed; UI follows component spec.
- Backend validates and authorizes all mutations.
- Migration has constraints/indexes/rollout impact.
- Appropriate tests created, run and result reported.
- Loading, empty, failure, accessible and mobile states covered.
- No secrets/fake payment success/placeholder financial numbers.
- Docs updated, CI green, one other developer reviewed.

## Definition of Done for public launch

All P0 end-to-end journeys work in production-like staging; provider account/payout compliance approved; reconciliation demonstrated against provider sandbox; no P0 security and finance issues; accessible desktop/mobile checkout and player; backups with proven restore; monitoring and runbook; owner UAT sign-off and production rollout/rollback plan.
