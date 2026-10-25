# 04 — PostgreSQL Data Model and ERD

All table names shown are planned, not claimed to exist. Use `BIGINT` surrogate PKs unless an existing repository uses UUIDs consistently. Dates in UTC (`timestamptz` for source timestamps); monetary IDR fields are integer `BIGINT`; commission percent represented as `INTEGER basis_points` (10,000 bps = 100%).

## Identity and learning

```mermaid
erDiagram
  users ||--o| instructor_profiles : owns
  users ||--o{ enrollments : joins
  users ||--o{ reviews : writes
  instructor_profiles ||--o{ courses : owns
  categories ||--o{ courses : categorizes
  courses ||--o{ course_sections : has
  course_sections ||--o{ lessons : has
  lessons }o--o| media_assets : references
  lessons ||--o| quizzes : may_have
  quizzes ||--o{ questions : contains
  questions ||--o{ question_options : offers
  quizzes ||--o{ quiz_attempts : tested_in
  quiz_attempts ||--o{ attempt_answers : stores
  enrollments ||--o{ lesson_progress : contains
  enrollments ||--o| certificates : earns
  courses ||--o{ live_sessions : schedules
  courses ||--o{ reviews : receives
  users { bigint id PK
    string email UK
    string name
    string password_hash }
  instructor_profiles { bigint id PK
    bigint user_id FK
    string kyc_status
    string publishing_status }
  courses { bigint id PK
    bigint instructor_id FK
    bigint category_id FK
    string slug UK
    bigint price_idr
    string status }
  lessons { bigint id PK
    bigint course_section_id FK
    bigint media_asset_id FK
    string type
    int sort_order }
  enrollments { bigint id PK
    bigint student_id FK
    bigint course_id FK
    string status }
  quiz_attempts { bigint id PK
    bigint user_id FK
    bigint quiz_id FK
    int score
    string status }
  certificates { bigint id PK
    bigint enrollment_id FK
    string verification_code UK
    datetime issued_at }
```

Tables: `users`, `roles`, `model_has_roles` (if using permission package), `instructor_profiles`, `categories`, `courses`, `course_sections`, `lessons`, `media_assets`, `enrollments`, `lesson_progress`, `quizzes`, `questions`, `question_options`, `quiz_attempts`, `attempt_answers`, `certificates`, `live_sessions`, `reviews`, `wishlists`, `course_moderation_events`.

### Critical constraints

- `users.email` UNIQUE (normalize as appropriate).
- `instructor_profiles.user_id` UNIQUE.
- `courses.slug` UNIQUE; `(instructor_id, status)` index.
- `enrollments(student_id, course_id)` UNIQUE; never duplicate on retry.
- `lesson_progress(enrollment_id, lesson_id)` UNIQUE; validate lesson belongs to same course as enrollment.
- `certificates.enrollment_id` UNIQUE if exactly one certificate per completed course; `verification_code` UNIQUE.
- `reviews(user_id, course_id)` UNIQUE.
- `question_options.question_id` FK; correct answers **never delivered** in quiz payload before submission.
- Use checks (e.g. price >= 0, score between 0 and 100, commission bps between 0 and 10,000), explicit FKs and delete policies.
- Historical student progress and finance evidence require soft delete/archiving strategy rather than uncontrolled cascades.

## Commerce, commissions and settlements

```mermaid
erDiagram
  users ||--o{ orders : places
  orders ||--|{ order_items : has
  courses ||--o{ order_items : purchased
  orders ||--o{ payments : attempts
  payments ||--o{ payment_events : logs
  order_items ||--o{ revenue_allocations : allocates
  instructor_profiles ||--o| gateway_accounts : connects
  gateway_accounts ||--o{ payouts : receives
  payouts ||--|{ payout_items : groups
  revenue_allocations ||--o{ payout_items : settled_by
  payments ||--o{ refunds : may_refund
  refunds ||--o{ refund_allocations : reverses
  orders { bigint id PK
    bigint buyer_id FK
    string order_no UK
    bigint subtotal_idr
    bigint total_idr
    string status }
  order_items { bigint id PK
    bigint order_id FK
    bigint course_id FK
    bigint instructor_id FK
    bigint unit_price_idr
    int commission_bps }
  payments { bigint id PK
    bigint order_id FK
    string provider
    string provider_ref UK
    bigint amount_idr
    string status }
  payment_events { bigint id PK
    bigint payment_id FK
    string provider_event_id UK
    string event_type }
  revenue_allocations { bigint id PK
    bigint order_item_id FK
    bigint platform_share_idr
    bigint instructor_share_idr }
  gateway_accounts { bigint id PK
    bigint instructor_id FK
    string provider_account_id UK
    string kyc_status }
  payouts { bigint id PK
    bigint gateway_account_id FK
    bigint amount_idr
    string status }
```

Other finance tables: `commission_rules`, `payment_events`, `settlement_events`, `provider_reconciliation_runs`, `audit_logs`; merchant verification details should remain with provider whenever possible to minimize sensitive data retained locally.

### Finance invariants

- On each order item, store instructor ID, course title/price snapshot, gross price, discount (if later), commission bps, fixed fee policy snapshot and currency.
- `platform_share + instructor_share + allocated_fees = gross_paid` only when fee allocation policy makes this equation correct; define exact ledger model and never guess fee inclusions. Accounting adjustments and refunds should be append-only entries, not silent edits to history.
- `payment_events(provider, provider_event_id)` UNIQUE when provider supplies an event ID; otherwise construct stable idempotency key from verified event fields.
- `payout_items(payout_id, revenue_allocation_id)` must prevent the same payable allocation from being paid twice; partial payout/adjustments require explicit records.
- Use row-level locks and transactions for state transitions; webhook may race with redirect, polling and retries.

## Migration ordering

Identity → instructor/category/course → lesson/media → enrollment/progress → assessment → certification/live/reviews → orders/items → payments/events → allocations → payouts/refunds/audit. Seed only anonymous, clearly synthetic content.
