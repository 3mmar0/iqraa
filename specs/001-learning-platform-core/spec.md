# Feature Specification: Learning Platform Core

**Feature Branch**: `001-learning-platform-core`

**Created**: 2026-07-31

**Status**: Draft

**Input**: User description: "Multi-surface educational platform (Public Website, Student/Instructor/Team/Finance/Marketing/Support/Super Admin Dashboards + API) with per-dashboard login, permissions, pages, data, notifications, and reports; detailed Student dashboard as the reference depth model; monolithic modular delivery direction."

## Clarifications

### Session 2026-07-31

- Q: In the first release, how should a student get access to a course? → A: Student submits a course access request; an admin approves it; only then does the student receive the course (enrollment/entitlement).
- Q: How does a new student get an account before they can request a course? → A: Both self-registration on the public website and admin-created student accounts are allowed.
- Q: Which surfaces must ship in the first production release? → A: All surfaces in v1 (Public Website, Student, Instructor, Team, Finance, Marketing, Support, Super Admin, and API).
- Q: How should users sign in across the different dashboards? → A: One shared login; after authentication the user is routed to an allowed dashboard (dashboard picker when the user has multiple roles).
- Q: Who is allowed to approve or reject a student’s course access request? → A: Any staff role granted a configurable “approve enrollment” permission (not hard-coded to a single dashboard role).

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Student Learning Loop (Priority: P1)

A enrolled student signs in to the Student Dashboard, continues from the last watched lesson, completes lessons with materials and notes, takes quizzes, and tracks progress and upcoming events with minimal navigation.

**Why this priority**: Students spend ~90% of platform time here; this is the primary product value and revenue-retention surface.

**Independent Test**: With a seeded enrolled student, complete login → home → course → lesson → mark complete → quiz → progress/calendar without needing other dashboards.

**Acceptance Scenarios**:

1. **Given** an enrolled student with incomplete courses, **When** they open Dashboard Home, **Then** they see their name, avatar, current term, enrolled courses, last watched lesson, progress %, upcoming quizzes, announcements, recent notifications, and quick actions.
2. **Given** a student with multiple enrollments, **When** they open My Courses, **Then** each course card shows name, instructor, image, lesson counts, completed lessons, progress bar, and an enter action.
3. **Given** a student inside a course, **When** they open a lesson, **Then** they can play the video, read description, open PDF/attachments, take notes, view comments, navigate next/previous, and mark the lesson completed.
4. **Given** an available quiz, **When** the student starts and submits it, **Then** they see score, their answers, correct answers, and a performance summary.
5. **Given** a student with no enrollments, **When** they open My Courses, **Then** they see an empty state (not an error) guiding next steps.
6. **Given** a student without access to a published course, **When** they submit a course access request and a user with approve-enrollment permission approves it, **Then** the course appears in My Courses and lesson/quiz access becomes available.
7. **Given** a student submitted a course access request that is still pending, **When** they view request status, **Then** they see pending (not enrolled) and cannot open private course media.

---

### User Story 2 - Instructor Teaching Operations (Priority: P1)

An instructor manages courses, lessons, videos, assignments, quizzes, live sessions, students, announcements, messages, calendar, and teaching analytics from a dedicated Instructor Dashboard.

**Why this priority**: Without instructor tooling, student learning content cannot be created or operated.

**Independent Test**: Instructor can create/manage a course path (lessons, quiz, announcement) and view student list/reports without using Super Admin or Finance.

**Acceptance Scenarios**:

1. **Given** an authenticated instructor, **When** they open Instructor Dashboard, **Then** they see an operational overview of their teaching workload (students, courses, upcoming sessions, alerts).
2. **Given** an instructor owns a course, **When** they manage lessons/videos/assignments/quizzes, **Then** only their authorized courses and related students are accessible.
3. **Given** an instructor needs to communicate, **When** they send announcements or messages, **Then** targeted students receive in-app notifications (and email when configured).

---

### User Story 3 - Secure Role Separation Across Dashboards (Priority: P1)

Each role (Student, Instructor, Team, Finance, Marketing, Support, Super Admin) has its own dashboard surface, permission set, pages, notifications, and reports. Users authenticate via one shared login, then only see surfaces and actions allowed by their roles/permissions (with a dashboard picker when multiple roles apply).

**Why this priority**: Cross-role leakage (e.g., students seeing finance or admin ops) is a critical security and trust failure.

**Independent Test**: Attempt cross-dashboard access with each role; unauthorized routes show access denied and no sensitive data is returned.

**Acceptance Scenarios**:

