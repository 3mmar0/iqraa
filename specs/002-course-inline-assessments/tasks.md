# Tasks: Course Inline Assessments

**Input**: Design documents from `/specs/002-course-inline-assessments/`

**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/, quickstart.md

**Tests**: Included — plan.md Technical Context and project structure specify Pest/PHPUnit feature tests for question CRUD, publish gate, grade/resubmit, and course-tab redirects; quickstart.md lists automated filters.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- Laravel modular monolith per plan.md: `app/`, `modules/`, `resources/views/`, `routes/`, `tests/`
- Admin UI under `resources/views/admin/` and `app/Http/Controllers/Web/Admin/`
- Quiz domain helpers in `modules/Quizzes/Services/`

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Confirm feature workspace and wiring targets (app already exists)

- [x] T001 Verify feature docs exist under `specs/002-course-inline-assessments/` (spec.md, plan.md, research.md, data-model.md, contracts/admin-course-assessments.md, quickstart.md) and note course-tab entry points `resources/views/admin/courses/tabs/quizzes.blade.php` and `resources/views/admin/courses/tabs/assignments.blade.php`
- [x] T002 [P] Confirm existing models/tables for quizzes, questions, options, assignments, and submissions in `app/Models/Quiz.php`, `app/Models/Question.php`, `app/Models/QuestionOption.php`, `app/Models/Assignment.php`, `app/Models/AssignmentSubmission.php`, and `app/Models/AttemptAnswer.php` (no new core migrations expected)

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Shared backend gates and course-show eager loads that all stories need

**CRITICAL**: No user story work can begin until this phase is complete

- [x] T003 Enforce publish-requires-at-least-one-question (Arabic error) in `modules/Quizzes/Services/QuizAdminService.php` and surface failures from `app/Http/Controllers/Web/Admin/QuizController.php` publish/store paths
- [x] T004 Extend course show eager-loading for assessment tabs in `app/Http/Controllers/Web/Admin/CourseController.php` (`quizzes.questions.options` when tab=quizzes; `assignments.lesson` plus `assignments.submissions.user` when tab=assignments)
- [x] T005 [P] Document/reuse return-to-course fields via existing `resources/views/admin/courses/_return_fields.blade.php` and `app/Http/Controllers/Web/Admin/Concerns/ReturnsToCourse.php` for all new mutating forms

**Checkpoint**: Publish gate works; course show can load question/submission graphs for tabs

---

## Phase 3: User Story 1 - Manage quiz questions on the course Quizzes tab (Priority: P1) - MVP

**Goal**: Admins add/edit/delete/reorder quiz questions (single/multiple/text + options) entirely from `?tab=quizzes` without opening standalone quiz show for authoring.

**Independent Test**: From `/admin/courses/{id}?tab=quizzes`, create a quiz, add single + text questions, edit/delete/reorder, publish only when questions exist — never required to visit `/admin/quizzes/{id}`.

### Tests for User Story 1

- [x] T006 [P] [US1] Add failing feature coverage for question CRUD, option validation, delete-block-with-attempts, publish gate, and return-to-course in `tests/Feature/Admin/CourseQuizQuestionsTest.php`

### Implementation for User Story 1

- [x] T007 [P] [US1] Create Form Request(s) for store/update question payloads (type, body, points, options rules) in `app/Http/Requests/Admin/StoreQuestionRequest.php` and `app/Http/Requests/Admin/UpdateQuestionRequest.php`
- [x] T008 [US1] Implement question create/update/destroy/reorder (transactional options sync; block destroy when attempt_answers exist; map short_text to text) in `app/Http/Controllers/Web/Admin/QuestionController.php` using ReturnsToCourse and audit logging
- [x] T009 [US1] Register nested admin routes `admin.quizzes.questions.store|update|destroy|reorder` in `routes/web.php`
- [x] T010 [P] [US1] Optionally add question helper methods (sync options / next position) on `modules/Quizzes/Services/QuizAdminService.php` if controller logic needs extraction
- [x] T011 [US1] Replace outbound questions primary flow with in-tab Alpine question manager (list, add/edit modal, options UI, reorder, delete confirm) in `resources/views/admin/courses/tabs/quizzes.blade.php`, including questions payload URLs and `_return_fields`
- [x] T012 [US1] Keep optional secondary link to `admin.quizzes.show` for attempts/stats only (not required for authoring) in `resources/views/admin/courses/tabs/quizzes.blade.php` and/or `resources/views/admin/quizzes/show.blade.php`
- [x] T013 [US1] Make `tests/Feature/Admin/CourseQuizQuestionsTest.php` pass for US1 acceptance paths

**Checkpoint**: US1 complete — question authoring works fully on the course Quizzes tab

---

## Phase 4: User Story 2 - Manage assignments entirely on the course Assignments tab (Priority: P1)

**Goal**: Admins create/edit/delete/view assignment details in place on `?tab=assignments` without requiring `/admin/assignments/{id}` for routine management; block delete (prefer archive) when graded submissions exist.

**Independent Test**: From `/admin/courses/{id}?tab=assignments`, create/edit/change status/delete and open in-tab detail — without visiting global assignment pages for those tasks.

