# Tasks: Learning Platform Core

**Input**: Design documents from `/specs/001-learning-platform-core/`

**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/, quickstart.md

**Tests**: Not included — feature specification did not explicitly request TDD/contract test tasks. Validate via `quickstart.md` scenarios during polish.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- Laravel modular monolith per `plan.md`: `app/`, `modules/`, `resources/views/`, `routes/`, `database/`, `tests/`

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Initialize the Laravel application and tooling

- [x] T001 Create Laravel 12 project skeleton at repository root with PHP 8.3+ (`composer create-project` / equivalent) and commit base `composer.json`
- [x] T002 [P] Configure MySQL, Redis (cache/queue/session), and `APP_LOCALE=ar` in `.env.example`
- [x] T003 [P] Install and configure Tailwind CSS, Alpine.js, and Vite in `package.json`, `vite.config.js`, `resources/css/app.css`, `resources/js/app.js`
- [x] T004 [P] Install Laravel Sanctum and publish config in `config/sanctum.php`
- [x] T005 [P] Add Laravel Pint and PHPStan (or Larastan) config in `pint.json` and `phpstan.neon`
- [x] T006 Create `modules/` directory tree (Auth, Rbac, Catalog, Learning, Teaching, Quizzes, Media, Support, Finance, Marketing, Team, Notifications, Reports, Admin) with PSR-4 mappings in `composer.json`
- [x] T007 Register module service providers in `bootstrap/providers.php` (or `config/app.php`) for each `modules/*/Providers/*ServiceProvider.php`
- [x] T008 Create shared RTL Arabic layout shell in `resources/views/layouts/app.blade.php` and `resources/views/layouts/guest.blade.php` (`dir=rtl` `lang=ar`)

**Checkpoint**: App boots, Vite builds, Redis/MySQL env documented

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Auth, RBAC, routing, notifications/queues — MUST complete before user stories

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [x] T009 Create migrations for `users`, `roles`, `permissions`, `role_permission`, `user_role`, `user_settings` in `database/migrations/`
- [x] T010 [P] Create Eloquent models `app/Models/User.php`, `app/Models/Role.php`, `app/Models/Permission.php`, `app/Models/UserSetting.php`
- [x] T011 Implement RBAC helpers and Gate/Policy registration in `app/Providers/AuthServiceProvider.php` and `modules/Rbac/Services/PermissionRegistrar.php`
- [x] T012 Seed base roles/permissions (including `enrollments.approve`) in `database/seeders/RbacSeeder.php`
- [x] T013 Implement shared web login/logout/register/password-reset controllers in `app/Http/Controllers/Web/Auth/` and routes in `routes/web.php`
- [x] T014 [P] Implement Sanctum API login/logout/me in `app/Http/Controllers/Api/V1/AuthController.php` and `routes/api.php`
- [x] T015 Implement post-login dashboard routing + multi-role picker in `app/Http/Controllers/Web/DashboardPickerController.php` and `resources/views/auth/dashboard-picker.blade.php`
- [x] T016 [P] Add middleware `EnsureDashboardAccess` and `EnsurePermission` in `app/Http/Middleware/`
- [x] T017 Configure queue, mail, and notifications defaults in `config/queue.php`, `config/mail.php`, and `app/Notifications/`
- [x] T018 Create private media disk config `local_private` in `config/filesystems.php` pointing at `storage/app/private`
- [x] T019 Create database seeder personas from `quickstart.md` in `database/seeders/DemoPersonaSeeder.php` and wire `DatabaseSeeder.php`
- [x] T020 Add Arabic guest flash/error partials in `resources/views/components/alert.blade.php`

**Checkpoint**: Foundation ready — shared login works; roles/permissions seeded; user stories can start

---

## Phase 3: User Story 1 — Student Learning Loop (Priority: P1) 🎯 MVP

**Goal**: Enrolled students learn via Home → Courses → Lesson → Quiz → Progress; course access via request → approve → enrollment

