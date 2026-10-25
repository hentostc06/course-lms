# 05 — Route Map and Authorization

Routes below are **planned** and may be adjusted when Laravel starter kit conventions are inspected. Use named routes consistently; Inertia for pages and JSON for narrowly scoped endpoints. `webhooks/*` use provider authentication rather than user sessions.

## Public pages

| Method | Path | Access | Purpose |
|---|---|---|---|
| GET | `/` | Public | Homepage |
| GET | `/courses` | Public | Catalog, query filters |
| GET | `/courses/{slug}` | Public | Published course detail |
| GET | `/categories/{slug}` | Public | Course category |
| GET | `/search` | Public | Search |
| GET | `/instructors/{username}` | Public | Approved public instructor profile |
| GET | `/teach` | Public | Instructor application information |
| GET | `/certificates/verify/{code}` | Public | Minimal validity proof; no unnecessary personal data |
| GET | `/login`, `/register` | Guest | Laravel auth |

## Authenticated student/commerce

| Method | Path | Access |
|---|---|---|
| GET | `/my-learning` | Student |
| GET | `/learn/{course}/{lesson}` | Active enrollment or preview |
| PUT | `/learn/{lesson}/progress` | Authorized student |
| POST | `/quizzes/{quiz}/attempts` | Enrolled student |
| POST | `/quiz-attempts/{attempt}/submit` | Attempt owner, active attempt |
| GET | `/my-certificates` | Student |
| GET | `/wishlist` | Student |
| POST | `/wishlist/{course}` | Student |
| GET | `/cart` | Authenticated shopper |
| POST | `/cart/items` | Authenticated shopper |
| DELETE | `/cart/items/{id}` | Cart owner |
| POST | `/checkout` | Cart owner |
| GET | `/orders` | Buyer |
| GET | `/orders/{order}` | Order buyer only |
| GET | `/account` | Account owner |

## Instructor studio

All instructor routes require authenticated instructor role and resource policy; do **not** trust `instructor_id` in request body.

| Method | Path | Purpose |
|---|---|---|
| GET/POST | `/instructor/onboarding` | Profile, application, provider onboarding |
| GET | `/instructor` | Overview |
| GET/POST | `/instructor/courses` | List/new course |
| GET/PATCH | `/instructor/courses/{course}/edit` | Edit owned draft |
| GET/POST | `/instructor/courses/{course}/curriculum` | Curriculum builder |
| POST | `/instructor/courses/{course}/submit` | Publish-review request |
| GET | `/instructor/courses/{course}/students` | Own course students |
| GET | `/instructor/earnings` | Own ledger summary |
| GET | `/instructor/payouts` | Own payout statuses |
| GET/POST | `/instructor/live-sessions` | Course-owned sessions |

## Admin and integrations

| Method | Path | Access |
|---|---|---|
| GET | `/admin/*` | Verified Filament admins |
| POST | `/webhooks/payments/{provider}` | Provider-verified request |
| POST | `/webhooks/media/{provider}` | Media-provider-verified request |
| POST | `/api/media/direct-upload` | Authorized instructor and owned draft |
| POST | `/api/lessons/{lesson}/playback-token` | Preview/enrollment policy |
| GET | `/health` | Restricted, minimal metadata |

## Policy matrix

| Action | Visitor | Student | Instructor | Admin |
|---|:---:|:---:|:---:|:---:|
| Browse published course | Yes | Yes | Yes | Yes |
| Access paid lesson | No | If enrolled | If owner / enrolled | Authorized support only |
| Submit quiz | No | If enrolled | If enrolled | No by default |
| Create course | No | No | Verified and active | Operational override with reason |
| Edit course | No | No | Own editable course | Moderation control |
| Approve course | No | No | No | Yes |
| Read instructor revenue | No | No | Own only | Privileged finance admin |
| View payment events | No | Own order status only | Aggregates only | Privileged finance admin |
| Request payout | No | No | Provider-eligible own account only | Per approved policy |

All authorization checked **in Laravel**; hiding buttons in Vue is supplementary only. Include denial tests for guessed IDs and cross-tenant access.
