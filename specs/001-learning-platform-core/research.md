# Research: Learning Platform Core

**Branch**: `001-learning-platform-core` | **Date**: 2026-07-31

All Technical Context items resolved from the feature spec clarifications and stakeholder architecture preferences. No remaining NEEDS CLARIFICATION.

---

## 1. Application architecture

**Decision**: Modular monolithic Laravel 12 application (`modules/*` + shared `app/`), one deployable.

**Rationale**: Spec requires many dashboards and shared auth/RBAC/data; a modular monolith keeps transactional consistency (enrollment approval → enrollment row) without distributed complexity.

**Alternatives considered**:
- Microservices per dashboard — rejected (ops overhead, overkill for v1 single deployment).
- Flat Laravel app only — rejected for long-term maintainability at this surface count.
- Separate SPA frontend — rejected by explicit “no React/Vue/Inertia/Livewire” constraint.

---

## 2. UI stack

**Decision**: Blade + Tailwind CSS + Alpine.js + Vite; full Arabic RTL layouts.

**Rationale**: Matches stakeholder stack; Alpine covers interactive bits (quiz timer UI, toggles, modals) without a SPA framework.

**Alternatives considered**: Livewire/Inertia/React/Vue — explicitly out of scope.

---

## 3. Authentication & session

**Decision**: Shared web login using Laravel session authentication (Sanctum SPA/session pattern as appropriate); Remember Me; email verification; password reset. API uses Sanctum tokens for first-party clients. Post-login: single-role → direct dashboard; multi-role → picker.

**Rationale**: Spec FR-002a / clarifications require one login and role-based routing; Sanctum is the stated auth package.

**Alternatives considered**:
- Separate login URL per dashboard — rejected in clarification.
- 2FA in v1 — deferred (future).

---

## 4. Authorization

**Decision**: RBAC with roles, permissions, Policies, and Gates. Permission slug `enrollments.approve` (approve enrollment) is assignable to any staff role via Super Admin.

**Rationale**: Clarification Q5 requires configurable approval, not hard-coded Super Admin/Support.

**Alternatives considered**: Hard-code Super Admin only — rejected by clarification.

---

## 5. Course access / enrollment

**Decision**: `CourseAccessRequest` workflow: `pending` → `approved` | `rejected`. Approval creates `Enrollment` (active). No payment gateway required to grant access in v1.

**Rationale**: Clarification Q1; Phase 2 payment providers may later automate or supplement approval.

**Alternatives considered**: Self-checkout in v1; free open enrollment — rejected.

---

## 6. Account provisioning

**Decision**: Dual path — public self-registration (email verification) and admin-created students (invite/activation or temporary password flow).

**Rationale**: Clarification Q2.

**Alternatives considered**: Admin-only or self-register-only — rejected.

---

## 7. Media delivery

**Decision**: Store lesson videos/files on private local disk in v1; stream/download only through authorized controllers after enrollment checks. Signed temporary URLs optional for player segments if needed, still entitlement-gated.

**Rationale**: FR-005; stakeholder phase-1 local storage; S3/CDN later.

**Alternatives considered**: Public `/storage` links — rejected (leak risk). CDN/adaptive streaming — Phase 2+.

---

## 8. Caching & queues

**Decision**: Redis for cache, queue, sessions, rate limiting, OTP, and dashboard statistic keys. Queue workers (Supervisor) for mail, notifications, Telegram, reports, exports, backups. Scheduler for cleanup, reminders, stats refresh, subscription expiry checks, student alerts.

**Rationale**: Stakeholder architecture; keeps HTTP requests responsive (SC-010).

**Alternatives considered**: Database queue/sessions — workable for tiny installs but weaker under concurrent dashboards/reports.

---

## 9. Notifications

**Decision**: In-app notifications always persisted; email via SMTP; Telegram Bot API in Phase 1 integrations. WhatsApp/FCM later.

**Rationale**: FR-006 and stakeholder phases.

---

## 10. Reporting & search

**Decision**: Report/export jobs produce PDF/Excel/CSV artifacts with completion notifications. Search via Query Builder + MySQL FULLTEXT where useful; Scout deferred.

**Rationale**: FR-007; stakeholder search roadmap.

---

## 11. Module packaging approach

**Decision**: Prefer lightweight internal modules (PSR-4 namespaces + service providers) over mandatory third-party module packages; optionally adopt `nwidart/laravel-modules` during implementation if it speeds scaffolding—either way, module boundaries stay the same.

**Rationale**: Clear domains without forcing a specific package in the plan gate.

**Alternatives considered**: Force nwidart immediately — optional, not blocking.

---

## 12. Testing strategy

**Decision**: Feature tests for enrollment request/approve, RBAC denials, lesson entitlement, shared login routing; contract tests for documented API routes; seeders/factories for quickstart personas.

**Rationale**: Maps directly to acceptance scenarios and `contracts/`.

---

## 13. Deployment baseline

**Decision**: Ubuntu, Nginx, PHP-FPM, Redis, Supervisor, Cron, SSL, Git.

**Rationale**: Stakeholder deployment table; standard Laravel production shape.

---

## 14. v1 surface delivery

**Decision**: Ship all surfaces in v1; prioritize High matrix pages; Medium/Low may be thinner but surfaces must exist and serve primary actors (FR-001b).

**Rationale**: Clarification Q3.

**Alternatives considered**: Core-only MVP cut — rejected by stakeholder.