1. **Given** a student account, **When** they attempt to open Finance or Super Admin pages, **Then** access is denied.
2. **Given** a user with multiple roles, **When** they sign in, **Then** they see a dashboard picker (or equivalent chooser) limited to dashboards granted by assigned roles/permissions and can only open those.
3. **Given** a user with a single role, **When** they sign in successfully, **Then** they land directly on that role’s dashboard without an unnecessary picker.
4. **Given** any dashboard user, **When** they sign out or session expires, **Then** protected pages require re-authentication through the shared login.

---

### User Story 4 - Finance Independence (Priority: P2)

Finance staff operate a fully independent Finance Dashboard covering revenue, expenses, transactions, subscriptions, refunds, payroll, reports, forecast, and profit views.

**Why this priority**: Money operations must be isolatable for accountability and day-to-day business control.

**Independent Test**: Finance user can review overview, filter transactions, process/record refunds workflow, and export a report without Instructor or Marketing tools.

**Acceptance Scenarios**:

1. **Given** a finance user, **When** they open Overview, **Then** they see high-level revenue/expense/profit indicators for a selected period.
2. **Given** recorded payments and refunds, **When** they open Transactions/Refunds, **Then** they can search, filter, and inspect status history.
3. **Given** subscription products, **When** they open Subscriptions, **Then** they can see active, expired, and canceled states.

---

### User Story 5 - Marketing Growth Engine (Priority: P2)

Marketing operates an independent Marketing Dashboard for campaigns, coupons, referrals, student ambassadors, leads, conversion, and analytics.

**Why this priority**: Growth and acquisition are separate from teaching and finance workflows and need dedicated ownership.

**Independent Test**: Marketing user creates a coupon/campaign and reviews lead/conversion metrics without accessing payroll or role administration.

**Acceptance Scenarios**:

1. **Given** a marketing user, **When** they manage Campaigns and Coupons, **Then** they can create, edit, activate/deactivate, and measure usage.
2. **Given** referral and ambassador programs, **When** they open Referral/Student Ambassadors, **Then** they can track participants and attributed outcomes.
3. **Given** inbound interest, **When** they open Leads/Conversion, **Then** they see pipeline stages and conversion rates for a period.

---

### User Story 6 - Internal Team Coordination (Priority: P2)

Internal team members use a Team Dashboard to view personal tasks, announcements, files, meetings, goals, reports, and attendance—capability uncommon in typical LMS products but required for internal operations.

**Why this priority**: Improves execution across non-teaching staff without overloading Support or Super Admin.

**Independent Test**: Team member sees only their assigned tasks/meetings/files and can mark task progress; managers with permission see team-level reports/attendance.

**Acceptance Scenarios**:

1. **Given** a team member with assigned tasks, **When** they open Tasks, **Then** they see due dates, status, and can update allowed fields.
2. **Given** published team announcements/files/meetings, **When** they open those pages, **Then** content visible matches their membership/permissions.
3. **Given** attendance tracking is enabled, **When** they open Attendance, **Then** their attendance records (or team records if permitted) are visible.

---

### User Story 7 - Support Resolution (Priority: P2)

Support agents manage tickets, live chat, student context lookup, FAQ content, and support reports from the Support Dashboard.

**Why this priority**: Reduces student friction and protects retention when learning or billing issues occur.

**Independent Test**: Agent opens a ticket, replies, closes it, and updates FAQ; student can create a ticket from Student Support and see status.

**Acceptance Scenarios**:

1. **Given** a student-created ticket, **When** a support agent opens Tickets, **Then** they can assign, reply, change status, and close.
2. **Given** an active chat request, **When** an agent handles Live Chat, **Then** conversation history is retained on the ticket/student record as applicable.
3. **Given** FAQ entries, **When** students open Support FAQ, **Then** published articles are searchable and readable.

---

### User Story 8 - Super Admin Platform Control (Priority: P3)

Super Admins govern users, roles, permissions, logs, platform settings, storage, queues/jobs health, notifications/emails configuration visibility, audit logs, backups, security, and monitoring—full project control plane.

**Why this priority**: Essential for operations, but daily learning value does not depend on exposing these controls to other roles.

**Independent Test**: Super Admin can create a role, assign permissions, inspect audit log for a sensitive action, and review system health widgets.

**Acceptance Scenarios**:

1. **Given** a Super Admin, **When** they manage Users/Roles/Permissions, **Then** changes take effect on subsequent authorization checks.
2. **Given** sensitive admin actions occur, **When** they open Audit Logs, **Then** actor, action, timestamp, and target are recorded.
3. **Given** background processing is used, **When** they open Queues/Jobs/Monitoring, **Then** they can observe job status and failure signals.

---

