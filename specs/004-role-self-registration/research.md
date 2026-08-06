# Research: Role Self-Registration

## Decision 1: Single `/register` with account type field

**Decision**: Keep one public entry (`GET/POST /register`). Add required `account_type` with allowed values `student` | `instructor`.

**Rationale**: Spec assumes a single live URL; avoids duplicate forms and SEO/auth middleware duplication. Matches existing guest layout max-width form.

**Alternatives considered**: Separate `/register/instructor` route (clearer URLs, more duplication); query `?as=instructor` only (weaker for POST validation and bookmarks).

## Decision 2: Role assignment via existing RBAC slugs

**Decision**: After user create, attach exactly one role: `Role` where `slug` is `student` or `instructor` based on `account_type`. Never attach staff slugs from this endpoint.

**Rationale**: `RbacSeeder` already defines both roles with `dashboard_key` values that `User::dashboardKeys()` and `DashboardPickerController` understand. Immediate access follows from attaching instructor + `status = active` + login + redirect to `dashboard.redirect`.

**Alternatives considered**: Soft “pending instructor” status (rejected by product clarification A); create instructor profile row beyond `users` (out of scope; admin teachers already use User + instructor role).

## Decision 3: No schema migration

**Decision**: Reuse `users.creation_source = self_registered`, `users.status = active`, `user_role`, and `user_settings` creation as today.

**Rationale**: Spec FR-009 only needs distinguishable creation source; already present. No new columns required for account type after role is attached.

**Alternatives considered**: Persist `account_type` column (redundant with role); invite/approval table (rejected by immediate-access decision).

## Decision 4: UI — Alpine for copy toggle, server validates truth

**Decision**: Use a small Alpine `x-data` on the register form to switch Arabic heading/help text (and optional university emphasis) client-side when the guest picks a role. Server-side validation remains authoritative for `account_type`.

**Rationale**: Alpine is already loaded on guest layout via Vite `app.js`. Spec P2 asks for adaptive copy without a page reload. Progressive enhancement: radios/select still work if JS fails; headings can default to student or use `old('account_type')`.

**Alternatives considered**: Full page reload per role (simpler, worse UX); two static partials without JS (no live toggle).

## Decision 5: Email verification unchanged

**Decision**: Do not introduce MustVerifyEmail gating for this feature. Current controller does not send verification; existing `RegistrationFlowTest` asserts `VerifyEmail` is not sent. Keep that behavior for both roles unless a later platform-wide verification initiative changes auth.

**Rationale**: Spec allows “any existing email-verification step if enabled”; today none is enabled on this path. Instructors and students stay consistent.

**Alternatives considered**: Enable verification for instructors only (asymmetric, surprising); enable for both now (scope creep vs current tests/product).

## Decision 6: Form request optional

**Decision**: Prefer extracting `StoreRegisteredUserRequest` for clarity and Arabic messages, but allowing inline validation in the controller is acceptable if kept small.

**Rationale**: Matches platform preference for Form Requests on admin CRUD; auth controllers today often validate inline. Either is fine if tests cover rules.

## Decision 7: University field for instructors

**Decision**: Keep `university` as optional nullable for both roles (same field). No instructor-only bio in v1.

**Rationale**: Spec assumption; minimizes form branching. Student-focused helper text can still mention university when student is selected.
