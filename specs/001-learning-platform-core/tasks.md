# Tasks: Learning Platform Core

**Input**: Design documents from `/specs/001-learning-platform-core/`  
**Context**: Stakeholder Super Admin Dashboard depth (full control plane + modular page template) provided to `/speckit-tasks`

**Prerequisites**: plan.md (required), spec.md (required for user stories), research.md, data-model.md, contracts/, quickstart.md

**Tests**: Not included — feature specification did not explicitly request TDD/contract test tasks. Validate via `quickstart.md` scenarios during polish.

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story. Phases 1–12 are the completed baseline (T001–T104). Phase 13 expands **US8 Super Admin** to the full platform control plane described by the stakeholder (Dashboard Home cards/charts, Students→System Logs navigation, page-spec template, module boundaries). Finance remains its own dashboard (US4); Super Admin links/overviews only—no duplicate Finance rebuild.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

## Path Conventions

- Laravel modular monolith per `plan.md`: `app/`, `modules/`, `resources/views/`, `routes/`, `database/`, `tests/`
- Super Admin UI under `resources/views/admin/` and `app/Http/Controllers/Web/Admin/`
- Domain logic in `modules/{Students,Catalog,Quizzes,Finance,Marketing,Team,Admin,Reports}/`

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

## Phase 10: User Story 8 — Super Admin Platform Control — Baseline (Priority: P3)

**Goal**: Super Admin controls users, roles, permissions, logs, settings, storage/queues/jobs, notifications/emails, audit, backups, security, monitoring

**Independent Test**: Admin creates student; assigns `enrollments.approve` to a role; audit log records the change; ops pages show queue/job health

### Implementation for User Story 8 (baseline — completed)

- [x] T084 [US8] Build Users CRUD (including admin-created students + invite/activation) in `app/Http/Controllers/Web/Admin/UserController.php` and `resources/views/admin/users/`
- [x] T085 [US8] Build Roles & Permissions UI (assign `enrollments.approve`) in `app/Http/Controllers/Web/Admin/RoleController.php` and `resources/views/admin/roles/`
- [x] T086 [P] [US8] Implement `AuditLog` model/migration and writer in `app/Models/AuditLog.php` and `app/Services/AuditLogger.php`
- [x] T087 [P] [US8] Build Logs + Audit Logs pages under `resources/views/admin/logs/`
- [x] T088 [P] [US8] Build Settings, Storage, Queues, Jobs, Monitoring pages under `resources/views/admin/ops/`
- [x] T089 [P] [US8] Build Notifications/Emails ops visibility pages under `resources/views/admin/comms/`
- [x] T090 [US8] Build Backups + Security overview actions/pages under `resources/views/admin/security/`
- [x] T091 [US8] Register `/admin/*` routes locked to super_admin in `routes/web.php`

**Checkpoint**: Control plane baseline can provision users and permissions

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

## Phase 12: Polish & Cross-Cutting Concerns (Baseline)

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

**Checkpoint**: Baseline platform production-ready for MVP surfaces

---

## Phase 13: User Story 8 — Super Admin Dashboard Expansion (Priority: P3) 🎯 Next Wave

**Goal**: Make Super Admin the full platform control plane so one role can manage Students, Courses, Categories, Lessons, Quizzes, Assignments, Orders, Payments, Coupons, Teachers, Academic Years, Semesters, Groups, Telegram, Announcements, Reports, Marketing overview, Support overview, Team overview, Settings (tabbed), and System Logs—without needing other dashboards for day-to-day ops. Finance stays a dedicated dashboard (US4); Admin provides deep-link/overview only. Every Admin page follows a fixed 13-section page template. Code is organized by independent modules.

**Independent Test**: Super Admin can open `/admin` and within ~30s read platform health (cards + charts); CRUD a student with filters/bulk actions; manage a course with tabbed detail; create category/lesson/quiz; verify payment/order actions; configure settings tabs; inspect system logs—all without leaving `/admin/*`. Non–super-admin roles still denied.