**Independent Test**: Seeded enrolled student completes login → home → course → lesson → mark complete → quiz → progress; separate student can request access and gain course after approver action (quickstart Scenarios B–C)

### Implementation for User Story 1

- [x] T021 [P] [US1] Create migrations for `courses`, `course_access_requests`, `enrollments`, `lessons`, `media_assets`, `lesson_progress`, `lesson_notes`, `lesson_comments` in `database/migrations/`
- [x] T022 [P] [US1] Create migrations for `quizzes`, `questions`, `question_options`, `quiz_attempts`, `attempt_answers`, `calendar_events`, `announcements`, `achievements`, `user_achievements` in `database/migrations/`
- [x] T023 [P] [US1] Create models in `app/Models/` for Course, CourseAccessRequest, Enrollment, Lesson, MediaAsset, LessonProgress, LessonNote, LessonComment
- [x] T024 [P] [US1] Create models in `app/Models/` for Quiz, Question, QuestionOption, QuizAttempt, AttemptAnswer, CalendarEvent, Announcement, Achievement
- [x] T025 [US1] Implement `EnrollmentService` (request, approve, reject, create enrollment, uniqueness) in `app/Services/EnrollmentService.php`
- [x] T026 [US1] Implement `LessonProgressService` and `QuizAttemptService` in `app/Services/LessonProgressService.php` and `app/Services/QuizAttemptService.php`
- [x] T027 [US1] Implement private media stream/download in `app/Http/Controllers/Web/Student/MediaController.php` with enrollment Policy in `app/Policies/EnrollmentPolicy.php`
- [x] T028 [US1] Implement student course-request endpoints in `app/Http/Controllers/Web/Student/CourseRequestController.php` and staff approve/reject in `app/Http/Controllers/Web/Staff/CourseRequestController.php`
- [x] T029 [P] [US1] Add Form Requests in `app/Http/Requests/Student/` and `app/Http/Requests/Staff/` for course requests and quiz submit
- [x] T030 [US1] Build Student Home controller + Blade view in `app/Http/Controllers/Web/Student/HomeController.php` and `resources/views/student/home.blade.php`
- [x] T031 [P] [US1] Build My Courses + Course Details views in `resources/views/student/courses/index.blade.php` and `resources/views/student/courses/show.blade.php` with controllers under `app/Http/Controllers/Web/Student/`
- [x] T032 [US1] Build Lesson page (player, PDF, attachments, notes, comments, prev/next, complete) in `app/Http/Controllers/Web/Student/LessonController.php` and `resources/views/student/lessons/show.blade.php`
- [x] T033 [US1] Build Quiz start/submit/result flow in `app/Http/Controllers/Web/Student/QuizController.php` and `resources/views/student/quizzes/`
- [x] T034 [P] [US1] Build Progress, Achievements, Notifications, Calendar pages under `resources/views/student/` and matching controllers
- [x] T035 [P] [US1] Build Profile + Settings (dark mode + notification prefs only) under `resources/views/student/profile.blade.php` and `resources/views/student/settings.blade.php`
- [x] T036 [US1] Build Student Support ticket create + FAQ list in `app/Http/Controllers/Web/Student/SupportController.php` and `resources/views/student/support/`
- [x] T037 [US1] Register all student + staff course-request web routes in `routes/web.php` (prefix `/student`, `/staff`)
- [x] T038 [P] [US1] Mirror critical student learning + course-request JSON endpoints in `app/Http/Controllers/Api/V1/Student/` per `contracts/api.md`
- [x] T039 [US1] Add shared course-request queue Blade partial for any role with `enrollments.approve` in `resources/views/components/course-request-queue.blade.php`
- [x] T040 [US1] Seed sample published course, lessons, quiz, and one active enrollment in `database/seeders/LearningDemoSeeder.php`

**Checkpoint**: US1 independently demoable (MVP learning loop + request/approve)

---

