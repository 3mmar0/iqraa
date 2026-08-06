# Tasks: Role Self-Registration

**Input**: Design documents from `/specs/004-role-self-registration/`

**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/, quickstart.md

**Tests**: Included — plan.md Technical Context and quickstart.md specify PHPUnit coverage in `tests/Feature/RegistrationFlowTest.php` for student/instructor register, missing `account_type`, and role attachment.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- Laravel modular monolith per plan.md: `app/`, `resources/views/`, `routes/`, `tests/`
- Guest auth under `app/Http/Controllers/Web/Auth/` and `resources/views/auth/`

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Confirm feature workspace and existing registration touchpoints (app already exists)

- [x] T001 Verify feature docs exist under `specs/004-role-self-registration/` (spec.md, plan.md, research.md, data-model.md, contracts/public-registration.md, quickstart.md) and note current student-only flow in `app/Http/Controllers/Web/Auth/RegisteredUserController.php` and `resources/views/auth/register.blade.php`
- [x] T002 [P] Confirm `student` and `instructor` roles exist via `database/seeders/RbacSeeder.php` and that `routes/web.php` already exposes guest `GET/POST /register` (named `register`) with no path change required

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Shared validation + role-attach plumbing all stories need before UI polish

**CRITICAL**: No user story work can begin until this phase is complete

- [x] T003 Add required `account_type` validation (`in:student,instructor`) with Arabic error copy in `app/Http/Controllers/Web/Auth/RegisteredUserController.php` (or extract `app/Http/Requests/Auth/StoreRegisteredUserRequest.php` and wire it from the controller)
- [x] T004 Update registration store to attach exactly one role from `account_type` (`student` or `instructor`), keep `creation_source = self_registered` and `status = active`, create `UserSetting`, login, and redirect to `dashboard.redirect` in `app/Http/Controllers/Web/Auth/RegisteredUserController.php` — never attach staff role slugs

**Checkpoint**: POST `/register` with `account_type` can create either role; invalid/missing type is rejected

---

## Phase 3: User Story 1 - Visitor chooses account type and registers (Priority: P1) - MVP

**Goal**: Guests pick student or instructor on `/register`, submit the form, and land on the matching dashboard immediately (instructor included, no admin approval).

**Independent Test**: As a guest, open `/register`, select student, submit valid data → student dashboard; repeat with instructor → instructor dashboard immediately.

### Tests for User Story 1

- [x] T005 [P] [US1] Extend failing/updated feature coverage for student register (with `account_type=student`), instructor register (role `instructor` only + redirect path), and missing `account_type` Arabic validation in `tests/Feature/RegistrationFlowTest.php`

### Implementation for User Story 1

- [x] T006 [US1] Add account-type chooser (radio/select: طالب / محاضر) posting `account_type` on the form in `resources/views/auth/register.blade.php`
- [x] T007 [US1] Ensure successful student registration still creates `student` role + settings and redirects via `dashboard.redirect` to student home; update existing student case in `tests/Feature/RegistrationFlowTest.php` to send `account_type=student`
- [x] T008 [US1] Ensure successful instructor registration attaches `instructor` only, authenticates, and reaches instructor home via `dashboard.redirect` (assert in `tests/Feature/RegistrationFlowTest.php`)
- [x] T009 [US1] Make `tests/Feature/RegistrationFlowTest.php` pass for US1 acceptance paths (student, instructor, missing type)

**Checkpoint**: US1 complete — both account types register and land on the correct dashboard

---

## Phase 4: User Story 2 - Role-appropriate fields and messaging (Priority: P2)

**Goal**: Registration copy adapts to the selected account type; validation failures preserve the chosen type and show clear Arabic errors.

**Independent Test**: Toggle account type on `/register` and verify heading/help text match without leaving the page; fail validation and confirm `account_type` is preserved.

### Implementation for User Story 2

- [x] T010 [US2] Add Alpine (or equivalent) live toggle for Arabic heading/help text per selected type in `resources/views/auth/register.blade.php` using copy from `specs/004-role-self-registration/contracts/public-registration.md`
- [x] T011 [US2] Preserve `old('account_type')` after validation failure and keep Arabic uniqueness/required/password messages in `resources/views/auth/register.blade.php` and the store validation messages in `app/Http/Controllers/Web/Auth/RegisteredUserController.php` (or `StoreRegisteredUserRequest`)
- [x] T012 [P] [US2] Optionally emphasize university helper text for student selection only (field remains optional for both) in `resources/views/auth/register.blade.php`

