# 12 — Two-Developer Workflow with Codex and Claude Code

## One repo, one documented source of truth

Use GitHub or equivalent Git host; `main` protected, merge via pull request, CI required. Maintain tickets and architectural decision records (`docs/decisions/ADR-xxxx.md` when needed). `AGENTS.md` and `CLAUDE.md` point to the same docs and never contain contradictory instructions.

## Branch and task rules

- `feat/catalog-search`, `feat/course-builder`, `feat/payment-webhook`, `fix/enrollment-idempotency`.
- Each developer uses **separate local clone or git worktree**; no shared working directory used simultaneously by both agents.
- One ticket must describe actor, motivation, constraints, UI state, backend behavior, data changes, acceptance tests and out-of-scope.
- Each PR references issue, owner, migration impact, screenshot/video of visual change, test command outputs, security and provider impact.
- Shared domain model/migration conflicts must be merged sequentially with both developer reviews.
- CI checks: Composer validation, PHP formatter/linter, static checks if configured, PHP tests, TypeScript typecheck, frontend lint/test and build. Do not fabricate green results.

## Agent prompt template

```text
Read AGENTS.md (or CLAUDE.md) and docs relevant to ticket [ISSUE-ID].
First inspect existing code and git status; summarize approach in up to 8 bullets.
Implement ONLY [FEATURE], following design tokens, policies, finance invariants and repo conventions.
Acceptance criteria:
- [criterion 1]
- [criterion 2]
- [negative case/security test]
Do not alter production, secrets, other features or database outside necessary migrations.
Run applicable tests and report exact results; report changed files, risks and follow-ups.
```

## Code review rules

Human reviewer checks business logic and UX in addition to diff quality. Ask second agent to review without modifying files (read-only review), then have owner implement accepted feedback. NEVER treat two agreeing AI answers as evidence that payment or legal process is correct.

## Agent responsibility boundaries

| Work | Codex / Claude | Human owner |
|---|---|---|
| Generate components/migrations/tests | Can propose and implement assigned ticket | Review and accept |
| Determine fee rate, refund policy, tax | Can identify options and implications | Client decides |
| Approve payout provider and KYC arrangement | Can research docs | Client/provider approves |
| Deploy production or migrate irreversible data | No autonomous action | Explicit human approval |
| Design signoff | Can generate prototypes and critique | Human evaluates anti-slop quality |

## First agent commands (example prompts, not shell commands)

**Codex:** `Read AGENTS.md, README.md and docs/11_SPRINT_PLAN.md. Audit existing repository and propose S0-02 provider feasibility task. Do not write payment integration until provider eligibility is verified.`

**Claude Code:** `Read CLAUDE.md, README.md and docs/06_DESIGN_SYSTEM.md. Audit existing repository and draft a responsive component inventory and wireframe implementation plan. Do not overwrite current frontend.`