### User Story 9 - Public Website Entry (Priority: P3)

Visitors use the Public Website to discover the platform/offering, register or sign in, and reach the correct dashboard after authentication. All role dashboards and the API are in scope for the first production release alongside this entry surface.

**Why this priority**: Required for acquisition, but core learning value is inside authenticated dashboards.

**Independent Test**: Visitor browses public pages, registers/logs in, and lands in the dashboard matching their role.

**Acceptance Scenarios**:

1. **Given** a visitor, **When** they open the public site, **Then** they can view marketing/discovery content without authentication.
2. **Given** valid credentials, **When** they sign in via the shared login, **Then** they are routed to their sole allowed dashboard, or to a role/dashboard picker if they have multiple allowed dashboards.
3. **Given** invalid credentials, **When** they attempt sign in, **Then** they see a clear error and are not authenticated.
4. **Given** a visitor on the public website, **When** they complete self-registration (including required verification), **Then** they obtain a student account that can sign in and submit course access requests.
5. **Given** an authorized admin, **When** they create a student account, **Then** that student can sign in (after any required activation/password setup) and submit course access requests like self-registered students.
6. **Given** a staff user without approve-enrollment, **When** they open the course-request queue, **Then** they cannot approve or reject requests.
7. **Given** Super Admin grants approve-enrollment to a staff role, **When** a user of that role reviews a pending request, **Then** they can approve or reject and the student is notified.

---

### Edge Cases

- Student with enrollment revoked mid-course: course/lesson/quiz access is blocked; progress history retained for audit/support.
- Quiz time expires mid-attempt: attempt auto-submits with answered questions only; student sees timed-out result state.
- User has no role assigned: authentication may succeed but no dashboard pages are authorized; show safe “contact support” state.
- Empty dashboards (no courses, no tickets, no campaigns): show explicit empty states, never blank failures.
- Concurrent lesson progress updates: last successful completion write wins; progress never exceeds 100%.
- Refund after course access granted: finance refund workflow triggers enrollment/access policy consistent with business rules (access revoked or retained per refund policy).
- Course access request rejected by a reviewer with approve-enrollment permission: student remains without enrollment; sees rejected status and may submit a new request only if business rules allow.
- Course access request still pending: student cannot access private lessons/files/quizzes for that course.
- Duplicate pending request for the same student and course: system rejects or coalesces to a single pending request (no duplicate active pendings).
- Admin-created student with incomplete activation (no password set / invite unused): cannot sign in until activation completes; clear guidance shown on login failure where applicable.
- Notification channel failure (email/Telegram): in-app notification still stored; failure is logged for operations.
- Large report export requested: user receives deferred completion notification rather than a hanging page.
- Soft-deleted records: hidden from normal lists; recoverable only by authorized admin flows where supported.

## Requirements *(mandatory)*

### Functional Requirements

#### Platform & Access

- **FR-001**: System MUST provide distinct surfaces: Public Website, Student Dashboard, Instructor Dashboard, Team Dashboard, Finance Dashboard, Marketing Dashboard, Support Dashboard, Super Admin Dashboard, and a programmatic API surface for platform operations.
- **FR-001b**: The first production release MUST include all surfaces listed in FR-001 (no dashboard deferred out of v1). Delivery may still prioritize High-priority pages within each dashboard, but each surface MUST be present and usable for its primary actors.
- **FR-001a**: The Public Website and all dashboards MUST present a fully Arabic user interface (RTL). Product labels, system messages, notifications, and emails are Arabic in v1; no product language switcher.
- **FR-002**: Each dashboard MUST support authenticated access (via the shared login), role/permission checks, its own page set, related data visibility, notifications, and reports appropriate to that dashboard’s purpose.
- **FR-002a**: System MUST provide one shared sign-in entry for all roles. After successful authentication, users with one allowed dashboard land there directly; users with multiple allowed dashboards MUST choose among only those dashboards.
- **FR-003**: System MUST enforce Role-Based Access Control so users only access pages and actions granted by their roles and permissions.
- **FR-004**: System MUST support sign-in, remember-me, email verification, and password reset for applicable user types.
- **FR-004a**: Student accounts MUST be creatable both by public self-registration and by authorized admins. Both account origins MUST be able to sign in and submit course access requests under the same enrollment rules.
- **FR-005**: System MUST prevent direct unauthorized access to private learning media (videos/files); only entitled users can play/download through controlled access paths.
- **FR-006**: System MUST provide in-app notifications for all dashboards, with email notifications where configured, and support Telegram notifications in phase 1 integrations; WhatsApp is out of scope for initial release.
- **FR-007**: System MUST allow authorized users to export reports as PDF, Excel, and CSV for dashboards that include Reports.
- **FR-008**: Heavy work (emails, notifications, report generation, exports, backups, future video processing, Telegram sync) MUST be processable asynchronously so interactive pages remain responsive.
- **FR-009**: System MUST run scheduled operational jobs including temporary-file cleanup, reminders, statistics refresh, backups, expired-subscription checks, and student alerts.
- **FR-010**: Public API consumers MUST be authenticated and authorized consistently with platform permissions for exposed operations.
- **FR-010a**: Course access in v1 MUST follow request-and-approve: a student submits a course access request; an authorized reviewer with the approve-enrollment permission reviews it; only after approval does the system create an active enrollment/entitlement. Pending or rejected requests MUST NOT grant lesson, file, or quiz access.
- **FR-010b**: Users who hold the configurable “approve enrollment” permission MUST be able to list pending course access requests and approve or reject them from an allowed staff surface, with the student notified of the decision (at least in-app). Super Admin MUST be able to assign or revoke that permission on roles.
- **FR-010c**: Approve-enrollment authority MUST NOT be hard-wired to only one named role; it is granted through RBAC so Support, Finance, Team, or other staff roles may receive it when configured.