## Phase 4: User Story 2 — Instructor Teaching Operations (Priority: P1)

**Goal**: Instructors manage courses, lessons, media, quizzes, students, announcements, and teaching calendar for owned courses

**Independent Test**: Instructor creates/edits lesson on owned course; student sees published content; announcement notifies student (quickstart Scenario D)

### Implementation for User Story 2

- [x] T041 [P] [US2] Create instructor authorization Policy `app/Policies/CoursePolicy.php` (own/assigned courses only)
- [x] T042 [US2] Implement Teaching services for course/lesson/quiz CRUD in `modules/Teaching/Services/CourseAuthoringService.php`
- [x] T043 [US2] Build Instructor Dashboard home in `app/Http/Controllers/Web/Instructor/DashboardController.php` and `resources/views/instructor/dashboard.blade.php`
- [x] T044 [P] [US2] Build Courses/Lessons/Videos management UI under `resources/views/instructor/courses/` and controllers in `app/Http/Controllers/Web/Instructor/`
- [x] T045 [P] [US2] Build Assignments + Quizzes authoring UI under `resources/views/instructor/quizzes/` and `resources/views/instructor/assignments/`
- [x] T046 [P] [US2] Build Students roster + Messages + Announcements under `resources/views/instructor/`
- [x] T047 [US2] Build Live Sessions (schedule/join-link metadata) in `app/Http/Controllers/Web/Instructor/LiveSessionController.php`
- [x] T048 [P] [US2] Build Instructor Reports/Analytics summary pages under `resources/views/instructor/reports/`
- [x] T049 [US2] Build Instructor Calendar + Settings views under `resources/views/instructor/`
- [x] T050 [US2] Register `/instructor/*` routes in `routes/web.php` with dashboard middleware
- [x] T051 [US2] Private media upload to `local_private` disk via `app/Http/Controllers/Web/Instructor/MediaUploadController.php`

**Checkpoint**: US2 independently testable for teaching operations

---

## Phase 5: User Story 3 — Secure Role Separation Across Dashboards (Priority: P1)

**Goal**: Harden shared-login RBAC: denial of unauthorized dashboards, picker correctness, session expiry behavior

**Independent Test**: Student denied `/finance` and `/admin`; multi-role user sees only allowed picker entries; logout forces re-auth (quickstart Scenario A)

### Implementation for User Story 3

- [x] T052 [US3] Audit and lock all dashboard route groups with `EnsureDashboardAccess` in `routes/web.php`
- [x] T053 [US3] Implement Arabic 403 page in `resources/views/errors/403.blade.php`
- [x] T054 [US3] Add dashboard switcher component for multi-role sessions in `resources/views/components/dashboard-switcher.blade.php`
- [x] T055 [US3] Enforce API permission checks mirroring web policies in `app/Http/Middleware/EnsurePermission.php` for `/api/v1/*`
- [x] T056 [US3] Add no-role safe landing page in `resources/views/auth/no-access.blade.php` and routing from `DashboardPickerController`

**Checkpoint**: Cross-role leakage blocked; picker/session behavior matches spec

---

## Phase 6: User Story 4 — Finance Independence (Priority: P2)

**Goal**: Independent Finance dashboard for overview, revenue, expenses, transactions, subscriptions, refunds, payroll, reports, forecast, profit

**Independent Test**: Finance user reviews overview, filters transactions, records/processes refund with audit trail, enqueues export

### Implementation for User Story 4

