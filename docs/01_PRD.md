# 01 — Product Requirements Document (PRD)

## Jobs-to-be-done

- **Learner:** “I want to find credible industry training, compare outcomes, pay safely, attend/consume lessons and receive proof of completion.”
- **Instructor:** “I want to publish structured courses, understand the publication rules, teach live/recorded, and track earned revenue.”
- **Training administrator:** “I want verifiable, time-stamped evidence of participation, assessment, completion and payouts without manual spreadsheets.”

## Functional requirements (prioritized)

| ID | Requirement | V1 rule | Priority |
|---|---|---|---|
| ID-01 | Authentication, verified email, password reset | One account, multi-role | P0 |
| CT-01 | Course catalog / categories / search / filters | Published courses only | P0 |
| CT-02 | Course detail / structured syllabus / preview | Includes outcomes, requirements, instructor, price | P0 |
| IN-01 | Public instructor application & review | Admin approves before publishing | P0 |
| IN-02 | Course builder with sections/lessons | Draft, submit, approve/reject/publish | P0 |
| MD-01 | Video, PDF, article materials | Private access checks; direct upload for video | P0 |
| EN-01 | Enrollment / personal learning dashboard | Paid enrollment only after verified payment | P0 |
| PR-01 | Per-lesson completion and course progress | Computed server-side | P0 |
| AS-01 | Multiple-choice quizzes with grading | Attempt history, passing score | P0 |
| CE-01 | PDF certificate / verification URL | Versioned criteria, unique code | P0 |
| LS-01 | Live session schedule / meeting links | External meeting platform, limited attendance evidence | P0 |
| CO-01 | Cart / orders / checkout / payment status | Real provider sandbox & verified webhooks | P0 |
| FI-01 | Commission snapshot and instructor finance | Ledger and reconciliation, provider-governed payout | P0 |
| AD-01 | Admin moderation / reports | Filament back-office with audit history | P0 |
| RV-01 | Course ratings / written review | Only enrolled students, rate limits | P1 |
| TR-01 | Attendance and training evidence export | Define source of truth; CSV export | P1 |
| FB-01 | Post-class feedback form | Optional according to certificate rule | P1 |

**Compliance-aware extension (P1):** topic competency tags, training program IDs, outcomes, pre-test/post-test, evidence report by course/date/student, cancellation reason and who approved it. No legal claims that certificates constitute professional accreditation without evidence.

## Key rules

1. One user may have `student` and `instructor` roles concurrently.
2. Course purchase belongs to a specified snapshot of course title, price, instructor and commission.
3. Paid enrollment is idempotent; a repeated webhook cannot create duplicate enrollment or allocations.
4. Students cannot see unpublished content beyond instructor-marked preview.
5. Certificate release requires rules saved with course configuration and verified state, not frontend-only checks.
6. Review only allowed for eligible enrolled users; one review per user/course subject to moderation policy.
7. Instructors only see their own business information and course-owned learning aggregates, not other instructors' sales.
8. An admin moderation action must have actor, timestamp, previous/next status and reason.
9. Refund and revoked access policy must be signed off before live payments.

## Success metrics for launch

- End-to-end paid enrollment and lesson playback function in staging/production.
- Funnel: catalog view → detail → checkout initiated → paid enrollment is measurable without collecting unnecessary personal data.
- No unresolved P0 security or finance defects at launch.
- Completion / quiz / certificate can be verified from stored evidence.
- Payment vs settlement vs ledger discrepancy can be detected and investigated.

## Out of scope by default

Native mobile apps; built-in live video; advanced DRM; SCORM; enterprise SSO; AI tutor; subscriptions; affiliates; multi-instructor revenue sharing per course; marketplace cross-instructor single-payment split; automated grading essays; proctoring; legally binding professional license/accreditation.
