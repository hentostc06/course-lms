# 08 — Payment, Commission, Payout and Refund Specification

**High-risk domain.** Do not implement production payouts until the client and payment provider confirm merchant eligibility, sub-account/onboarding, allowed funds flow, KYC requirements, fee rules, settlement terms, refund and reversal support. Xendit XenPlatform is a candidate, not an approved contract.

## V1 money model

- Currency: IDR, integer amounts (BIGINT).
- Marketplace commission: editable platform policy, snapshotted per order item as basis points; **20% is illustrative only**, not an agreed rate.
- One course owner; V1 one instructor per checkout transaction (multiple courses same instructor permitted).
- Provider payment fees, tax, refunds and promotions require written fee-payer rules.
- Do not store payment-card credentials or bank account documents beyond what is essential. Use provider-hosted interfaces/KYC where feasible.

## Order state machine

`draft → pending_payment → paid | cancelled | expired | failed`.

`paid` does not mean fully settled to instructor. Track payment, settlement, revenue allocation and payout separately. Refunds may be partial; use independent refund records and cumulative refunded amount.

## Webhook processing (idempotent)

1. Accept only documented provider callback path; authenticate signature/token per provider spec and fetch transaction state if required.
2. Validate provider reference, merchant account, currency and amount against known pending order.
3. Insert provider event with unique event ID/idempotency key; on duplicate, acknowledge without repeating business effects.
4. DB transaction with row lock on payment/order; validate legal state transition.
5. Mark payment/order paid once; create course enrollment with UNIQUE(student, course), revenue allocations with UNIQUE(order_item, type or allocation-id key).
6. Commit; dispatch email/receipt/certificate-eligibility jobs **after commit**. Do not issue certificate purely on purchase.
7. Reconcile payment and settlement with provider periodically; log discrepancies (no PII/secret leakage).

Handle delayed, unordered or retried events and recover from worker crashes. Human-readable success pages must poll/read **server state**, not trust redirect parameters.

## Example commission math

Assume a course gross paid amount IDR 300,000 and commission `2000 bps` (=20%). With no discount and fees handled separately: platform share = IDR 60,000; instructor gross share = IDR 240,000. Rounding policy and actual net payout depend on approved fee rules. Do not derive fee inclusion by assumption.

Prefer an append-only ledger or explicit `revenue_allocations`, `refund_allocations`, `settlement_events` and `payout_items`. Every financial adjustment retains provenance and timestamp; historical orders never silently recompute due to future commission changes.

## Payout eligibility

Instructor is approved + provider onboarding/KYC complete + amount is settled/available + no hold/dispute + payout policy met. Record provider payout reference, status (`pending`, `processing`, `paid`, `failed`) and reconciliation timestamp. Provider payout operation is an external action; acknowledgement is not success.

## Refunds and disputes

Written policies define student eligibility window, approval authority, instructor share reversal, platform fee reversal, payment fee responsibility, partial refund behavior, course access revocation timing and treatment of completed certificates. Build explicit transitions and reports; do not hard delete money rows.

## Acceptance test cases

- Repeated webhook → single enrollment, single financial allocation.
- False redirect/success query string → zero enrollment.
- Mismatched amount/currency/order ref → reject + alert.
- Course unpublished during checkout → deterministic price/order policy.
- Multi-instructor mixed cart → clear split-checkout UX, no invalid one-order split.
- Refund before/after settlement and payout → auditable reversal/reconciliation.
- Payout callback repeated/out of order → idempotent state.
- Provider sandbox and live gateway review separately signed off.

Official provider documentation links in `REFERENCES.md`; use actual supported endpoints and signatures, never fabricated API schemas.
