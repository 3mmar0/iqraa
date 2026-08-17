---
name: يَطْمَئِن
description: Arabic RTL learning platform — Academy Night Court (ink + brass)
colors:
  primary: "#C4A35A"
  primary-hover: "#8C6E2F"
  primary-light: "#F0E6CE"
  secondary: "#8C6E2F"
  secondary-hover: "#6B5424"
  secondary-light: "#EDE7DC"
  ink: "#161A1E"
  ink-text: "#1E2428"
  sand: "#EDE7DC"
  surface: "#F7F3EA"
  line: "#D4CBB8"
  text-secondary: "#4A4540"
  muted: "#6B6458"
  success: "#2A7A6E"
typography:
  display:
    fontFamily: "Amiri, Tajawal, ui-serif, serif"
    fontWeight: 700
  body:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontWeight: 400
    lineHeight: 1.7
  ui:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
rounded:
  sm: "12px"
  md: "14px"
  lg: "16px"
spacing:
  band: "32px"
  section: "80px"
  gutter: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.ink}"
    rounded: "{rounded.lg}"
  course-card:
    backgroundColor: "{colors.surface}"
    borderColor: "{colors.line}"
    statusChip: "{colors.success}"
  header:
    backgroundColor: "{colors.ink}"
    accentLine: "{colors.primary}"
---

# Design System — يَطْمَئِن (Academy Night Court)

## Overview

**Academy Night Court** replaces the prior Reading Room identity. Public surfaces follow Al-Borhan Academy information architecture (ceremonial header, program tracks, rich course cards, filtered catalog) with higher craft: night ink chrome, brass metal accents, limestone grounds. Dashboards inherit the same tokens in Operate mode.

## Colors

| Role | Hex | Use |
|------|-----|-----|
| Night Ink | `#161A1E` | Header, footer, sidebar, hero ground |
| Limestone | `#EDE7DC` | Page ground (`--color-sand`) |
| Parchment | `#F7F3EA` | Cards, panels (`--color-surface`) |
| Brass | `#C4A35A` | Primary actions, active nav, accents |
| Brass Deep | `#8C6E2F` | Hover, secondary emphasis |
| Ink Text | `#1E2428` | Body copy |
| Muted | `#6B6458` | Secondary text |
| Success Teal | `#2A7A6E` | Status chips only |

Brass primary buttons use **dark ink text**, not white.

## Typography

- **Tajawal** — all UI, forms, dashboards, nav labels
- **Amiri** — ceremonial display only: `.academy-display`, `.site-brand`, page heroes

## Layout — Public

- Sticky night header with brass hairline bottom border
- Home: ink hero → program track tiles (categories) → available course cards → path → instructors → FAQ → ink CTA
- Catalog: sticky category rail + search/sort toolbar + card grid
- Course detail: cover + status + instructor meta + sticky enrollment aside
- Four-column academy footer on ink

## Layout — Dashboards

- Night ink sidebar, brass active nav pill
- Limestone main canvas, parchment panels
- Shared `.admin-*` primitives use design tokens (no slate palette)

## Components

| Component | Path | Notes |
|-----------|------|-------|
| Course card | `components/course-card.blade.php` | Status, category, instructor, lessons, CTA |
| Program track | `components/program-track.blade.php` | Category tile linking to filtered catalog |
| Page hero | `components/public-page-hero.blade.php` | Light or dark (`dark` prop) |
| Academy chrome | `layouts/app.blade.php` | Header + 4-col footer |

## CSS Classes

- `.academy-header`, `.academy-hero`, `.academy-card`, `.academy-track`
- `.academy-btn-primary`, `.academy-btn-secondary`
- `.academy-catalog-rail`, `.academy-catalog-link`
- `.academy-status` — success teal chip "متاح الآن"

## Do's and Don'ts

**Do** use real category/course/enrollment data; keep enrollment-request flow; maintain RTL Arabic throughout.

**Don't** copy Al-Borhan logo/name; invent student counts; expose draft courses as "قريباً"; revert to teal reading-room as primary brand.
