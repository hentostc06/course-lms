# 14 — Open Decisions and Launch Blockers

These are **unknown** and must never be treated by an AI agent as confirmed. The team can build non-finance fundamentals while obtaining decisions.

| ID | Decision required | Proposed default for planning | Owner | Launch blocker? |
|---|---|---|---|---|
| D01 | Final platform name, logo, editorial art direction | Placeholder internal brand; no copied design | Client | Before visual signoff |
| D02 | Commission percentage and promotions | Configurable, 20% example only | Client | Yes for sales |
| D03 | Payment provider, merchant account, subaccount/KYC eligibility | Evaluate Xendit XenPlatform, verify contract | Client + provider | **Yes** |
| D04 | Who pays gateway fees, VAT/tax treatment, settlement intervals | No assumption | Client/accountant/provider | **Yes** |
| D05 | Refund policy, course-access revocation and chargeback responsibility | Must be written | Client | **Yes** |
| D06 | Payout release threshold, hold period, failed payout handling | Provider-supported policy | Client/provider | **Yes** |
| D07 | Multi-seller cart/checkout | V1 separate per-instructor checkout | Client | For scope signoff |
| D08 | Assessment passing rules and retakes | Per course, configurable | Client/auditor | Before certificate release |
| D09 | What constitutes live attendance evidence | Track link-join event only unless provider integrated | Client | For audit claims |
| D10 | Certificate template, issuer name, signature and verification privacy | Original branded PDF, no accreditation claim | Client | Before certificate launch |
| D11 | Course content ownership, refunds, copyright/licensing | Terms supplied by client/legal | Client | Before public launch |
| D12 | Client-approved infrastructure vendor, backup retention | 4 vCPU / 8 GiB initial baseline | Client/IT | Before production |
| D13 | PSE/privacy/data retention/invoicing review | Hand off to client legal/ops | Client | Before public launch |
| D14 | Owner-approved V1 acceptance criteria and delivery dates | Draft in docs/02 and docs/10 | Client + two devs | Before scope freeze |

## Agent policy for open items

If implementing an area depends on an undecided item, declare the blocker. Use feature flags and test fixtures if necessary, with a conspicuous `NOT_LIVE`/sandbox status; never simulate payouts while telling users that money was transferred.
