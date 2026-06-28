---
name: design-system-gofood-merchant-dashboard
description: Creates implementation-ready design-system guidance with tokens, component behavior, and accessibility standards. Use when creating or updating UI rules, component specifications, or design-system documentation.
---

<!-- TYPEUI_SH_MANAGED_START -->

# GoFood Merchant Dashboard

## Mission

Deliver implementation-ready design-system guidance for GoFood Merchant Dashboard that can be applied consistently across dashboard web app interfaces.

## Brand

- Product/brand: GoFood Merchant Dashboard
- URL: https://portal.gofoodmerchant.co.id/dashboard/goresto?date_range=this_week
- Audience: authenticated users and operators
- Product surface: dashboard web app

## Style Foundations

- Visual style: structured, accessible, implementation-first
- Main font style: `font.family.primary=Maison Neue`, `font.family.stack=Maison Neue, sans-serif`, `font.size.base=16px`, `font.weight.base=400`, `font.lineHeight.base=16px`
- Typography scale: `font.size.xs=12px`, `font.size.sm=13.33px`, `font.size.md=14px`, `font.size.lg=16px`, `font.size.xl=24px`
- Color palette: `color.surface.base=#000000`, `color.text.secondary=#8e919b`, `color.text.tertiary=#1b2842`, `color.text.inverse=#2d2e34`, `color.surface.muted=#ffffff`, `color.surface.raised=#ecf0f6`, `color.surface.strong=#00880d`
- Spacing scale: `space.1=8px`, `space.2=10px`, `space.3=12px`, `space.4=12.96px`, `space.5=13px`, `space.6=16px`, `space.7=20px`, `space.8=24px`
- Radius/shadow/motion tokens: `radius.xs=6px`, `radius.sm=9999px` | `shadow.1=rgba(0, 0, 0, 0.1) 0px 2px 10px 0px` | `motion.duration.instant=200ms`, `motion.duration.fast=300ms`

## Accessibility

- Target: WCAG 2.2 AA
- Keyboard-first interactions required.
- Focus-visible rules required.
- Contrast constraints required.

## Writing Tone

concise, confident, implementation-focused

## Rules: Do

- Use semantic tokens, not raw hex values in component guidance.
- Every component must define required states: default, hover, focus-visible, active, disabled, loading, error.
- Responsive behavior and edge-case handling should be specified for every component family.
- Accessibility acceptance criteria must be testable in implementation.

## Rules: Don't

- Do not allow low-contrast text or hidden focus indicators.
- Do not introduce one-off spacing or typography exceptions.
- Do not use ambiguous labels or non-descriptive actions.

## Guideline Authoring Workflow

1. Restate design intent in one sentence.
2. Define foundations and tokens.
3. Define component anatomy, variants, and interactions.
4. Add accessibility acceptance criteria.
5. Add anti-patterns and migration notes.
6. End with QA checklist.

## Required Output Structure

- Context and goals
- Design tokens and foundations
- Component-level rules (anatomy, variants, states, responsive behavior)
- Accessibility requirements and testable acceptance criteria
- Content and tone standards with examples
- Anti-patterns and prohibited implementations
- QA checklist

## Component Rule Expectations

- Include keyboard, pointer, and touch behavior.
- Include spacing and typography token requirements.
- Include long-content, overflow, and empty-state handling.

## Quality Gates

- Every non-negotiable rule must use "must".
- Every recommendation should use "should".
- Every accessibility rule must be testable in implementation.
- Prefer system consistency over local visual exceptions.

<!-- TYPEUI_SH_MANAGED_END -->
