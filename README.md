# Course Marketplace — AI Agent Starter Pack

**Version:** 1.0 | **Date:** 2026-10-24 | **Status:** Planning baseline, not approved implementation requirements.

A repository-ready collection of Markdown instructions for **OpenAI Codex** and **Claude Code** developing an original Indonesian online-course marketplace. The business reference is professional training workflows (such as those publicly shown by Latih.co), while the desired browsing and learning experience is comparable in quality to Udemy — **without copying branding, assets, or layouts**.

## Quick start

1. Extract this package into the **root of the same Git repository** used by both developers. Keep `AGENTS.md` and `CLAUDE.md` in the root and `docs/` beside them.
2. Create Laravel 13 + Vue 3/TypeScript + Inertia 3 project **after** checking local tooling and versions; if the repository exists, audit it first and avoid overwriting.
3. For Codex: begin with `Read AGENTS.md and docs/00_PROJECT_BRIEF.md. Audit the repo, then propose Sprint 0 tasks. Do not overwrite existing work.`
4. For Claude Code: begin with `Read CLAUDE.md and docs/00_PROJECT_BRIEF.md. Audit the repo, then propose Sprint 0 tasks. Do not overwrite existing work.`
5. Use separate branches/worktrees per person. Finance and payment integration require explicit feasibility approval from the payment provider before going live.

## Document map

| Path | Content |
|---|---|
| `AGENTS.md` | Codex entrypoint and nonnegotiable engineering rules |
| `CLAUDE.md` | Claude Code entrypoint, same shared engineering rules |
| `docs/00_PROJECT_BRIEF.md` | Product goals, customer context and fixed decisions |
| `docs/01_PRD.md` | Roles, features and business requirements |
| `docs/02_MVP_SCOPE.md` | V1 boundaries and deferred features |
| `docs/03_ARCHITECTURE.md` | Stack, modules, implementation patterns |
| `docs/04_DATABASE_ERD.md` | Mermaid ERD, keys and constraints |
| `docs/05_ROUTES_AND_PERMISSIONS.md` | Route table and access policies |
| `docs/06_DESIGN_SYSTEM.md` | Design tokens, component rules, anti-AI-slop quality bar |
| `docs/07_UX_FLOWS.md` | Key journeys, screens and edge cases |
| `docs/08_PAYMENTS_AND_COMMISSION.md` | Marketplace ledger, gateways, refunds, payouts |
| `docs/09_SECURITY_AND_AUDIT.md` | Secure media, access controls, auditability |
| `docs/10_TESTING_AND_ACCEPTANCE.md` | Test strategy and verifiable Done criteria |
| `docs/11_SPRINT_PLAN.md` | Milestones, developer split, dependent tasks |
| `docs/12_DEV_WORKFLOW.md` | Git, PR, task templates and agent workflow |
| `docs/13_INFRA_AND_RAB_NOTES.md` | Sizing assumptions and budgeting boundaries |
| `docs/14_OPEN_DECISIONS.md` | Owner decisions and blockers before finance launch |
| `docs/REFERENCES.md` | Official documentation anchors |

## Priorities and precedence

1. Latest explicit product-owner decision in Git issues/approved PRD.
2. `docs/02_MVP_SCOPE.md` and `docs/14_OPEN_DECISIONS.md` for scope and open questions.
3. This package's technical and UX documents.
4. `AGENTS.md` and `CLAUDE.md` for agent behavior.

If two documents disagree, **flag the conflict instead of silently guessing**. These documents are design inputs; they do not prove any feature exists in the repository.

## Important budget distinction

**IDR 20,000,000** is the development fee stipulated in conversation; VPS, domain, media, gateway and third-party services are separately funded by the client. Do not bake infrastructure cost into developer fee or quote provider rates as confirmed costs.