### Foundational for expansion (module shell + page template + nav)

- [x] T105 [US8] Add `modules/Students/` PSR-4 module (Provider + empty Services) and map in `composer.json` / `bootstrap/providers.php`
- [x] T106 [P] [US8] Add `modules/Settings/` PSR-4 module (Provider + Services) and map in `composer.json` / `bootstrap/providers.php`
- [x] T107 [US8] Create Super Admin page-spec template markdown at `specs/001-learning-platform-core/admin-page-template.md` (Purpose, Navigation, Permissions, UI Components, Actions, Filters & Search, Validation Rules, Business Rules, Notifications, Reports, Database Tables, Audit Logs, Future Enhancements)
- [x] T108 [US8] Build shared Admin RTL layout + sidebar nav (Dashboard→System Logs) in `resources/views/layouts/admin.blade.php` and `resources/views/admin/partials/sidebar.blade.php`
- [x] T109 [P] [US8] Build reusable Admin UI primitives (data table, filter bar, KPI card, chart shell, bulk-action bar, tab nav, empty/forbidden states) under `resources/views/components/admin/`
- [x] T110 [US8] Expand Super Admin page matrix in `specs/001-learning-platform-core/spec.md` and route inventory in `contracts/ui-surfaces.md` to match stakeholder nav list
- [x] T111 [P] [US8] Seed Super Admin permissions (`admin.students.*`, `admin.courses.*`, `admin.settings.*`, etc.) in `database/seeders/RbacSeeder.php`

### Schema for Admin-managed catalog entities

- [x] T112 [P] [US8] Create migrations for `categories`, `academic_years`, `semesters`, `groups`, `group_user` in `database/migrations/`
- [x] T113 [P] [US8] Create migrations for `orders`, `order_items`, `telegram_groups`, `assignments`, `assignment_submissions` (if missing) in `database/migrations/`
- [x] T114 [P] [US8] Add FK columns on `courses`/`users` for `category_id`, `academic_year_id`, `semester_id`, `group_id` (nullable) in `database/migrations/`
- [x] T115 [P] [US8] Create models `Category`, `AcademicYear`, `Semester`, `Group`, `Order`, `TelegramGroup`, `Assignment` in `app/Models/`
- [x] T116 [US8] Document new entities in `specs/001-learning-platform-core/data-model.md`

### Dashboard Home (overview in <30s)

- [x] T117 [US8] Implement `AdminDashboardStatsService` (students, courses, categories, lessons, videos, orders, subscriptions, revenue totals/today/month, quizzes, DAU, tickets, unread notifications) with Redis cache in `modules/Admin/Services/AdminDashboardStatsService.php`
- [x] T118 [US8] Expand `app/Http/Controllers/Web/Admin/HomeController.php` + `resources/views/admin/home.blade.php` with KPI cards, Alpine/Chart.js charts (Revenue, Student Growth, Sales, DAU, Quiz Attempts, Subscriptions), quick actions, recent activity feeds
- [x] T119 [P] [US8] Add Home actions: refresh, date filter, enqueue PDF/Excel export in `app/Http/Controllers/Web/Admin/DashboardExportController.php` and `app/Jobs/ExportAdminDashboardJob.php`
- [x] T120 [US8] Write page spec for Admin Home using template sections in `specs/001-learning-platform-core/pages/admin/dashboard.md`

### Students Module