#### Student Dashboard

- **FR-011**: Student Dashboard Home MUST show student name, avatar, current term, enrolled courses summary, last watched lesson, overall/course progress, upcoming quizzes, announcements, latest notifications, and quick actions.
- **FR-012**: My Courses MUST list all courses the student is authorized to access via approved enrollment, with search/filter, course cards (name, instructor, image, lesson counts, completed count, progress, status, continue/enter).
- **FR-013**: Course Details MUST show description, schedule, videos, files, quizzes, hours, and progress for an enrolled course.
- **FR-014**: Lesson page MUST provide video playback, description, PDF, attachments, notes, comments, next/previous navigation, and mark-as-completed.
- **FR-015**: Quiz flow MUST show quiz name, duration, question count, start action; after completion MUST show score, student answers, correct answers, and performance analysis.
- **FR-016**: My Progress MUST show per-course progress, hours, videos completed, quizzes, and ranking where ranking is enabled.
- **FR-017**: Student Calendar MUST show reviews, lectures, and exam dates relevant to the student.
- **FR-018**: Student Profile MUST allow viewing/updating avatar, name, phone, university, and password change under validation rules.
- **FR-019**: Student Settings MUST support notification preferences and dark mode. The entire product UI and default content language MUST be Arabic (RTL); no multi-language product UI in v1.
- **FR-020**: Student Support MUST allow opening a ticket, chat entry, and FAQ browsing.
- **FR-021**: Student Notifications page MUST list platform notifications for that student with read/unread states.

#### Instructor Dashboard

- **FR-022**: Instructor Dashboard MUST include pages: Dashboard, Students, Courses, Lessons, Videos, Assignments, Quizzes, Live Sessions, Reports, Analytics, Messages, Announcements, Calendar, Settings.
- **FR-023**: Instructors MUST manage educational content and cohorts only within their authorized courses.
- **FR-024**: Instructors MUST be able to publish announcements and message relevant students.
- **FR-025**: Instructors MUST access teaching reports/analytics for their courses (completion, quiz performance, engagement summaries).

#### Marketing Dashboard

- **FR-026**: Marketing Dashboard MUST be an independent surface with Campaigns, Coupons, Referral, Student Ambassadors, Leads, Conversion, and Analytics.
- **FR-027**: Marketing users MUST create and manage campaigns/coupons and measure attributed usage and conversion outcomes.

#### Finance Dashboard

- **FR-028**: Finance Dashboard MUST be fully independent with Overview, Revenue, Expenses, Transactions, Subscriptions, Refunds, Payroll, Reports, Forecast, and Profit.
- **FR-029**: Finance users MUST inspect monetary movements and subscription states and produce finance reports/exports for authorized periods.
- **FR-030**: Refund actions MUST be permission-gated and leave an auditable trail.

#### Team Dashboard

- **FR-031**: Team Dashboard MUST let each member view tasks, announcements, files, meetings, goals, reports, and attendance according to permissions.
- **FR-032**: Task updates and attendance visibility MUST respect membership and manager/member permission differences.

#### Support Dashboard

- **FR-033**: Support Dashboard MUST include Tickets, Live Chat, Students (context lookup), FAQ, and Reports.
- **FR-034**: Support agents MUST manage ticket lifecycle and publish/maintain FAQ content used by students.

#### Super Admin Dashboard

