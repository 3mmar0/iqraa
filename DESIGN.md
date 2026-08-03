---
name: يَطْمَئِن
description: Arabic RTL learning platform — reading-room calm with official palette
colors:
  primary: "#2A9D8F"
  primary-hover: "#238B7F"
  primary-light: "#DDEEEB"
  secondary: "#4F8FBF"
  secondary-hover: "#3D7AAB"
  secondary-light: "#E8F1F8"
  sage: "#A8C3A0"
  sage-light: "#E8F0E6"
  ink: "#2F3A45"
  sand: "#F4F6F8"
  surface: "#ffffff"
  line: "#E2E8ED"
  text-secondary: "#5A6772"
  muted: "#8B97A3"
typography:
  display:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(2.5rem, 7vw, 4.5rem)"
    fontWeight: 800
    lineHeight: 1.1
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.7
    letterSpacing: "normal"
  title:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.5rem, 3vw, 2.25rem)"
    fontWeight: 700
    lineHeight: 1.3
rounded:
  sm: "12px"
  md: "16px"
  lg: "24px"
spacing:
  band: "32px"
  section: "80px"
  gutter: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.lg}"
    padding: "14px 24px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
    textColor: "#ffffff"
  button-secondary:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.secondary}"
    rounded: "{rounded.lg}"
    padding: "14px 24px"
  link:
    textColor: "{colors.secondary}"
  course-tile:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.ink}"
    rounded: "{rounded.md}"
---

# Design System — يَطْمَئِن

## Overview

**Reading Room** public identity: calm library atmosphere, shelf-like course catalogs, and deep section rhythm — painted only with the official palette. Arabic RTL. Persuade mode on marketing pages; dashboards stay Operate with the same tokens.

## Colors

| Role | Hex | Use |
|------|-----|-----|
| Primary Teal | `#2A9D8F` | Primary actions |
| Soft Teal | `#DDEEEB` | Soft fills, hero wash |
| Secondary Blue | `#4F8FBF` | Links, secondary buttons |
| Sage | `#A8C3A0` | Supportive highlights |
| Light | `#F4F6F8` | Section grounds |
| Dark Text | `#2F3A45` | Type and dark bands |

No purple washes. Gradients only as soft photo overlays for readability.

## Typography

Tajawal exclusively on public chrome. Brand display is large in heroes (up to ~4.5rem). Section titles step clearly above body.

## Layout

- Home first viewport: full-bleed reading-room photograph, soft light wash, brand + headline + CTAs.
- Below: shelf catalog, path grid, Arabic-student band, post-approval stations, instructors, FAQ, closing CTA.
- Sibling public pages share soft page headers (`sand` / `primary-light`) and `rounded-2xl` surfaces.
- Max width `max-w-6xl`; generous section padding (~80px).

## Elevation & Depth

Soft offset shadows on interactive course tiles and the contact form. No glow orbs.

## Shapes

Soft radii 12–24px. Sage top border accent on course tiles (spine metaphor).

## Components

- Primary / secondary buttons as in tokens.
- Course tile = interaction card with cover + sage spine edge.
- FAQ = rounded accordion with soft teal expand control.
- Instructors = soft tiles with blue monogram.

## Do's and Don'ts

**Do** keep the reading-room calm; use teal for the one main action; expand sections with real product stations.

**Don't** invent testimonials; use pure black; restore indigo SaaS heroes; stack same-size icon+text cards as the page scaffold.
