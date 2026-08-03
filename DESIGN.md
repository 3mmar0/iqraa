---
name: يَطْمَئِن
description: Arabic RTL learning platform — calm catalog-forward marketing
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
    fontSize: "clamp(1.875rem, 4vw, 3rem)"
    fontWeight: 800
    lineHeight: 1.15
    letterSpacing: "-0.02em"
  body:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.7
    letterSpacing: "normal"
  title:
    fontFamily: "Tajawal, ui-sans-serif, system-ui, sans-serif"
    fontSize: "clamp(1.25rem, 2vw, 1.875rem)"
    fontWeight: 700
    lineHeight: 1.35
rounded:
  sm: "12px"
  md: "16px"
  lg: "20px"
spacing:
  band: "24px"
  section: "56px"
  gutter: "24px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "#ffffff"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  button-primary-hover:
    backgroundColor: "{colors.primary-hover}"
    textColor: "#ffffff"
  button-secondary:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.secondary}"
    rounded: "{rounded.sm}"
    padding: "12px 20px"
  link:
    textColor: "{colors.secondary}"
  course-tile:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.ink}"
    rounded: "{rounded.md}"
---

# Design System — يَطْمَئِن

## Overview

Calm, comfortable, educational public UI: catalog-forward home, soft rounded controls, and a restrained palette. Arabic RTL throughout. Official brand colors are binding across public and shared tokens.

## Colors

| Role | Hex | Use |
|------|-----|-----|
| Primary Teal | `#2A9D8F` | Brand actions, primary buttons |
| Soft Teal | `#DDEEEB` | Hover grounds, soft fills |
| Secondary Blue | `#4F8FBF` | Secondary buttons, text links |
| Sage Green | `#A8C3A0` | Supportive highlights, step labels |
| Light Background | `#F4F6F8` | Page sections / card grounds |
| Dark Text | `#2F3A45` | Body and headings (never pure black) |

Avoid loud gradients and overly bright accents. Prefer flat fills and soft teal/blue washes.

## Typography

- **Tajawal** for display and UI (`.site-brand` at weight 800).
- Body and titles use Dark Text; secondary copy uses `#5A6772`.

## Layout

- `max-w-6xl` content width.
- Home: compact brand band → course grid as proof.
- Alternate white / light-background sections; instructors as list rows.

## Elevation & Depth

- Soft offset shadows on interactive course tiles only.
- No glow, glass, or neon effects.

## Shapes

- Soft rounded UI: ~12–16px on buttons and tiles (`rounded-xl` / `rounded-2xl`).

## Components

- **Primary button:** teal fill, white label, soft radius.
- **Secondary button / outline:** blue border and label, light fill on hover.
- **Text links:** secondary blue.
- **Supportive chips / step labels:** sage.
- **Course tile:** light surface card as interaction container with cover image.

## Do's and Don'ts

**Do**

- Use teal for the main action on each screen.
- Use blue for secondary actions and links.
- Keep backgrounds light and text Dark Text.

**Don't**

- Use pure black text or neon accents.
- Reintroduce purple/indigo marketing washes.
- Overuse gradients or high-chroma fills.
