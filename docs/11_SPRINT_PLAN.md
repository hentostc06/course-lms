# 11 — Roadmap, Dependencies, Ownership

The timeline is a **planning estimate of ~16–18 calendar weeks** for two developers with consistent capacity. Coding agents reduce mechanical implementation time but not payment approval, design reviews or product-owner decisions. Scope is re-estimated after Sprint 0.

| Phase | Weeks | Developer A — Platform/Commerce | Developer B — Learning/Instructor | Shared gate |
|---|---|---|---|---|
| Sprint 0 | 1–2 | Payment provider feasibility, user/auth proposal, baseline schema | Wireframes, course taxonomy, audit/assessment rules | Owner signs PRD, payment funds flow, design direction |
| Foundation | 3–4 | Repo/CI/auth/roles/catalog DB | Design tokens/layouts, course builder DB | Architecture + schema frozen for V1 |
| Marketplace | 5–7 | Home, catalog, search, detail, carts | Instructor onboarding, editor, moderation, direct media upload | Demo published-course discovery |
| Learning | 8–10 | Commerce scaffolding, order domain | Video player, enrollment, progress, quiz, assessment tests | End-to-end private learning demo |
| Finance | 11–13 | Gateway sandbox, webhook, allocation, settlement, refund/payout state | Learning edge cases, certificate generation, live schedule | Finance reconciliation and security review |
| Completion | 14–15 | Instructor earnings + admin finance + reports | Studio polish, certificates, reviews, attendance evidence | Cross-role UAT |
| Launch gate | 16–18 | Payment retries/load/ops | Mobile/UX/accessibility/test repair | Provider signoff, restore test, handover |

## First sprint backlog (issue-ready)

| Ticket | Owner | Deliverable | Acceptance |
|---|---|---|---|
| S0-01 | Both | Signed V1 scope + decisions | No unresolved money-flow assumption marked as approved |
| S0-02 | A | Provider feasibility memo | Document onboarding/KYC, split, refund, payout, fees, callback proof; blocked if not verified |
| S0-03 | B | UX wireframe set | Home, Catalog, Detail, Checkout, Learning, Studio responsive wireframes |
| S0-04 | A | Draft entities + migrations map | ERD, unique constraints, money invariants reviewed |
| S0-05 | B | Design tokens/components | Style guide, states, accessibility checklist approved |
| S0-06 | Both | Repository and CI plan | No destructive change; clean scaffold planned |

## Ownership and integration rules

- A owns `Identity`, `Catalog`, `Commerce`, `Finance`, checkout UI, Filament finance.
- B owns `Instructor`, `Learning`, `Assessment`, `Certification`, studio UI, learning UI.
- `Course`, `Enrollment`, roles, prices, API response types, migrations and design tokens: **shared contract**; changes require both to review.
- Parallelization allowed **only after contracts are stable**. Do not have separate agents independently generate models or global CSS.
- Use small pull requests (<500 changed lines preferred except generated migrations/design), test results required.

## Milestone demos

1. Student can browse a published course.
2. Instructor can draft → submit → admin approve → publish.
3. Student can enroll in test mode, watch private video, pass quiz, receive certificate.
4. Sandbox payment can produce one enrollment through verified webhook; commission reconciles.
5. Instructor finance shows pending vs settled vs paid accurately; refund reversal demonstrated.
6. Launch signoff including monitoring and backup restore.

## Stop conditions

If payout gateway eligibility, owner-defined refund rules, legal merchant model or costed provider capabilities are unresolved, **do not** announce production finance readiness. Continue developing read-only UI/mock contracts only with conspicuous sandbox labeling and bounded scope.
