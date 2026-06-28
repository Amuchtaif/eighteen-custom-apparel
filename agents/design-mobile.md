---
name: Eighteen Industrial ERP
colors:
    surface: "#f7f9fb"
    surface-dim: "#d8dadc"
    surface-bright: "#f7f9fb"
    surface-container-lowest: "#ffffff"
    surface-container-low: "#f2f4f6"
    surface-container: "#eceef0"
    surface-container-high: "#e6e8ea"
    surface-container-highest: "#e0e3e5"
    on-surface: "#191c1e"
    on-surface-variant: "#45474c"
    inverse-surface: "#2d3133"
    inverse-on-surface: "#eff1f3"
    outline: "#75777d"
    outline-variant: "#c5c6cd"
    surface-tint: "#545f73"
    primary: "#091426"
    on-primary: "#ffffff"
    primary-container: "#1e293b"
    on-primary-container: "#8590a6"
    inverse-primary: "#bcc7de"
    secondary: "#0058be"
    on-secondary: "#ffffff"
    secondary-container: "#2170e4"
    on-secondary-container: "#fefcff"
    tertiary: "#00190e"
    on-tertiary: "#ffffff"
    tertiary-container: "#00301e"
    on-tertiary-container: "#00a472"
    error: "#ba1a1a"
    on-error: "#ffffff"
    error-container: "#ffdad6"
    on-error-container: "#93000a"
    primary-fixed: "#d8e3fb"
    primary-fixed-dim: "#bcc7de"
    on-primary-fixed: "#111c2d"
    on-primary-fixed-variant: "#3c475a"
    secondary-fixed: "#d8e2ff"
    secondary-fixed-dim: "#adc6ff"
    on-secondary-fixed: "#001a42"
    on-secondary-fixed-variant: "#004395"
    tertiary-fixed: "#6ffbbe"
    tertiary-fixed-dim: "#4edea3"
    on-tertiary-fixed: "#002113"
    on-tertiary-fixed-variant: "#005236"
    background: "#f7f9fb"
    on-background: "#191c1e"
    surface-variant: "#e0e3e5"
typography:
    display-lg:
        fontFamily: Inter
        fontSize: 32px
        fontWeight: "700"
        lineHeight: 40px
        letterSpacing: -0.02em
    headline-md:
        fontFamily: Inter
        fontSize: 24px
        fontWeight: "600"
        lineHeight: 32px
        letterSpacing: -0.01em
    headline-sm:
        fontFamily: Inter
        fontSize: 20px
        fontWeight: "600"
        lineHeight: 28px
    body-lg:
        fontFamily: Inter
        fontSize: 16px
        fontWeight: "400"
        lineHeight: 24px
    body-md:
        fontFamily: Inter
        fontSize: 14px
        fontWeight: "400"
        lineHeight: 20px
    body-sm:
        fontFamily: Inter
        fontSize: 12px
        fontWeight: "400"
        lineHeight: 16px
    label-caps:
        fontFamily: Inter
        fontSize: 11px
        fontWeight: "700"
        lineHeight: 16px
        letterSpacing: 0.05em
    status-code:
        fontFamily: JetBrains Mono
        fontSize: 12px
        fontWeight: "500"
        lineHeight: 16px
rounded:
    sm: 0.125rem
    DEFAULT: 0.25rem
    md: 0.375rem
    lg: 0.5rem
    xl: 0.75rem
    full: 9999px
spacing:
    base: 4px
    xs: 4px
    sm: 8px
    md: 16px
    lg: 24px
    xl: 32px
    gutter: 12px
    margin-mobile: 16px
    margin-desktop: 32px
---

## Brand & Style

The design system is engineered for the high-velocity environment of custom apparel manufacturing. It prioritizes utility, clarity, and reliability, moving away from decorative elements toward a functional aesthetic that reflects industrial precision.

The style is **Corporate Modern with a Data-Centric focus**. It utilizes a structured, card-based architecture to organize complex workflows—from order intake to the factory floor. The visual language conveys a sense of "controlled movement," ensuring that production managers and floor staff can identify bottlenecks and status changes at a glance. Efficiency is the primary driver, achieved through high-density information layouts and a clear visual hierarchy that emphasizes action over ornamentation.