- [x] T121 [US8] Implement `StudentAdminService` (search, filters, suspend/activate, reset password, assign/remove course, discount, notify/email, bulk ops) in `modules/Students/Services/StudentAdminService.php`
- [x] T122 [US8] Build Students index table (avatar, name, phone, email, university, group, status, registered_at, last_login, subscription) with search/filters in `app/Http/Controllers/Web/Admin/StudentController.php` and `resources/views/admin/students/index.blade.php`
- [x] T123 [P] [US8] Add Student Form Requests + Policies in `app/Http/Requests/Admin/Student*` and `app/Policies/StudentAdminPolicy.php`
- [x] T124 [US8] Implement student actions (Add/Edit/View/Delete/Suspend/Activate/Reset Password/Login As/Send Notification/Email/Assign Course/Remove Course/Add Discount/Export CSV/Excel) under `app/Http/Controllers/Web/Admin/StudentController.php` and `app/Http/Controllers/Web/Admin/ImpersonationController.php`
- [x] T125 [US8] Build Student Profile tabs (Overview, Courses, Payments, Quizzes, Progress, Attendance, Notifications, Orders, Activity Logs, Notes) in `resources/views/admin/students/show.blade.php` and tab partials under `resources/views/admin/students/tabs/`
- [x] T126 [P] [US8] Implement bulk actions endpoint in `app/Http/Controllers/Web/Admin/StudentBulkController.php`
- [x] T127 [US8] Write Students page spec in `specs/001-learning-platform-core/pages/admin/students.md` and audit-log sensitive student actions via `app/Services/AuditLogger.php`

### Courses Module

- [x] T128 [US8] Extend `modules/Catalog/Services/` (or Teaching service) with admin course ops: archive, duplicate, publish, hide, assign teacher/semester in `modules/Catalog/Services/CourseAdminService.php`
- [x] T129 [US8] Expand Courses table + CRUD actions in `app/Http/Controllers/Web/Admin/CourseController.php` and `resources/views/admin/courses/` (name, image, term, year, teacher, price, student/lesson counts, status)
- [x] T130 [US8] Build Course detail tabs (General, Lessons, Files, Videos, Quizzes, Assignments, Students, Analytics, Reviews, Settings) under `resources/views/admin/courses/show.blade.php` and `resources/views/admin/courses/tabs/`
- [x] T131 [P] [US8] Write Courses page spec in `specs/001-learning-platform-core/pages/admin/courses.md`

### Lessons Module

- [x] T132 [US8] Expand Lesson admin service (lock/unlock, move, duplicate, reorder, schedule publish, attach quiz, upload video/PDF/files) in `modules/Catalog/Services/LessonAdminService.php`
- [x] T133 [US8] Expand Lessons CRUD + actions in `app/Http/Controllers/Web/Admin/LessonController.php` and `resources/views/admin/lessons/`
- [x] T134 [US8] Build Lesson detail sections (General, Video, Files, Resources, Quiz, Notes, Comments, Settings) under `resources/views/admin/lessons/show.blade.php`
- [x] T135 [P] [US8] Write Lessons page spec in `specs/001-learning-platform-core/pages/admin/lessons.md`

### Categories

- [x] T136 [P] [US8] Build Categories CRUD + merge/archive/restore in `app/Http/Controllers/Web/Admin/CategoryController.php`, `modules/Catalog/Services/CategoryService.php`, and `resources/views/admin/categories/`
- [x] T137 [P] [US8] Write Categories page spec in `specs/001-learning-platform-core/pages/admin/categories.md`

### Quizzes Module

- [x] T138 [US8] Implement quiz admin ops (duplicate, publish/unpublish, assign course/lesson, import/export questions, randomize) in `modules/Quizzes/Services/QuizAdminService.php`
- [x] T139 [US8] Build Quizzes list + actions in `app/Http/Controllers/Web/Admin/QuizController.php` and `resources/views/admin/quizzes/`
- [x] T140 [US8] Build Quiz detail tabs (Questions, Attempts, Statistics, Settings, Results, Leaderboard) under `resources/views/admin/quizzes/show.blade.php`
- [x] T141 [P] [US8] Write Quizzes page spec in `specs/001-learning-platform-core/pages/admin/quizzes.md`

### Assignments

- [x] T142 [P] [US8] Build Assignments admin CRUD + review list in `app/Http/Controllers/Web/Admin/AssignmentController.php` and `resources/views/admin/assignments/`
- [x] T143 [P] [US8] Write Assignments page spec in `specs/001-learning-platform-core/pages/admin/assignments.md`

### Orders & Payments (Admin ops; Finance dashboard remains system of record for deep finance)

