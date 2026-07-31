# Implementation Plan: Learning Platform Core

**Branch**: `001-learning-platform-core` | **Date**: 2026-07-31 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/001-learning-platform-core/spec.md`

**Note**: This template is filled in by the `/speckit-plan` command; its definition describes the execution workflow.

## Summary

Build a fully Arabic (RTL) educational platform as a **modular Laravel monolith**: shared session authentication, RBAC dashboards (Student, Instructor, Team, Finance, Marketing, Support, Super Admin), public website, and first-party API. Course access is **request → approve-enrollment permission → enrollment** (no payment gateway required in v1). UI is Blade + Tailwind CSS + Alpine.js (no React/Vue/Livewire/Inertia). Redis backs cache, queues, sessions, and rate limiting; heavy work runs via queues and the scheduler.

## Technical Context

**Language/Version**: PHP 8.3+; Laravel 12.x

**Primary Dependencies**: Laravel Sanctum (session + API tokens as needed), Laravel Policies/Gates, Notifications, Queue, Scheduler; Blade; Tailwind CSS; Alpine.js; Vite; Composer; npm; Laravel Pint; PHPStan (dev)

**Storage**: MySQL 8+ (Eloquent, soft deletes, FKs, indexes, UUID where needed); Redis (cache, queue, sessions, rate limit, OTP, dashboard stats); local disk for private media in v1 (S3-compatible later)

**Testing**: PHPUnit / Pest (feature + unit); contract tests against `contracts/`; browser smoke via documented quickstart scenarios

**Target Platform**: Ubuntu Server + Nginx + PHP-FPM + Redis + Supervisor + Cron; HTTPS; GitHub-based delivery

**Project Type**: Modular monolithic web application (server-rendered UI + JSON API)

**Performance Goals**: Student lesson resume ≤ 3 clicks after login (SC-001); interactive pages stay responsive by offloading email/notify/report/export to queues; dashboard home and course lists use cached aggregates where beneficial

**Constraints**: Full Arabic RTL UI only (no language switcher); private video/file access (no public direct URLs); no React/Vue/Livewire/Inertia; payment gateways Phase 2; 2FA/WhatsApp/FCM future; all surfaces ship in v1 with High-priority pages mandatory

**Scale/Scope**: Single deployment (multi-university students, not multi-tenant white-label); 8 dashboards + public site + API; domain modules for Users/RBAC, Courses, Learning Progress, Quizzes, Support, Finance, Marketing, Team, Notifications, Reports, Media

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

`.specify/memory/constitution.md` is still the Spec Kit placeholder (principles not ratified).

| Gate | Status | Notes |
|------|--------|-------|
| Constitution principles defined | PASS (advisory) | Proceed using stakeholder standards documented in research: PSR-12, SOLID, DRY, KISS, modular monolith, Form Requests, Policies, Service layer |
| Spec aligned with Arabic-only product | PASS | FR-001a enforced in Technical Context |
| No unjustified stack split | PASS | Single Laravel app; modules are folders, not separate deployables |
| Spec clarifications complete for blocking decisions | PASS | Enrollment, accounts, v1 scope, login, approve permission clarified |

**Post–Phase 1 re-check**: Design artifacts remain a single modular app with explicit contracts and data model; no constitution violations introduced. Recommend running `/speckit-constitution` before large implementation waves so gates become enforceable.

## Project Structure

### Documentation (this feature)

```text
specs/001-learning-platform-core/
├── plan.md              # This file
├── research.md          # Phase 0
├── data-model.md        # Phase 1
├── quickstart.md        # Phase 1
├── contracts/           # Phase 1
└── tasks.md             # Phase 2 (/speckit-tasks — not created here)
```

### Source Code (repository root)

```text
app/
├── Domains/                    # Optional domain services shared across modules
├── Http/
│   ├── Controllers/
│   │   ├── Web/                # Blade dashboard controllers by surface
│   │   └── Api/                # JSON API controllers
│   ├── Middleware/
│   ├── Requests/               # Form Requests
│   └── Resources/              # API Resources
├── Models/
├── Policies/
├── Notifications/
├── Jobs/
├── Services/                   # Application services (enrollment, progress, reports)
└── Providers/

modules/                        # Feature modules (modular monolith)
├── Auth/
├── Rbac/
├── Catalog/                    # Public courses + access requests
├── Learning/                   # Student progress, lessons, notes, comments
├── Teaching/                   # Instructor course/lesson/quiz authoring
├── Quizzes/
├── Media/                      # Private video/file delivery
├── Support/
├── Finance/
├── Marketing/
├── Team/
├── Notifications/
├── Reports/
└── Admin/                      # Super Admin ops surfaces

resources/
├── views/
│   ├── layouts/                # RTL Arabic layouts
│   ├── public/
│   ├── student/
│   ├── instructor/
│   ├── team/
│   ├── finance/
│   ├── marketing/
│   ├── support/
│   └── admin/
├── css/
└── js/

routes/
├── web.php
├── api.php
└── channels.php                # if needed later

database/
├── migrations/
├── seeders/
└── factories/

tests/
├── Feature/
├── Unit/
└── Contract/

public/
storage/app/private/            # Private lesson media (v1)
```

**Structure Decision**: Single Laravel 12 application with **feature modules under `modules/`** plus shared `app/` infrastructure. Blade views grouped by surface under `resources/views/`. JSON API under `routes/api.php` for first-party/future mobile use. This matches the stakeholder “Monolithic Modular Architecture” without microservices.

## Complexity Tracking

> Filled only where intentional complexity exceeds the simplest monolith.

| Violation | Why Needed | Simpler Alternative Rejected Because |
|-----------|------------|-------------------------------------|
| Feature modules (`modules/*`) | Eight dashboards + shared domains need clear ownership boundaries | Flat `app/` only becomes unmaintainable at this surface count |
| Service layer + Policies | Enrollment approval, media access, finance mutations need centralized rules | Fat controllers would duplicate RBAC and entitlement checks |
| Queued reports/exports | SC-010 and large exports must not block HTTP workers | Sync PDF/Excel generation fails under concurrent staff use |
| Private media controller | FR-005 forbids direct public file URLs | Public disk links would leak paid/entitled content |