- **FR-035**: Super Admin Dashboard MUST provide control over Users, Roles, Permissions, Logs, Settings, Storage, Queues, cache/session operational views, Jobs, Notifications, Emails, Audit Logs, Backups, Security, and Monitoring—including creating student accounts and configuring which roles hold approve-enrollment (and performing approvals when that permission is granted to Super Admin).
- **FR-036**: All privileged Super Admin changes affecting access or security MUST be captured in audit logs.

#### Cross-cutting Page Specification Standard

- **FR-037**: For every dashboard page delivered, the product definition MUST include: goal, who can access, priority, components, states, business rules, permissions, and related data entities (as exemplified by Student → My Courses).
- **FR-038**: Every dashboard MUST include a page-priority matrix (Page | Goal | Who can access | Priority) before detailed page specs.

### Key Entities *(include if feature involves data)*

- **User**: Account identity (name, avatar, phone, university as applicable, auth credentials/verification state, creation source: self-registered or admin-created).
- **Role / Permission**: Authorization units assigned to users; gate dashboard and page actions, including the configurable approve-enrollment permission for course access requests.
- **Course Access Request**: Student-initiated request for a specific course; states include pending, approved, rejected; approval creates enrollment.
- **Enrollment**: Link between student and course created upon admin approval of a course access request (or equivalent authorized grant); access state active/revoked.
- **Course**: Teachable unit (name, instructor, image, description, schedule, hours, status).
- **Lesson**: Course learning unit (video, description, PDF/attachments, completion state).
- **Progress**: Per-student per-course/lesson completion and aggregates (hours, videos, quizzes).
- **Quiz / Attempt**: Assessment definition and student attempt results (score, answers, timing).
- **Announcement / Notification**: Broadcast or personal messages across channels (in-app/email/Telegram).
- **Calendar Event**: Lectures, reviews, exams, meetings relevant to a user/role.
- **Ticket / Chat Thread**: Support conversations and resolution state.
- **Campaign / Coupon / Lead / Referral / Ambassador**: Marketing acquisition and attribution entities.
- **Transaction / Subscription / Refund / Expense / Payroll Record**: Finance entities for money movement and obligations.
- **Team Task / Meeting / Goal / Attendance / File Asset**: Internal team operations entities.
- **Audit Log / Activity Log**: Security and operational trail of sensitive actions.
- **Report Job**: Requested export/report with status and download entitlement.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Enrolled students can reach last-watched lesson playback in ≤ 3 clicks from Student Dashboard Home after login.
- **SC-002**: ≥ 95% of student lesson page loads (authorized) present video player and core lesson metadata without dead-end errors in acceptance testing.
- **SC-003**: Students can complete a standard quiz attempt end-to-end (start → submit → see score/analysis) in one uninterrupted session.
- **SC-004**: 100% of cross-role access attempts to unauthorized dashboards are denied in security acceptance tests.
- **SC-005**: Finance users can produce at least one period revenue/transactions report export successfully on first attempt in UAT.
- **SC-006**: Support agents can move a new ticket from open → replied → closed in ≤ 5 minutes in moderated UAT scripts.
- **SC-007**: Marketing users can create a coupon and verify it appears as usable in the acquisition flow during UAT.
- **SC-008**: Team members can view and update an assigned task status without accessing Finance or Super Admin surfaces.
- **SC-009**: Super Admins can assign a permission and observe the permission change enforce within the same UAT session after re-auth/refresh rules defined in plan.
- **SC-010**: Asynchronous exports notify completion without requiring the user to keep the report page open; ≥ 90% of sample export jobs complete successfully in UAT.
- **SC-011**: First-time students in usability testing can locate My Courses, a lesson, and Support without assistance in ≥ 80% of sessions.
- **SC-012**: Empty and blocked-access states (no courses, revoked enrollment, no permission) show clear user-facing messages in 100% of tested cases.
- **SC-013**: In UAT, a student request → approve-enrollment permission holder approves path results in the course appearing under My Courses and private lesson access working on the next student session/refresh without unauthorized enrollment side-doors.
- **SC-014**: In UAT, both a self-registered student and an admin-created student can each complete sign-in and submit at least one course access request successfully.
- **SC-015**: In v1 UAT, each listed surface (Public Website and every dashboard plus a smoke-tested authorized API call) is reachable by its intended actor role without being marked “coming later.”
- **SC-016**: Multi-role test accounts see only allowed dashboards in the post-login picker; single-role accounts skip the picker and land on their dashboard in UAT.
- **SC-017**: A staff role without approve-enrollment cannot approve requests; the same role can approve after Super Admin grants that permission (verified in UAT).

## Assumptions