### Tests for User Story 2

- [x] T014 [P] [US2] Add failing feature coverage for assignment delete/archive-when-graded and return-to-course from course tab context in `tests/Feature/Admin/CourseAssignmentInlineTest.php`

### Implementation for User Story 2

- [x] T015 [US2] Tighten assignment destroy rules (block delete / archive when graded submissions exist; Arabic flash) in `app/Http/Controllers/Web/Admin/AssignmentController.php`
- [x] T016 [US2] Replace view navigation with in-tab Alpine detail panel (title, description, lesson, due date, status) in `resources/views/admin/courses/tabs/assignments.blade.php` while retaining existing create/edit/delete modals and `_return_fields`
- [x] T017 [US2] Make `tests/Feature/Admin/CourseAssignmentInlineTest.php` pass for US2 acceptance paths

**Checkpoint**: US2 complete — assignment CRUD + detail stay on the course Assignments tab

---

## Phase 5: User Story 3 - Review assignment submissions without leaving the course (Priority: P2)

**Goal**: From the course Assignments tab, view submissions and grade or request resubmit without opening standalone assignment show.

**Independent Test**: With a student submission, open submissions on the course tab, grade one, request resubmit — URL remains `/admin/courses/{id}?tab=assignments` (or returns there after POST).

### Tests for User Story 3

- [x] T018 [P] [US3] Add failing feature coverage for grade/resubmit actions and return-to-course in `tests/Feature/Admin/CourseAssignmentSubmissionsTest.php`

### Implementation for User Story 3

- [x] T019 [P] [US3] Create Form Request for grading (score 0-100) in `app/Http/Requests/Admin/GradeAssignmentSubmissionRequest.php`
- [x] T020 [US3] Implement grade and resubmit actions (status graded + score; status resubmit_requested + clear score) with ReturnsToCourse and audit logs in `app/Http/Controllers/Web/Admin/AssignmentController.php`
- [x] T021 [US3] Register routes `admin.assignments.submissions.grade` and `admin.assignments.submissions.resubmit` in `routes/web.php`
- [x] T022 [US3] Add submissions list + grade/resubmit forms inside the in-tab assignment panel in `resources/views/admin/courses/tabs/assignments.blade.php` (payload includes submission URLs; show late via due_at comparison in UI)
- [x] T023 [P] [US3] Optionally reuse submission table partial on standalone show in `resources/views/admin/assignments/show.blade.php` so both surfaces share grading UI
- [x] T024 [US3] Make `tests/Feature/Admin/CourseAssignmentSubmissionsTest.php` pass for US3 acceptance paths

**Checkpoint**: US3 complete — grading works from the course Assignments tab

---

## Phase 6: User Story 4 - Keep global quiz/assignment indexes as optional overview (Priority: P3)

**Goal**: Global indexes remain; course tabs are the primary authoring path and no longer force outbound navigation for core tasks.

**Independent Test**: Course Quizzes/Assignments tabs do not require standalone show for authoring; `/admin/quizzes` and `/admin/assignments` still list resources.

### Implementation for User Story 4

- [x] T025 [P] [US4] Audit course tab CTAs so primary actions are in-tab (questions/detail/submissions) and any remaining links to `admin.quizzes.show` / `admin.assignments.show` are clearly secondary in `resources/views/admin/courses/tabs/quizzes.blade.php` and `resources/views/admin/courses/tabs/assignments.blade.php`
- [x] T026 [P] [US4] Add course return link when quiz/assignment was opened from a course context on `resources/views/admin/quizzes/show.blade.php` and `resources/views/admin/assignments/show.blade.php` if return query/session is available
- [x] T027 [US4] Confirm global indexes still render in `resources/views/admin/quizzes/index.blade.php` and `resources/views/admin/assignments/index.blade.php` without regressing filters

**Checkpoint**: Global overview retained; course tabs are the primary workspace

---

## Phase 7: Polish and Cross-Cutting Concerns

**Purpose**: Validation, copy, and light cleanup across stories

- [x] T028 [P] Align Arabic flash/validation copy for question and submission errors with existing admin tone across `app/Http/Requests/Admin/StoreQuestionRequest.php`, `app/Http/Requests/Admin/UpdateQuestionRequest.php`, `app/Http/Requests/Admin/GradeAssignmentSubmissionRequest.php`, and related controllers
- [x] T029 Run `specs/002-course-inline-assessments/quickstart.md` scenarios A-C manually (or document results) against a local admin session
- [x] T030 [P] Run `php artisan test --filter=CourseQuizQuestions` and `php artisan test --filter=CourseAssignment` and fix any failures
- [x] T031 Review N+1 / payload size on course show after eager loads in `app/Http/Controllers/Web/Admin/CourseController.php` and cap submissions listed in the Alpine payload if needed in `resources/views/admin/courses/tabs/assignments.blade.php`

---

## Dependencies and Execution Order

See prior plan; all phases implemented.

## Notes

- All tasks T001–T031 completed 2026-08-03
- Feature tests: 14 passed (`CourseQuizQuestions` 8, `CourseAssignment*` 6)
- Manual quickstart A–C covered by feature tests + in-tab UI markers assertions
