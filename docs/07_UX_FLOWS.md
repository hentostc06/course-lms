# 07 — UX Flows and Content States

## Journey A: Discover → Purchase → Learn

1. Visitor searches or browses. Only `published` courses show.
2. Detail page states outcomes, curriculum, instructor, duration, price, actual reviews and refund policy.
3. Register/login, add to cart; V1 enforces one instructor per payment, including explanatory message for mixed cart.
4. Backend recalculates prices and validates active course/instructor; creates pending order and provider session.
5. Gateway verified event makes order paid; UI shows processing state until server confirms.
6. Enrollment created once; CTA becomes **Mulai Belajar**; failure/expired/retry handled without duplicate orders.
7. Student watches authorized media, marks/completes lessons, attempts assessment, receives certificate if eligible.

**Empty/error states:** No courses found; course unpublished between view and checkout; video processing; payment expired; webhook delayed; user already enrolled; unavailable live meeting.

## Journey B: Instructor apply → publish → earn

1. User opens `/teach`, reads requirements and applies.
2. Creates instructor profile, submits fields required for platform review; provider KYC handled via provider when supported.
3. Admin approves publishing rights; instructor creates draft.
4. Course editor tracks title/description/outcomes, category, thumbnail, price, section/lesson, quiz and certificate rules.
5. Submit → pending review; admin approves/rejects with reasons; revisions produce clear status and history.
6. Instructor checks enrollments, course performance and earnings status with distinction between gross sales, platform fee, provider fee, pending settlement, and paid amount.

**Error states:** Failed upload; duplicate slug; rejected course; missing compliance info; provider KYC incomplete; payout not yet enabled.

## Journey C: Auditor-friendly training evidence

1. Course specifies learning outcomes, quiz minimum score, optional live attendance and feedback rules.
2. Attendance evidence labels origin: platform join-click event vs external provider verified attendance. Never present join-click as confirmed meeting attendance.
3. Quiz attempts store immutable submitted answers, score and timestamp; retakes follow per-course policy.
4. Completion evaluates rules on server and stores reason/evidence summary.
5. Certificate stores unique verification code, issue date, owner, course identifier and criteria version.
6. Admin export contains authorized fields and timestamps; audit log records issuer and data changes.

## Journey D: Refund and instructor payout

1. Student requests refund using approved policy/time window.
2. Admin reviews; backend initiates provider refund when supported and records status.
3. Enrollment state changes only after confirmed refund per agreed policy; retain historical progress for audit.
4. Finance ledger records reversals instead of rewriting past revenue.
5. Any already-paid instructor amount leads to reconciliation/adjustment workflow rather than invisible negative balance.

## Content microcopy rules

- Indonesian, plain, precise and actionable; no vague motivational filler.
- Status terms must distinguish *diproses*, *berhasil*, *gagal*, *menunggu verifikasi*, *belum bisa dicairkan*.
- Disclose restrictions before checkout. Never claim “sertifikat terakreditasi” absent proof.
- Page must still be usable at 360px viewport, with text zoom at 200%, keyboard tab order and visible focus.