- The website and all dashboards are fully Arabic (RTL). There is no English UI or language switcher in v1; copy, labels, notifications, and emails are Arabic unless a third-party system forces otherwise. University field implies students may belong to different universities on one platform (single deployment, not multi-tenant white-label in v1).
- v1 course access is request-and-approve (student requests → permissioned staff approves → enrollment). Online payment gateway checkout is not required to grant course access in v1; payment-provider integrations remain Phase 2 and may later automate or supplement approval.
- Student onboarding supports dual account creation: public self-registration and admin-created accounts; email verification applies where self-registration is used; admin-created accounts may use invite/activation or temporary credentials as defined in planning.
- v1 ships all platform surfaces. Within each dashboard, High-priority pages from the page matrices are mandatory for release; Medium/Low pages may be thinner but must not leave the surface absent.
- Authentication uses one shared login for all roles; post-login routing uses direct landing or a multi-role dashboard picker. There are no separate per-dashboard login URLs required in v1.
- Course access request approval is permission-based (approve enrollment), assignable to any staff role via Super Admin RBAC—not limited to Super Admin alone by product rule.
- Ranking on My Progress is optional/configurable; if disabled, the page omits ranking without failing.
- Live Sessions for instructors are scheduling/announcement/join-link oriented in v1 (not a full built-in WebRTC conference product).
- Team Dashboard members are internal staff accounts distinct from students/instructors unless a user is explicitly multi-role.
- Public Website depth in this feature covers entry, discovery, and auth routing; full marketing page-by-page creative content can be specified in a follow-up if needed.
- Private media access in v1 uses platform-controlled delivery on local storage; S3-compatible storage and CDN/adaptive streaming are later phases.
- Two-factor authentication is future-scoped; not required for v1 success criteria.
- WhatsApp notifications and mobile-app push (FCM) are future-scoped.
- Stakeholder-preferred delivery direction for planning (not binding on this specification’s user-facing requirements): single modular monolith application, server-rendered UI with progressive enhancement (no React/Vue/Livewire/Inertia), relational data store, Redis for cache/queue/session/rate-limit/OTP/statistics, Sanctum/session auth patterns, RBAC via roles/permissions/policies, and phased integrations (Telegram/SMTP → payment providers → analytics/push). Detailed stack choices belong in `/speckit-plan`.
- API surface initially serves first-party needs and future mobile; public third-party partner API productization is out of scope unless later specified.

## Platform Surfaces & Page Matrices

### Surface Map

| Surface | Primary actors | Purpose |
|---------|----------------|---------|
| Public Website | Visitors, authenticating users | Discovery and entry |
| Student Dashboard | Students | Learning, progress, support |
| Instructor Dashboard | Instructors | Teach and manage course operations |
| Team Dashboard | Internal team members | Tasks, collaboration, attendance |
| Finance Dashboard | Finance staff | Revenue, expenses, payroll, profit |
| Marketing Dashboard | Marketing staff | Acquisition and conversion |
| Support Dashboard | Support agents | Tickets, chat, FAQ |
| Super Admin Dashboard | Super admins | Full platform control |
| API | Authorized clients | Programmatic platform operations |

### Student Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Dashboard Home | Daily learning overview & resume | Student | High |
| My Courses | List entitled courses | Student | High |
| Course Details | Course content map | Student | High |
| Lesson | Consume lesson media & mark complete | Student | High |
| Quiz | Take assessments & review results | Student | High |
| My Progress | Track learning metrics | Student | High |
| Achievements | View earned achievements | Student | Medium |
| Notifications | Read all notifications | Student | High |
| Calendar | See lectures/reviews/exams | Student | Medium |
| Profile | Manage personal identity data | Student | Medium |
| Settings | Notifications, theme | Student | Medium |
| Support | Tickets, chat, FAQ | Student | Medium |

### Instructor Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Dashboard | Teaching operations overview | Instructor | High |
| Students | View/manage course students | Instructor | High |
| Courses | Manage owned/assigned courses | Instructor | High |
| Lessons | Structure course lessons | Instructor | High |
| Videos | Manage lesson videos | Instructor | High |
| Assignments | Create/review assignments | Instructor | High |
| Quizzes | Create/manage assessments | Instructor | High |
| Live Sessions | Schedule/manage live sessions | Instructor | Medium |
| Reports | Teaching reports/exports | Instructor | Medium |
| Analytics | Engagement & performance insights | Instructor | Medium |
| Messages | Student messaging | Instructor | Medium |
| Announcements | Course/platform announcements | Instructor | High |
| Calendar | Teaching schedule | Instructor | Medium |
| Settings | Instructor preferences | Instructor | Low |

