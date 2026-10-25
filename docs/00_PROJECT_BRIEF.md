# 00 — Project Brief

**Working title:** Course Marketplace (brand name TBD)
**Date:** 2026-10-24 | **Market:** Indonesia | **Initial target:** 100–500 registered users (not concurrent)
**Team:** Two full-stack developers, AI-assisted with Codex and Claude Code.
**Commercial:** Development fee IDR 20,000,000; infrastructure and third-party running costs are separate client budgets.

## Product intent

Build a real course/training marketplace where multiple instructors can apply, build and publish courses, learners can purchase and complete training, and the platform earns commission. The client has a professional/auditing background; emphasize traceable evidence of training, quizzes, attendance records where feasible, completion criteria and verifiable certificates.

**Feature benchmark:** Public training journeys such as Latih.co (registration, training/webinars, meetings, learning materials, feedback, completion and certificates) and established course marketplaces. Benchmark does **not** authorize a visual clone of Latih.co or Udemy; deliver a high-quality, original UX.

## Nonnegotiable product qualities

1. Trustworthy payments and correct instructor revenue allocation.
2. Clear course discovery, high-performance checkout, distraction-free learning player.
3. Instructor course builder with publishing moderation.
4. Evidence-ready completion, scoring and certificate verification.
5. Secure student records, reliable backups, observable operations.
6. Polish and usability over decorative effects or speculative features.

## Core roles

- **Visitor:** browse/search/view previews; sign up.
- **Student:** cart/checkout, enrollment, learn, quiz, live sessions, feedback, certificates.
- **Instructor:** onboard/KYC through provider, create/edit/submit courses, monitor students and finances (authorized aggregate only).
- **Admin:** moderate instructors/courses, view transactions, configure categories/commission, resolve refund cases and audit activities.

A single `users` identity can carry multiple roles. One instructor owns each course in V1.

## Technology decisions

Laravel 13 + Vue 3/TypeScript + Inertia 3 + Tailwind CSS 4 + Filament 5 (admin only) + PostgreSQL + Redis. Documents and pictures in object storage; adaptive video via streaming service; live training linked to provider; marketplace payment provider must pass feasibility gate. Separate live/staging environments and offsite backup.

## Assumptions — subject to owner sign-off

- Bahasa Indonesia default; IDR only; timezone Asia/Jakarta for UI, timestamps stored in UTC.
- Buy-once per course; no subscriptions in V1.
- Basic multiple-choice quizzes, optional passing threshold, certificate upon defined learning/assessment rules.
- Instructor KYC and payout eligibility managed by payment provider where supported.
- V1 checkout initially limited to cart items owned by one instructor per payment unless multi-recipient checkout is demonstrated end-to-end.
- Live class scheduling and authenticated join link, **not** internally hosted video conferencing.

## Exit criterion

An approved instructor can publish a course, an end user can pay via verified flow, enroll, learn, pass assessment, obtain a verifiable certificate, and the platform can reconcile the instructor share against provider settlement. See `10_TESTING_AND_ACCEPTANCE.md`.
