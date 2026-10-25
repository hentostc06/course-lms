# Codex skills pack — Premium Course Marketplace

Installation target: project-local `.agents/skills/` inside `/var/www/projects/course`.

## Includes
- `premium-course-ui` — Screen design, hierarchy, premium non-generic visual direction.
- `vue-interface-craft` — Vue 3/Inertia/Tailwind implementation quality.
- `visual-qa` — Desktop/mobile visual acceptance audit.
- `training-audit-ux` — Evidence-oriented professional training flows.

## Install
1. Download ZIP, copy to `/var/www/projects/course/`.
2. Open terminal there, then run: `unzip -n Codex_Premium_Course_Skills.zip -d .`
3. Check `find .agents/skills -name SKILL.md -print`.
4. Restart Codex if currently open.
5. Inside Codex run `/skills`, or explicitly mention `$premium-course-ui` and other names in task prompts.

These skills do not overwrite `AGENTS.md`, `CLAUDE.md`, `docs/`, Laravel code, PHP dependencies, or node_modules. Existing files are not overwritten if using `unzip -n`.

## Example instructions
`$premium-course-ui Audit the existing course catalog UX, propose two original layout directions, pick a direction grounded in docs/06_DESIGN_SYSTEM.md, then implement only after the current task explicitly allows code changes.`

`$vue-interface-craft Implement the approved Course Detail page in Vue 3 + Inertia while preserving current routes, types and design tokens. Run the existing safe tests.`

`$visual-qa Inspect desktop and mobile screenshots of catalog, course detail, learning player and checkout. Rank problems by P0-P3 and fix P1/P2 only if requested.`

`$training-audit-ux Define the webinar attendance, quiz pass score, training completion and certificate verification states, with evidence provenance.`

## Public skills (optional; install separately)
- Anthropic `frontend-design` (distinctive frontend aesthetics)
- Vercel `web-design-guidelines` (UI and accessibility audit)

Use `npx skills add anthropics/skills --skill frontend-design --agent codex` and `npx skills add vercel-labs/agent-skills --skill web-design-guidelines --agent codex`. Review third-party skill files before installing. No global flag means project-scoped installation by default.