## Colors

The palette is rooted in "Industrial Navy" to establish trust and authority.

- **Primary (#1E293B):** Used for navigation, headers, and core structural elements. It provides a grounded, professional foundation.
- **Action Blue (#3B82F6):** Reserved strictly for interactive elements like primary buttons, links, and active selection states.
- **Production Green (#10B981):** Represents the "Go" state—completed orders, successful QC, and healthy inventory levels.
- **Alert Tones:** Warning Amber (#F59E0B) flags production delays or looming deadlines, while Error Red (#EF4444) highlights immediate blockers or failed quality checks.
- **Surface Neutrals:** A range of cool grays (starting at #F8FAFC) is used to differentiate between background layers and interactive card surfaces.

## Typography

This design system employs **Inter** for its exceptional legibility in data-heavy interfaces. It features a tall x-height which aids readability on mobile devices on the shop floor.

- **Headlines:** Use tighter letter spacing and semi-bold weights to create a strong visual anchor for page sections.
- **Data Tables & Labels:** The system introduces **JetBrains Mono** for specific technical strings like SKU numbers, Batch IDs, and machine codes to prevent character confusion (e.g., '1', 'l', and 'I').
- **Mobile Scaling:** On mobile devices, `display-lg` should scale down to 24px to ensure headers do not push critical production data "below the fold."

## Layout & Spacing

The layout follows a **Mobile-First, Fluid Grid** philosophy. Since apparel managers are often on the move, the UI must prioritize a single-column stack for mobile that expands into a multi-column dashboard on desktop.

- **The 4px Rule:** All spacing (padding, margins, heights) must be a multiple of 4px to maintain a rhythmic, systematic density.
- **Density:** Use "Compact" spacing (8px internal padding) for data grids and "Comfortable" spacing (16px internal padding) for detail views and forms.
- **Grid:** A 12-column grid is used for desktop (breakpoint 1024px+). On mobile, a single column with 16px side margins is standard.

## Elevation & Depth

Elevation in the design system is used functionally to indicate interactivity and information layering rather than purely for aesthetics.

- **Surface Tiers:** The base background is the lowest tier (#F8FAFC). Content cards sit one level above on a pure white (#FFFFFF) background.
- **Subtle Borders:** Every card and input uses a 1px border (#E2E8F0) to ensure definition in high-glare environments.
- **Soft Shadows:** Shadows are reserved for floating elements (modals, dropdowns) or to indicate that a production card is "draggable" in a Kanban view. Use a Y-offset of 2px, a blur of 4px, and 5% black opacity for a natural, grounded look.

## Shapes

The shape language is **Soft yet Structured**. By using a 0.25rem (4px) base radius, the UI feels modern without losing the "industrial" sharpness required for an ERP.

- **Standard Elements:** Buttons, inputs, and small cards use the 4px radius.
- **Large Containers:** Dashboard widgets and main content areas use an 8px (rounded-lg) radius to create a distinct container hierarchy.
- **Status Pills:** Badges for "Printing" or "Sewing" use a fully rounded (pill) shape to distinguish them instantly from clickable square buttons.

## Components

- **Buttons:** Primary buttons are Action Blue (#3B82F6) with white text. Secondary buttons use a white fill with a 1px border of Primary Navy.
- **Status Badges:** These are critical. Use a "Soft Fill" style: a 10% opacity background of the state color (e.g., Green for success) with a 100% opacity bold text of the same color.
- **Data Cards:** Every order card must include a clear header (Order #), a status badge in the top right, and a "Progress Bar" at the bottom to show production completion.
- **Input Fields:** Use a 40px minimum height for touch targets on mobile. Labels must always be visible (no floating labels that disappear) to ensure the user never loses context during data entry.
- **Lists:** Use "Zebra Striping" (alternating light gray backgrounds) for long inventory lists to help the eye track across rows on wide screens.