**Checkpoint**: US2 complete — adaptive Arabic messaging and preserved selection on errors

---

## Phase 5: User Story 3 - Existing admin-created accounts stay valid (Priority: P3)

**Goal**: Admin student/teacher creation and seeded admin-created personas keep working; self-registered and admin-created users of the same role reach the same dashboard surface.

**Independent Test**: Admin-created student/instructor still sign in correctly; self-registered peers of each role reach the same dashboard keys.

### Tests for User Story 3

- [x] T013 [P] [US3] Add regression assertions that public register never assigns staff roles and that `creation_source` remains `self_registered` in `tests/Feature/RegistrationFlowTest.php`

### Implementation for User Story 3

- [x] T014 [US3] Smoke-check admin create paths remain unchanged for students/teachers (`app/Http/Controllers/Web/Admin/` student/teacher controllers and forms) — no code removal; fix only if register changes caused regressions
- [x] T015 [US3] Confirm seeded/admin-created instructor and student login still resolve via `app/Http/Controllers/Web/DashboardPickerController.php` the same as self-registered peers (manual per `specs/004-role-self-registration/quickstart.md` Regression section, or a thin feature assertion if practical)

**Checkpoint**: US3 complete — dual-path account creation intact

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Contract alignment and end-to-end validation

- [x] T016 [P] Align guest register UX with `specs/004-role-self-registration/contracts/public-registration.md` (no staff roles offered; Arabic suggested headings)
- [x] T017 Run quickstart validation: `php artisan test --filter=RegistrationFlowTest` plus manual student/instructor paths in `specs/004-role-self-registration/quickstart.md`
- [x] T018 [P] Update `specs/001-learning-platform-core/contracts/api.md` register row wording from student-only to student|instructor if that core contract is still treated as live docs

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — start immediately
- **Foundational (Phase 2)**: Depends on Setup — BLOCKS all user stories
- **User Story 1 (Phase 3)**: Depends on Foundational — MVP
- **User Story 2 (Phase 4)**: Depends on Foundational; best after US1 form chooser exists (T006)
- **User Story 3 (Phase 5)**: Depends on Foundational; can follow US1 role-attach behavior
- **Polish (Phase 6)**: After desired stories complete

### User Story Dependencies

- **User Story 1 (P1)**: After Phase 2 — no dependency on US2/US3
- **User Story 2 (P2)**: After Phase 2; practically needs US1 chooser in the Blade form
- **User Story 3 (P3)**: After Phase 2; validates non-regression once US1 store path is live

### Within Each User Story

- Tests (where listed) before or with implementation until green
- Validation/role attach before dashboard assertions
- Story complete before moving to next priority when staffing is sequential

### Parallel Opportunities

- T001 and T002 in Setup
- T005 can be drafted in parallel with T003/T004 once contract is clear (same test file as later tasks — coordinate)
- T012 and T016/T018 are parallelizable polish/UI tweaks on distinct concerns
- US2 and US3 can proceed in parallel after US1 MVP if different owners avoid editing the same controller/view simultaneously

---

## Parallel Example: User Story 1

```bash
# After foundational T003–T004:
Task: "Extend RegistrationFlowTest for student/instructor/missing type in tests/Feature/RegistrationFlowTest.php"
Task: "Add account_type chooser in resources/views/auth/register.blade.php"
# Then finish redirect/role assertions and make tests green
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (account_type + role attach)
3. Complete Phase 3: User Story 1 (chooser + tests green)
4. **STOP and VALIDATE**: Student and instructor register → correct dashboards
5. Deploy/demo if ready

### Incremental Delivery

1. Setup + Foundational → POST can assign either role
2. US1 → Full register MVP
3. US2 → Adaptive Arabic copy / old() preservation
4. US3 → Admin dual-path regression confidence
5. Polish → Contract/docs + quickstart

### Parallel Team Strategy

1. Pair on Setup + Foundational
2. Dev A: US1 tests + controller edge cases
3. Dev B: US2 Blade/Alpine copy (after chooser lands)
4. Dev C: US3 regression / admin smoke

---

## Notes

- [P] tasks = different files, no dependencies (or safe concurrent reads)
- [Story] label maps task to US1/US2/US3
- No migrations expected
- Staff roles must never appear on public registration
- Commit after each task or logical group
- Stop at checkpoints to validate independently
- Implemented 2026-08-07: all T001–T018 complete; `RegistrationFlowTest` 6 passed
