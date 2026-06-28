# GoFood Merchant Dashboard

## Mission

Create implementation-ready, token-driven UI guidance for GoFood Merchant Dashboard that is optimized for consistency, accessibility, and fast delivery across dashboard web app.

## Brand

- Product/brand: GoFood Merchant Dashboard
- URL: https://portal.gofoodmerchant.co.id/analytics/sales-gofood?date_range=this_week
- Audience: authenticated users and operators
- Product surface: dashboard web app

## Style Foundations

- Visual style: clean, functional, implementation-oriented
- Main font style: `font.family.primary=Maison Neue`, `font.family.stack=Maison Neue, Helvetica, Open Sans, sans-serif`, `font.size.base=16px`, `font.weight.base=600`, `font.lineHeight.base=24px`
- Typography scale: `font.size.xs=12px`, `font.size.sm=13.33px`, `font.size.md=14px`, `font.size.lg=16px`, `font.size.xl=24px`
- Color palette: `color.text.primary=#2d2e34`, `color.surface.base=#000000`, `color.text.tertiary=#8e919b`, `color.text.inverse=#1b2842`, `color.surface.muted=#ffffff`, `color.surface.raised=#00880d`, `color.surface.strong=#ecf0f6`
- Spacing scale: `space.1=4px`, `space.2=5px`, `space.3=8px`, `space.4=9px`, `space.5=10px`, `space.6=12px`, `space.7=12.96px`, `space.8=13px`
- Radius/shadow/motion tokens: `radius.xs=6px`, `radius.sm=8px`, `radius.md=9999px` | `shadow.1=rgba(0, 0, 0, 0.1) 0px 2px 10px 0px` | `motion.duration.instant=200ms`, `motion.duration.fast=300ms`

## Accessibility

- Target: WCAG 2.2 AA
- Keyboard-first interactions required.
- Focus-visible rules required.
- Contrast constraints required.

## Writing Tone

Concise, confident, implementation-focused.

## Rules: Do

- Use semantic tokens, not raw hex values, in component guidance.
- Every component must define states for default, hover, focus-visible, active, disabled, loading, and error.
- Component behavior should specify responsive and edge-case handling.
- Interactive components must document keyboard, pointer, and touch behavior.
- Accessibility acceptance criteria must be testable in implementation.

## Rules: Don't

- Do not allow low-contrast text or hidden focus indicators.
- Do not introduce one-off spacing or typography exceptions.
- Do not use ambiguous labels or non-descriptive actions.
- Do not ship component guidance without explicit state rules.

## Guideline Authoring Workflow

1. Restate design intent in one sentence.
2. Define foundations and semantic tokens.
3. Define component anatomy, variants, interactions, and state behavior.
4. Add accessibility acceptance criteria with pass/fail checks.
5. Add anti-patterns, migration notes, and edge-case handling.
6. End with a QA checklist.

## Required Output Structure

- Context and goals.
- Design tokens and foundations.
- Component-level rules (anatomy, variants, states, responsive behavior).
- Accessibility requirements and testable acceptance criteria.
- Content and tone standards with examples.
- Anti-patterns and prohibited implementations.
- QA checklist.

## Component Rule Expectations

- Include keyboard, pointer, and touch behavior.
- Include spacing and typography token requirements.
- Include long-content, overflow, and empty-state handling.
- Include known page component density: buttons (24), links (13), lists (11), navigation (2), tables (2).

## Quality Gates

- Every non-negotiable rule must use "must".
- Every recommendation should use "should".
- Every accessibility rule must be testable in implementation.
- Teams should prefer system consistency over local visual exceptions.