- [x] T057 [P] [US4] Create migrations for `transactions`, `subscriptions`, `refunds`, `expenses`, `payroll_records` in `database/migrations/`
- [x] T058 [P] [US4] Create finance models under `app/Models/` (Transaction, Subscription, Refund, Expense, PayrollRecord)
- [x] T059 [US4] Implement `FinanceService` and refund audit logging in `app/Services/FinanceService.php`
- [x] T060 [US4] Build Finance Overview + Revenue + Expenses pages under `resources/views/finance/` and `app/Http/Controllers/Web/Finance/`
- [x] T061 [P] [US4] Build Transactions, Subscriptions, Refunds pages under `resources/views/finance/`
- [x] T062 [P] [US4] Build Payroll, Forecast, Profit pages under `resources/views/finance/`
- [x] T063 [US4] Wire Finance report enqueue to `ReportJob` via `app/Http/Controllers/Web/Finance/ReportController.php`
- [x] T064 [US4] Register `/finance/*` routes and `finance.*` permissions in `routes/web.php` and `database/seeders/RbacSeeder.php`

**Checkpoint**: Finance surface usable without Instructor/Marketing tools

---

## Phase 7: User Story 5 — Marketing Growth Engine (Priority: P2)

**Goal**: Independent Marketing dashboard for campaigns, coupons, referrals, ambassadors, leads, conversion, analytics

**Independent Test**: Marketing user creates coupon/campaign and views leads/conversion metrics

### Implementation for User Story 5

- [x] T065 [P] [US5] Create migrations for `campaigns`, `coupons`, `leads`, `referrals`, `ambassador_profiles` in `database/migrations/`
- [x] T066 [P] [US5] Create marketing models under `app/Models/`
- [x] T067 [US5] Implement `MarketingService` in `app/Services/MarketingService.php`
- [x] T068 [P] [US5] Build Campaigns + Coupons CRUD UI under `resources/views/marketing/` and controllers in `app/Http/Controllers/Web/Marketing/`
- [x] T069 [P] [US5] Build Referral + Student Ambassadors pages under `resources/views/marketing/`
- [x] T070 [US5] Build Leads, Conversion, Analytics pages under `resources/views/marketing/`
- [x] T071 [US5] Register `/marketing/*` routes and permissions in `routes/web.php` and `database/seeders/RbacSeeder.php`

**Checkpoint**: Marketing surface independent of payroll/admin

---

## Phase 8: User Story 6 — Internal Team Coordination (Priority: P2)

**Goal**: Team members manage tasks, announcements, files, meetings, goals, reports, attendance

**Independent Test**: Team member updates assigned task; sees scoped announcements/files; attendance visible per permissions

### Implementation for User Story 6

- [x] T072 [P] [US6] Create migrations for `team_memberships`, `team_tasks`, `team_files`, `meetings`, `goals`, `attendance_records` in `database/migrations/`
- [x] T073 [P] [US6] Create team models under `app/Models/`
- [x] T074 [US6] Implement `TeamTaskService` with membership scoping in `app/Services/TeamTaskService.php`
- [x] T075 [P] [US6] Build Tasks, Announcements, Files pages under `resources/views/team/` and `app/Http/Controllers/Web/Team/`
- [x] T076 [P] [US6] Build Meetings, Goals, Attendance, Reports pages under `resources/views/team/`
- [x] T077 [US6] Register `/team/*` routes and permissions in `routes/web.php` and `database/seeders/RbacSeeder.php`

**Checkpoint**: Team dashboard works without Finance/Super Admin access

---

## Phase 9: User Story 7 — Support Resolution (Priority: P2)

**Goal**: Support agents manage tickets, live chat, student lookup, FAQ, support reports

**Independent Test**: Agent replies/closes student ticket; FAQ publish visible on student support; chat messages retained

### Implementation for User Story 7

- [x] T078 [P] [US7] Create migrations for `tickets`, `ticket_messages`, `faq_articles` in `database/migrations/` (if not already from US1 stubs)
- [x] T079 [US7] Implement `TicketService` and live-chat message handling in `app/Services/TicketService.php`
- [x] T080 [US7] Build Support Tickets + Live Chat UI under `resources/views/support/` and `app/Http/Controllers/Web/Support/`
- [x] T081 [P] [US7] Build Students context lookup + FAQ management under `resources/views/support/`
- [x] T082 [US7] Build Support Reports page and register `/support/*` routes in `routes/web.php`
- [x] T083 [US7] Ensure student-created tickets from US1 appear in support queue (integrate `SupportController` student side)

