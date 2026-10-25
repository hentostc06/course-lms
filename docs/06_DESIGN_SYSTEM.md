# 06 — Design System: Editorial Learning

## Design goal

Build a premium, original Indonesian course marketplace that feels **designed by a professional product team**, not an AI-template generator. Borrow established *usability patterns* (prominent search, scannable course cards, precise curriculum, strong reviews, predictable checkout, focused player) but do not copy Udemy/Latih.co intellectual property, branded assets, exact layouts or marketing copy.

## Visual character

- Credible training and professional education, content-first, no decorative noise.
- Compact information density, not oversized hero panels with too much whitespace.
- Focus on instructor credentials, course outcomes, practical skills, course preview and purchase confidence.
- Clean editorial composition, measured hierarchy, restrained transitions.

### Tokens (starting proposal; validate contrast and client brand)

```css
:root {
  --color-ink: #18211F;
  --color-canvas: #F8F7F3;
  --color-surface: #FFFFFF;
  --color-primary: #206B59;
  --color-primary-hover: #185444;
  --color-sage: #E7EBE5;
  --color-accent: #D99A43;
  --color-text-muted: #58645F;
  --color-border: #D9DFD9;
  --color-danger: #B42318;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 14px;
  --container-max: 1320px;
}
```

Typography: **Plus Jakarta Sans** or **Inter** UI; body 16 px (14 px only for compact metadata), 1.5 line-height, clear headline 28–48 px by breakpoint. Use 4 px base spacing scale. Desktop grid 12 columns; mobile 4. All important clickable targets >=44px unless smaller inline contexts remain comfortably operable. Test WCAG 2.2 AA contrast, keyboard focus, labels, reduced motion.

## Component inventory

`AppHeader`, `CourseSearch`, `CategoryNav`, `Breadcrumbs`, `CourseCard`, `InstructorBadge`, `CourseRating`, `PriceLabel`, `CourseOutline`, `CurriculumAccordion`, `CoursePreview`, `StickyPurchasePanel`, `CartItem`, `CheckoutSummary`, `LearningPlayerShell`, `LessonSidebar`, `LessonRow`, `ProgressIndicator`, `QuizQuestion`, `QuizResult`, `CertificatePreview`, `InstructorCourseEditor`, `UploadStatus`, `CoursePublishChecklist`, `RevenueSummary`, `EmptyState`, `LoadingSkeleton`, `ErrorState`, `Toast`, `ConfirmDialog`, `DataTable`, `Pagination`.

Each component must document: props, interaction states, mobile behavior, accessibility and where used. Use shadcn-vue as foundation but customize styles and reduce generic styling. Build **two distinct products** under one system: marketing/storefront + focused learning/studio.

## Required layouts

### Home

Header with search and categories; concise hero showing primary benefit (not empty slogans), curated/best-selling/relevant course grids, category browsing, trust indicators based on real data, CTA to become instructor, compact footer. No fake course counts or ratings.

### Catalog

Search context visible, filters (category, difficulty, price), sorting; card thumbnail 16:9, max 2-line title, real instructor, valid rating only, duration, price. Filters in mobile drawer; keyboard accessible. Show empty results with clear reset action.

### Course detail

Title, truthful learning outcomes, instructor, review count, course preview, price/CTA, sticky purchase card where appropriate, syllabus, requirements, audience, updated date, refund policy. On mobile place CTA in visible usable location, never block content.

### Learning player

Video/document main content + collapsible curriculum sidebar; lesson completion, progress, quiz access, upcoming live session. Keep distractions and shopping suggestions out of learning view. Mobile curriculum drawer. Ensure playback errors and processing states are understandable.

### Instructor studio

Clear information architecture: Overview → My Courses → Edit Curriculum/Media/Assessment → Publish Checklist → Sales/Earnings → Payout Status. Use step-based editing only where justified. Incomplete course checklist must explain what is missing.

### Admin

Filament with functional tables, filters, audit trail and approval reason fields. Do not force consumer storefront visuals onto back-office.

## Motion specification

Use motion only for focus and feedback: hover/focus 120–180ms, dialogs/drawers 160–240ms, page transitions modest or absent. Honor `prefers-reduced-motion`. No unnecessary parallax, floating backgrounds, 3D objects, infinite marquee, scroll-jacking, bouncy CTA or oversized gradients.

## Anti-AI-slop acceptance gate

Reject if any of these appear without a concrete user need:

- Similar-looking giant rounded cards everywhere; unrelated pastel gradients and glow.
- Generic hero containing only “Empower Your Learning Journey” plus stock AI-generated illustration.
- Fake learner names, fake badges, made-up ratings or certification claims.
- Empty analytics charts simply to fill dashboard space.
- Excessive decorative iconography, unstructured spacing, inconsistent border radii.
- Key course information below oversized marketing sections.

## Deliverables for design sign-off

Figma-style design tokens, responsive high-fidelity screens for Home, Catalog, Course Detail, Cart/Checkout, Learning Player, Instructor Studio and Admin overview; prototype of navigation/checkout/learning; component usage list. Client approves wireframes before high-cost implementation. Do not generate imagery unless assets are approved or use licensed/demo placeholders explicitly marked.
