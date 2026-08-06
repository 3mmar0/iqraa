# Implementation Plan: Role Self-Registration

**Branch**: `004-role-self-registration` | **Date**: 2026-08-07 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/004-role-self-registration/spec.md`

**Note**: This template is filled in by the `/speckit-plan` command; its definition describes the execution workflow.

## Summary

Extend the public `/register` page so guests choose **student** or **instructor**, submit the same core identity fields, and receive the matching role with immediate dashboard access (no admin approval). Approach: add validated `account_type` to `RegisteredUserController`, attach `student` or `instructor` role from RBAC, and update Arabic guest Blade copy (with light Alpine toggle for role-specific headings) while leaving admin student/teacher creation unchanged.

## Technical Context

**Language/Version**: PHP 8.2+; Laravel 12.x

**Primary Dependencies**: Blade; Tailwind CSS; Alpine.js (already in `resources/js/app.js`); Laravel validation / Password rules; existing `Role` / `User` / `UserSetting` models; `DashboardPickerController`

**Storage**: MySQL — existing `users`, `roles`, `user_role`, `user_settings` tables (no new tables)

**Testing**: PHPUnit feature tests in `tests/Feature/RegistrationFlowTest.php` (extend for instructor + validation cases)

**Target Platform**: Same as platform (Ubuntu + Nginx + PHP-FPM); public Arabic RTL guest auth UI

**Project Type**: Modular monolithic Laravel web app (server-rendered guest + dashboards)

**Performance Goals**: Registration submit completes within normal web request budgets; no batch/async work required

**Constraints**: Arabic RTL only; no React/Vue/Livewire/Inertia; public registration may only assign `student` or `instructor`; staff roles remain admin-only; immediate instructor access after successful register/login

**Scale/Scope**: One guest page + one controller action; copy/UX for two account types; extend existing registration feature test

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

`.specify/memory/constitution.md` remains the Spec Kit placeholder (principles not ratified). Follow project norms from `001-learning-platform-core` plan:

| Gate | Status | Notes |
|------|--------|-------|
| Constitution principles defined | PASS (advisory) | Proceed with modular monolith, Form Requests optional for small auth change, Arabic UI |
| No stack split | PASS | Blade + Alpine only; no new frontend framework |
| Spec clarifications complete | PASS | FR-004 resolved: immediate instructor access |
| Reuse existing auth patterns | PASS | Extends `RegisteredUserController` + `auth.register` + `dashboard.redirect` |
| Schema churn minimized | PASS | No migrations; role attach only |

**Post–Phase 1 re-check**: Design keeps a single Laravel app, existing routes (`GET/POST /register`), and a thin UI/HTTP contract. No unjustified complexity. Complexity Tracking left empty.

## Project Structure

### Documentation (this feature)

```text
specs/004-role-self-registration/
├── plan.md              # This file (/speckit-plan command output)
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
│   └── public-registration.md
└── tasks.md             # Phase 2 (/speckit-tasks — NOT created by /speckit-plan)
```

### Source Code (repository root)

```text
app/Http/Controllers/Web/Auth/
└── RegisteredUserController.php   # validate account_type; attach student|instructor

app/Http/Requests/Auth/            # optional: StoreRegisteredUserRequest if extracted
└── StoreRegisteredUserRequest.php

resources/views/auth/
└── register.blade.php             # role chooser + dynamic Arabic copy

routes/web.php                     # existing guest register routes (unchanged paths)

tests/Feature/
└── RegistrationFlowTest.php       # student + instructor + missing type + staff not assignable
```

**Structure Decision**: Extend the existing guest auth surface in the Laravel modular monolith. No new packages, routes prefixes, or tables. Dashboard landing continues via `dashboard.redirect` using role `dashboard_key`.

## Complexity Tracking

> No constitution violations requiring justification.