### Marketing Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Campaigns | Plan/run campaigns | Marketing | High |
| Coupons | Create/manage discount codes | Marketing | High |
| Referral | Track referral program | Marketing | Medium |
| Student Ambassadors | Manage ambassador program | Marketing | Medium |
| Leads | Capture/manage leads | Marketing | High |
| Conversion | Monitor conversion funnel | Marketing | High |
| Analytics | Marketing performance | Marketing | Medium |

### Finance Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Overview | Financial health snapshot | Finance | High |
| Revenue | Inspect income streams | Finance | High |
| Expenses | Track expenses | Finance | High |
| Transactions | Audit money movements | Finance | High |
| Subscriptions | Monitor subscription states | Finance | High |
| Refunds | Process/review refunds | Finance | High |
| Payroll | Manage payroll records | Finance | Medium |
| Reports | Finance exports | Finance | High |
| Forecast | Forward-looking estimates | Finance | Medium |
| Profit | Profit views by period | Finance | High |

### Team Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Tasks | Personal/team work items | Team member (scoped) | High |
| Announcements | Internal announcements | Team member | High |
| Files | Shared operational files | Team member (scoped) | Medium |
| Meetings | Meeting schedule/details | Team member | Medium |
| Goals | Goals & progress | Team member (scoped) | Medium |
| Reports | Team operational reports | Team member with report permission | Medium |
| Attendance | Attendance records | Self; managers as permitted | Medium |

### Support Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Tickets | Manage support tickets | Support | High |
| Live Chat | Real-time student assistance | Support | High |
| Students | Lookup student context | Support | High |
| FAQ | Maintain help content | Support | Medium |
| Reports | Support KPIs/exports | Support | Medium |

### Super Admin Dashboard — Page Matrix

| Page | Goal | Who can access? | Priority |
|------|------|-----------------|----------|
| Users | Manage all users | Super Admin | High |
| Roles | Define roles | Super Admin | High |
| Permissions | Bind permissions | Super Admin | High |
| Logs | Operational logs | Super Admin | High |
| Settings | Global settings | Super Admin | High |
| Storage | Storage health/usage | Super Admin | Medium |
| Queues | Queue health | Super Admin | Medium |
| Jobs | Background job inspection | Super Admin | Medium |
| Notifications | Notification system control | Super Admin | Medium |
| Emails | Email delivery visibility | Super Admin | Medium |
| Audit Logs | Security/audit trail | Super Admin | High |
| Backups | Backup operations | Super Admin | High |
| Security | Security controls/overview | Super Admin | High |
| Monitoring | System health monitoring | Super Admin | High |
> **Note:** The table above is the high-level Super Admin surface (Users, Roles, Permissions, ops tooling). The implemented admin console expands beyond this matrix with full sidebar navigation (catalog, students, finance, marketing, support, settings, and more). See `specs/001-learning-platform-core/pages/admin/` (per-page specs) and `specs/001-learning-platform-core/admin-page-template.md` for the expanded nav and page inventory.

## Detailed Page Specs (Student reference depth)

### Page: Dashboard Home

**Goal**: Give the student everything needed to resume learning in the fewest clicks.

**Components**: Profile header (name/avatar), current term, enrolled courses summary, last lesson resume, progress indicator, upcoming quizzes, announcements list, latest notifications, quick actions.

**States**: New student (minimal data), active learner, all-caught-up (no upcoming quizzes), notifications empty.

**Business Rules**: Only entitled course data appears; progress reflects completed lessons/quizzes per enrollment.

**Permissions**: Student only.

**Data**: Users, Enrollments, Courses, Lessons, Progress, Quizzes, Announcements, Notifications.

### Page: My Courses

**Goal**: Display all entitled courses for the student.

**Components**: Search, Filter, Course Cards, Progress, Status, Continue/Enter button.

**States**: No courses; one course; multiple courses; course ended/completed.

**Business Rules**: Show only courses with an active approved enrollment for that student. Pending requests do not appear as accessible courses (they may appear separately as request status if the UI provides it).

**Permissions**: Student only.

**Data**: Courses, Enrollments, Course Access Requests, Lessons, Progress.

### Page: Course Details

**Goal**: Orient the student inside a single course.

**Components**: Description, schedule, videos list, files, quizzes, hours, progress summary, enter-lesson actions.

**States**: Not started; in progress; completed; access revoked.

**Business Rules**: Requires active entitlement; revoked access shows blocked state without private media URLs.

**Permissions**: Student (entitled).

**Data**: Courses, Lessons, Files, Quizzes, Progress, Enrollments.

### Page: Lesson

**Goal**: Deliver lesson learning materials and completion.