**Checkpoint**: Support lifecycle works end-to-end with student surface

---

## Phase 10: User Story 8 — Super Admin Platform Control (Priority: P3)

**Goal**: Super Admin controls users, roles, permissions, logs, settings, storage/queues/jobs, notifications/emails, audit, backups, security, monitoring

**Independent Test**: Admin creates student; assigns `enrollments.approve` to a role; audit log records the change; ops pages show queue/job health

### Implementation for User Story 8

- [x] T084 [US8] Build Users CRUD (including admin-created students + invite/activation) in `app/Http/Controllers/Web/Admin/UserController.php` and `resources/views/admin/users/`
- [x] T085 [US8] Build Roles & Permissions UI (assign `enrollments.approve`) in `app/Http/Controllers/Web/Admin/RoleController.php` and `resources/views/admin/roles/`
- [x] T086 [P] [US8] Implement `AuditLog` model/migration and writer in `app/Models/AuditLog.php` and `app/Services/AuditLogger.php`
- [x] T087 [P] [US8] Build Logs + Audit Logs pages under `resources/views/admin/logs/`
- [x] T088 [P] [US8] Build Settings, Storage, Queues, Jobs, Monitoring pages under `resources/views/admin/ops/`
- [x] T089 [P] [US8] Build Notifications/Emails ops visibility pages under `resources/views/admin/comms/`
- [x] T090 [US8] Build Backups + Security overview actions/pages under `resources/views/admin/security/`
- [x] T091 [US8] Register `/admin/*` routes locked to super_admin in `routes/web.php`

**Checkpoint**: Control plane can provision users and permissions used by other stories

---

## Phase 11: User Story 9 — Public Website Entry (Priority: P3)

**Goal**: Public Arabic discovery site, catalog teasers, CTAs to register/login, published course request entry points

**Independent Test**: Visitor browses public pages; registers/logs in; reaches correct dashboard (quickstart public steps)

### Implementation for User Story 9

- [x] T092 [P] [US9] Build public home/discovery page in `resources/views/public/home.blade.php` and `app/Http/Controllers/Web/Public/HomeController.php`
- [x] T093 [US9] Build public course catalog + course teaser with “طلب الانضمام” CTA in `resources/views/public/courses/` and controllers
- [x] T094 [US9] Polish guest auth pages (login/register/reset) copy/layout in `resources/views/auth/` for full Arabic UX
- [x] T095 [US9] Add public routes in `routes/web.php` and SEO-safe meta partial in `resources/views/components/meta.blade.php`

**Checkpoint**: Acquisition entry path complete

---

## Phase 12: Polish & Cross-Cutting Concerns

**Purpose**: Reports, notifications channels, scheduler, hardening, quickstart validation

- [x] T096 Create `report_jobs` migration/model and `app/Jobs/GenerateReportJob.php` with PDF/Excel/CSV exporters under `app/Services/Reports/`
- [x] T097 [P] Implement in-app + mail notification classes under `app/Notifications/` for enrollment decisions, announcements, report ready
- [x] T098 [P] Add Telegram notification channel stub/queue job in `app/Notifications/Channels/TelegramChannel.php` (Phase 1 integration)
- [x] T099 Register scheduler tasks (temp cleanup, reminders, stats refresh, subscription expiry, backups hook) in `routes/console.php`
- [x] T100 [P] Add Arabic empty-state components in `resources/views/components/empty-state.blade.php`
- [x] T101 Eager-load/prevent N+1 on student home and course lists in Student controllers/services
- [x] T102 Security pass: CSRF, rate limiting on auth/API, secure headers middleware in `bootstrap/app.php` / `app/Http/Middleware/`
- [x] T103 Run and document `quickstart.md` Scenarios A-F; fix gaps found - automated quickstart smoke passed (browser UAT still recommended)
- [x] T104 [P] Update README with setup, queue worker, and Arabic RTL notes in `README.md`

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies — start immediately
- **Foundational (Phase 2)**: Depends on Setup — **BLOCKS all user stories**
- **User Stories (Phases 3–11)**: Depend on Foundational
  - Prefer P1 order: US1 → US2 → US3, then P2 (US4–US7), then P3 (US8–US9)
  - US8 (admin user/permission UI) helps operations but US1 can use seeded `enrollments.approve` from Foundational until US8 lands
