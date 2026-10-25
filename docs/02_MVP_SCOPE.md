# 02 — MVP Contract Boundary

## V1 includes (subject to approved acceptance criteria)

1. Professional public marketplace: home, category, search/filter, course detail, preview, instructor profile.
2. Student registration/login, profile, purchase history, basic wishlist, cart.
3. Instructor registration/approval, course drafts, section/lesson builder, file upload, content moderation.
4. Learning player (video, articles, PDF links), per-lesson progress, multiple-choice quizzes with passing threshold, certificates with QR/unique verification URL.
5. Scheduled live class with external Zoom/Meet link and enrollment-based access, **not** attendance API integration unless specifically approved.
6. Marketplace-enabled checkout with authenticated webhooks, order/commission ledger, provider settlement/payout status and admin reconciliation (only after payment provider approves the business model).
7. Filament admin: users, courses, instructors, categories, payment events and finance reports.
8. Operational basics: staging, backups, monitoring, logs, audit history, tests, documentation and handover.

## Explicit V1 limitations

- Buy-once pricing in IDR; one currency.
- One course owner; no multi-instructor revenue splitting within a course.
- One instructor per checkout transaction initially. Multiple courses from the **same** instructor are allowed; when cart mixes instructors, split into separate checkouts and explain clearly to student.
- One configurable commission schedule and per-order snapshot; no complex affiliate/discount attribution.
- Payout uses provider-supported capabilities only. If the provider account is not activated, **merchant launch blocked**; prototype must label financial workflows as simulated, never operational.
- No automatically inferred attendance from video-watch time or external Zoom presence; label evidence source.
- Completion and certificate authenticity can be verified from platform evidence, but do not imply regulator accreditation.

## V1.5 candidates

Coupon, bundled courses, reviews advanced moderation, bookmarks/notes, advanced reporting exports, question banks, attendance API, detailed competency mapping.

## V2+ candidates

Multi-seller single checkout (once gateway supports/reconciles split/refund), subscriptions, co-instructors, revenue-share promotion, full refund automation, SCORM, proctoring, native apps, multilingual storefront, complex enterprise training management.

## Change control

No feature may be silently moved from V2 into V1. Any scope change records (a) problem, (b) affected user role, (c) screens/routes/data, (d) estimate, (e) impact on milestones, (f) owner approval. Successful code generation does not mean scope is free.