**Components**: Video player, description, PDF, attachments, notes, comments, next lesson, previous lesson, mark as completed.

**States**: Not started; in progress; completed; media unavailable; comments empty.

**Business Rules**: Completion requires authenticated entitled student; private media must not be directly publicly addressable.

**Permissions**: Student (entitled).

**Data**: Lessons, Progress, Notes, Comments, Files.

### Page: Quiz

**Goal**: Start, complete, and review assessments.

**Components**: Quiz name, time limit, question count, start button; post-attempt score, answers, correct answers, performance analysis.

**States**: Not attempted; in progress; submitted; timed out; results available; unavailable.

**Business Rules**: Enforce time limits and attempt rules configured on the quiz; results visibility follows quiz settings.

**Permissions**: Student (entitled).

**Data**: Quizzes, Questions, Attempts, Answers, Progress.

### Page: My Progress

**Goal**: Summarize learning performance across courses.

**Components**: Per-course progress, hours, videos count, quizzes count, ranking (if enabled).

**States**: No progress yet; partial; complete; ranking hidden.

**Business Rules**: Metrics derive from lesson/quiz completion records.

**Permissions**: Student only.

**Data**: Progress, Courses, Lessons, Quiz Attempts.

### Page: Achievements

**Goal**: Show earned recognition for milestones.

**Components**: Achievement list/cards, earned dates, empty state.

**States**: None earned; some earned.

**Business Rules**: Awards trigger from defined milestones (course completion, streaks, quiz thresholds).

**Permissions**: Student only.

**Data**: Achievements, User Achievements.

### Page: Notifications

**Goal**: Central inbox for platform notifications.

**Components**: Notification list, read/unread, filters, mark read.

**States**: Empty; unread present; all read.

**Business Rules**: Shows only recipient’s notifications.

**Permissions**: Student only.

**Data**: Notifications.

### Page: Calendar

**Goal**: Show learning schedule commitments.

**Components**: Calendar view, event details (reviews, lectures, exams).

**States**: No events; upcoming events; conflicting same-day events.

**Business Rules**: Events limited to student’s courses/platform events targeted to them.

**Permissions**: Student only.

**Data**: Calendar Events, Courses, Quizzes.

### Page: Profile

**Goal**: Manage personal profile and password.

**Components**: Avatar, name, phone, university, password change.

**States**: View; edit; validation errors; save success.

**Business Rules**: Password changes require current password + strength rules; phone/university validated.

**Permissions**: Student (own profile).

**Data**: Users.

### Page: Settings

**Goal**: Personal preferences.

**Components**: Notification preferences, dark mode.

**States**: Defaults; customized; save success.

**Business Rules**: Preference changes apply to the current user only. Product language is fixed Arabic (RTL) for all users; settings do not include a language switcher.

**Permissions**: Student (own settings).

**Data**: User Settings.

### Page: Support

**Goal**: Get help via ticket, chat, or FAQ.

**Components**: Open ticket form, chat entry, FAQ search/list.

**States**: No tickets; open tickets; chat unavailable; FAQ empty.

**Business Rules**: Tickets created here appear in Support Dashboard; students see only their tickets.

**Permissions**: Student (own tickets); FAQ public-to-authenticated students.

**Data**: Tickets, Chat Threads, FAQ Articles.

## Instructor & Other Dashboards — Page Spec Expectations

Each page listed in the Instructor, Marketing, Finance, Team, Support, and Super Admin matrices MUST be elaborated during planning/tasks using the same template as Student pages:

1. Goal  
2. Components  
3. States  
4. Business Rules  
5. Permissions  
6. Data entities  

Minimum elaboration before implementation of a page: goal, permissions, primary states (including empty), and critical business rules.

### Instructor pages — concise goals

| Page | Goal summary |
|------|----------------|
| Dashboard | Overview of teaching workload and alerts |
| Students | Roster and student learning signals for owned courses |
| Courses | CRUD/manage authorized courses |
| Lessons | Structure and order lessons |
| Videos | Attach/manage private lesson videos |
| Assignments | Create and review assignments |
| Quizzes | Author quizzes and review attempts |
| Live Sessions | Schedule and manage live session metadata |
| Reports | Export teaching reports |
| Analytics | Visualize engagement/performance |
| Messages | Message students |
| Announcements | Publish course announcements |
| Calendar | Instructor schedule |
| Settings | Instructor preferences |

### Marketing / Finance / Team / Support / Super Admin

Page goals are defined by the matrices above; detailed component-level specs follow the Student reference pattern during `/speckit-plan` and `/speckit-tasks` decomposition, prioritized by each matrix Priority column (High first).