- **Polish (Phase 12)**: After desired stories complete (minimum: Setup + Foundational + US1)

### User Story Dependencies

| Story | Depends on | Notes |
|-------|------------|-------|
| US1 Student | Foundational | MVP; uses seeded approver permission |
| US2 Instructor | Foundational (+ Course/Lesson schema from US1 ideally) | Can start after T021–T024 if parallelizing carefully |
| US3 RBAC hardening | Foundational + dashboard route groups existing | Best after US1/US2 routes exist |
| US4 Finance | Foundational | Independent domain tables |
| US5 Marketing | Foundational | Independent |
| US6 Team | Foundational | Independent |
| US7 Support | Foundational; integrates student tickets from US1 | |
| US8 Super Admin | Foundational | Enhances ops for all |
| US9 Public | Foundational auth pages | Catalog benefits from Course model (US1) |

### Within Each User Story

- Models/migrations before services
- Services before controllers/views
- Routes after controllers
- Story complete before next priority when staffing is serial

### Parallel Opportunities

- Phase 1: T002–T005 in parallel
- Phase 2: T010, T014, T016, T017 in parallel after T009
- US1: T021–T024 models/migrations in parallel; T034–T035 views in parallel
- After Foundational: US4, US5, US6 can proceed in parallel by different developers
- Polish: T097–T098, T104 in parallel

---

## Parallel Example: User Story 1

```bash
# Parallel migrations/models:
Task: "T021 Create learning/enrollment migrations in database/migrations/"
Task: "T022 Create quiz/calendar/announcement migrations in database/migrations/"
Task: "T023 Create Course/Enrollment/Lesson models in app/Models/"
Task: "T024 Create Quiz/Attempt models in app/Models/"

# Later parallel student pages:
Task: "T034 Build Progress/Notifications/Calendar pages under resources/views/student/"
Task: "T035 Build Profile + Settings under resources/views/student/"
```

---

## Parallel Example: Staff dashboards (after Foundational)

```bash
Task: "US4 Finance migrations + pages (T057–T064)"
Task: "US5 Marketing migrations + pages (T065–T071)"
Task: "US6 Team migrations + pages (T072–T077)"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Complete Phase 1: Setup  
2. Complete Phase 2: Foundational  
3. Complete Phase 3: US1 Student Learning Loop  
4. **STOP and VALIDATE** using `quickstart.md` Scenarios B–C  
5. Demo Student request/approve + lesson/quiz loop  

### Incremental Delivery

1. Setup + Foundational → foundation ready  
2. US1 → MVP learning platform  
3. US2 → teaching content ops  
4. US3 → security hardening  
5. US4–US7 → staff dashboards (parallelizable)  
6. US8 → admin control plane  
7. US9 → public acquisition polish  
8. Phase 12 polish → production readiness  

### Parallel Team Strategy

1. Team completes Setup + Foundational together  
2. Dev A: US1 → US3  
3. Dev B: US2  
4. Dev C: US4/US5  
5. Dev D: US6/US7  
6. Then US8/US9 + polish  

---

## Notes

- [P] = different files, no incomplete-task dependencies
- [USn] maps to spec user stories for traceability
- All UI Arabic RTL; never add a language switcher
- Private media only via authorized controllers (`local_private`)
- Payment gateways intentionally out of task scope (Phase 2 product)
- Commit after each task or logical group
- Stop at checkpoints to validate independently