- [x] T144 [US8] Implement Order admin actions (approve/reject/refund/invoice PDF/export) in `modules/Finance/Services/OrderAdminService.php` and `app/Http/Controllers/Web/Admin/OrderController.php` + `resources/views/admin/orders/`
- [x] T145 [US8] Expand Payments admin (verify/approve/reject/refund/manual payment/filter/export) in `app/Http/Controllers/Web/Admin/PaymentController.php` and `resources/views/admin/payments/`
- [x] T146 [P] [US8] Write Orders + Payments page specs in `specs/001-learning-platform-core/pages/admin/orders.md` and `pages/admin/payments.md`
- [x] T147 [US8] Add Admin → Finance deep-link overview card (no duplicate Finance UI) in `resources/views/admin/finance/overview.blade.php` and `app/Http/Controllers/Web/Admin/FinanceOverviewController.php`

### Coupons (Admin + Marketing module shared service)

- [x] T148 [US8] Build Coupons admin actions (create/edit/delete/activate/deactivate/generate/duplicate/limit/assign course/student) reusing `MarketingService` in `app/Http/Controllers/Web/Admin/CouponController.php` and `resources/views/admin/coupons/`
- [x] T149 [P] [US8] Write Coupons page spec in `specs/001-learning-platform-core/pages/admin/coupons.md`

### Teachers

- [x] T150 [US8] Build Teachers admin (add/edit/delete/suspend/assign courses/lessons/view analytics) in `app/Http/Controllers/Web/Admin/TeacherController.php`, `modules/Teaching/Services/TeacherAdminService.php`, and `resources/views/admin/teachers/`
- [x] T151 [P] [US8] Write Teachers page spec in `specs/001-learning-platform-core/pages/admin/teachers.md`

### Academic Years, Semesters, Groups

- [x] T152 [P] [US8] Build Academic Years CRUD in `app/Http/Controllers/Web/Admin/AcademicYearController.php` and `resources/views/admin/academic-years/`
- [x] T153 [P] [US8] Build Semesters CRUD in `app/Http/Controllers/Web/Admin/SemesterController.php` and `resources/views/admin/semesters/`
- [x] T154 [US8] Build Groups CRUD + member attach in `app/Http/Controllers/Web/Admin/GroupController.php` and `resources/views/admin/groups/`
- [x] T155 [P] [US8] Write Academic Years/Semesters/Groups page specs under `specs/001-learning-platform-core/pages/admin/`

### Telegram & Announcements

- [x] T156 [US8] Build Telegram admin (create/attach group, generate/expire invite, send announcement) in `app/Http/Controllers/Web/Admin/TelegramController.php`, `modules/Notifications/Services/TelegramAdminService.php`, and `resources/views/admin/telegram/`
- [x] T157 [US8] Build Announcements admin (create/schedule/publish/draft/delete/notify/pin/archive) in `app/Http/Controllers/Web/Admin/AnnouncementController.php` and `resources/views/admin/announcements/`
- [x] T158 [P] [US8] Write Telegram + Announcements page specs under `specs/001-learning-platform-core/pages/admin/`

### Reports, Marketing overview, Support overview, Team overview

- [x] T159 [US8] Build Admin Reports hub (Students/Revenue/Course/Quiz/Teacher/Attendance/Activity/Finance) with PDF/Excel/schedule/email in `app/Http/Controllers/Web/Admin/ReportController.php`, `modules/Reports/Services/AdminReportService.php`, and `resources/views/admin/reports/`
- [x] T160 [P] [US8] Build Marketing overview (referral/campaigns/coupons/discounts/landing/UTM/analytics/conversion + campaign pause/resume) in `app/Http/Controllers/Web/Admin/MarketingOverviewController.php` and `resources/views/admin/marketing/`
- [x] T161 [P] [US8] Build Support overview (tickets queue deep-link + unread counts) in `app/Http/Controllers/Web/Admin/SupportOverviewController.php` and `resources/views/admin/support/`
- [x] T162 [P] [US8] Build Team overview (members/tasks/roles/meetings/performance/goals deep-links) in `app/Http/Controllers/Web/Admin/TeamOverviewController.php` and `resources/views/admin/team/`
- [x] T163 [P] [US8] Write Reports/Marketing/Support/Team Admin page specs under `specs/001-learning-platform-core/pages/admin/`

### Settings Module (tabbed)

- [x] T164 [US8] Implement `PlatformSettingsService` (get/set typed settings + audit) in `modules/Settings/Services/PlatformSettingsService.php` and `platform_settings` migration/model
- [x] T165 [US8] Build Settings page with tabs (General, Platform, Authentication, Email, Telegram, Payments, Media, Storage, Cache, Queue, SEO, Theme, Languages note: Arabic-fixed, Security, Backup, Maintenance, Logs) in `app/Http/Controllers/Web/Admin/SettingsController.php` and `resources/views/admin/settings/`
- [x] T166 [P] [US8] Write Settings page spec in `specs/001-learning-platform-core/pages/admin/settings.md`

### System Logs

- [x] T167 [US8] Expand System Logs hub (Activity, Authentication, Payment, Errors, Queue, Mail, Audit) in `app/Http/Controllers/Web/Admin/SystemLogController.php` and `resources/views/admin/system-logs/`
- [x] T168 [P] [US8] Ensure log writers exist for auth/payment/mail/queue failures under `app/Services/Logging/` and wire AuditLogger for privileged Admin mutations
- [x] T169 [P] [US8] Write System Logs page spec in `specs/001-learning-platform-core/pages/admin/system-logs.md`

### Routes & wiring

- [x] T170 [US8] Register all new `/admin/*` routes (Students→System Logs) with `dashboard:admin` middleware in `routes/web.php`
- [x] T171 [US8] Update Admin sidebar active states + Arabic labels for full nav in `resources/views/admin/partials/sidebar.blade.php`
- [x] T172 [US8] Seed demo Admin data (categories, years, semesters, groups, sample orders) in `database/seeders/AdminDemoSeeder.php` and wire into `DatabaseSeeder.php`

**Checkpoint**: Super Admin can operate the full platform control plane from `/admin` alone; Finance/Marketing/Team deep work still available on their dedicated dashboards

---

## Phase 14: Polish — Super Admin Expansion

**Purpose**: Cross-cutting polish for the expanded Admin surface

- [x] T173 Cache Admin dashboard aggregates with scheduled refresh in `routes/console.php` and `modules/Admin/Services/AdminDashboardStatsService.php`
- [x] T174 [P] Add N+1 guards / eager loads on Admin Students/Courses/Quizzes index controllers
- [x] T175 [P] Ensure all privileged Admin mutations write AuditLog entries (spot-check Students, Roles, Settings, Payments)
- [x] T176 Queue large Admin exports (dashboard PDF/Excel, student CSV, reports) via existing `GenerateReportJob` / dedicated jobs under `app/Jobs/`
- [x] T177 [P] Arabic empty/forbidden/pending states on every new Admin index page using `resources/views/components/empty-state.blade.php`
- [x] T178 Update `specs/001-learning-platform-core/quickstart.md` with Super Admin Scenario G (home cards → student CRUD → course tabs → settings → logs)
- [x] T179 Run Scenario G smoke path and fix gaps; update `README.md` Admin module map

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: Complete
- **Foundational (Phase 2)**: Complete — blocked all stories
- **User Stories US1–US9 (Phases 3–11)**: Complete (baseline)
- **Polish baseline (Phase 12)**: Complete
- **Phase 13 (US8 expansion)**: Depends on Phases 1–12; **BLOCKS** remaining Admin depth
  - T105–T111 before page implementations
  - T112–T116 before entity-backed CRUD pages
  - Dashboard Home (T117–T120) can start after T108–T109
  - Students/Courses/Lessons/Quizzes can proceed in parallel after schema + primitives
  - Settings + System Logs can proceed in parallel with catalog pages
- **Phase 14**: Depends on Phase 13 completion (or last desired Admin pages)

### User Story Dependencies

| Story | Depends on | Notes |
|-------|------------|-------|
| US1 Student | Foundational | MVP — complete |
| US2 Instructor | Foundational + Course schema | Complete |
| US3 RBAC hardening | Foundational + routes | Complete |
| US4 Finance | Foundational | Complete — remains dedicated dashboard |
| US5 Marketing | Foundational | Complete — Admin reuses services |
| US6 Team | Foundational | Complete — Admin overview only |
| US7 Support | Foundational + US1 tickets | Complete — Admin overview only |
| US8 Super Admin | Foundational; expansion uses US4–US7 services | Baseline complete; **Phase 13 is next** |
| US9 Public | Foundational | Complete |

### Within Phase 13

1. Modules + layout + page template + permissions  
2. Schema/models  
3. Parallel page groups (Students ‖ Courses/Lessons/Categories ‖ Quizzes/Assignments ‖ Orders/Payments/Coupons ‖ Teachers/Years/Semesters/Groups ‖ Telegram/Announcements ‖ Reports/overviews ‖ Settings/Logs)  
4. Routes + seeder  

### Parallel Opportunities

```bash
# After T105–T116 + T108–T109:
Task: "T121–T127 Students module UI"
Task: "T128–T135 Courses + Lessons"
Task: "T136–T137 Categories"
Task: "T138–T143 Quizzes + Assignments"
Task: "T144–T149 Orders/Payments/Coupons"
Task: "T150–T155 Teachers + Academic structure"
Task: "T156–T158 Telegram + Announcements"
Task: "T159–T163 Reports + overviews"
Task: "T164–T169 Settings + System Logs"
```

---

## Parallel Example: User Story 8 Expansion

```bash
# Schema in parallel:
Task: "T112 Create categories/academic_years/semesters/groups migrations"
Task: "T113 Create orders/telegram_groups/assignments migrations"
Task: "T115 Create Category/AcademicYear/Semester/Group/Order models"

# Later parallel Admin pages:
Task: "T122 Build Students index under resources/views/admin/students/"
Task: "T129 Expand Courses admin under resources/views/admin/courses/"
Task: "T139 Build Quizzes admin under resources/views/admin/quizzes/"
Task: "T165 Build Settings tabs under resources/views/admin/settings/"
```

---

## Implementation Strategy

### MVP First (already delivered)

1. Phases 1–3 complete → Student learning MVP  
2. Remaining baseline stories complete → multi-surface platform  

### Next incremental delivery (recommended)

1. Phase 13 foundational (T105–T116) → Admin shell + schema  
2. Dashboard Home + Students (T117–T127) → first expanded demo  
3. Courses + Lessons + Categories + Quizzes → content control plane  
4. Orders/Payments/Coupons + Teachers/Academic structure → commerce & org  
5. Settings + System Logs + Reports → ops maturity  
6. Phase 14 polish → Scenario G validated  

### Parallel Team Strategy

1. Dev A: Students module (T121–T127)  
2. Dev B: Catalog (Courses/Lessons/Categories)  
3. Dev C: Quizzes/Assignments + Teachers/Academic  
4. Dev D: Orders/Payments/Coupons + Finance overview  
5. Dev E: Settings + System Logs + Telegram/Announcements  
6. Shared: T105–T111 shell, then T170–T172 routes/seeder  

---

## Notes

- [P] = different files, no incomplete-task dependencies
- [USn] maps to spec user stories for traceability
- All UI Arabic RTL; never add a language switcher (Settings “Languages” tab documents Arabic-fixed policy only)
- Private media only via authorized controllers (`local_private`)
- Payment gateways remain Phase 2 product; Admin Payments = manual verify/approve/refund of recorded payments
- Finance Dashboard (US4) is intentionally separate — Admin Finance nav is overview/deep-link only
- Every new Admin page must ship with a page-spec file using the 13-section template (T107)
- Commit after each task or logical group
- Stop at checkpoints to validate independently
