# API & Web Contracts: Learning Platform Core

**Branch**: `001-learning-platform-core` | **Date**: 2026-07-31  
**Base**: Arabic UI via web routes; JSON under `/api/v1` for first-party clients.

Auth: Web session (cookie) for Blade dashboards; Bearer Sanctum token for API. All protected routes require authenticated user + permission/policy checks.

Error shape (API):
```json
{ "message": "…", "errors": { "field": ["…"] } }
```

---

## 1. Auth & session

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/login` | guest | Shared login form (RTL) |
| POST | `/login` | guest | Authenticate; redirect per roles |
| POST | `/logout` | user | End session |
| GET | `/register` | guest | Student or instructor self-registration |
| POST | `/register` | guest | Create self-registered student or instructor (`account_type`) |
| GET | `/email/verify/*` | user | Email verification |
| GET/POST | `/password/*` | guest | Password reset |
| GET | `/dashboard-picker` | user | Multi-role chooser |
| POST | `/dashboard-picker` | user | Persist selected dashboard context |
| POST | `/api/v1/login` | guest | Issue token (email/password) |
| POST | `/api/v1/logout` | token | Revoke current token |
| GET | `/api/v1/me` | token/session | Current user, roles, permissions |

**Post-login behavior**: 1 allowed dashboard → redirect to that home; many → picker; none → safe “contact support” page.

---

## 2. Course access requests

| Method | Path | Permission | Description |
|--------|------|------------|-------------|
| GET | `/courses` (public/auth) | optional auth | Published catalog |
| POST | `/student/course-requests` | student | Create request `{ course_id, message? }` |
| GET | `/student/course-requests` | student | List own requests |
| GET | `/staff/course-requests` | `enrollments.approve` | Pending queue |
| POST | `/staff/course-requests/{id}/approve` | `enrollments.approve` | Approve → enrollment |
| POST | `/staff/course-requests/{id}/reject` | `enrollments.approve` | Reject |

API mirrors under `/api/v1/student/course-requests` and `/api/v1/staff/course-requests`.

**Contract rules**:
- Duplicate pending for same course → 422
- Approve when not pending → 409
- Without permission → 403

---

## 3. Student learning

| Method | Path | Rule | Description |
|--------|------|------|-------------|
| GET | `/student` | enrollment role | Dashboard home aggregates |
| GET | `/student/courses` | student | Entitled courses only |
| GET | `/student/courses/{course}` | active enrollment | Course details |
| GET | `/student/lessons/{lesson}` | active enrollment | Lesson + signed/stream media endpoints |
| POST | `/student/lessons/{lesson}/complete` | active enrollment | Mark complete |
| GET/POST | `/student/lessons/{lesson}/notes` | active enrollment | Notes |
| GET/POST | `/student/lessons/{lesson}/comments` | active enrollment | Comments |
| GET | `/student/media/{asset}` | active enrollment | Private stream/download |
| GET | `/student/quizzes/{quiz}` | active enrollment | Quiz meta |
| POST | `/student/quizzes/{quiz}/attempts` | active enrollment | Start attempt |
| POST | `/student/quiz-attempts/{id}/submit` | owner | Submit answers |
| GET | `/student/quiz-attempts/{id}/result` | owner | Score + analysis |
| GET | `/student/progress` | student | Progress aggregates |
| GET | `/student/notifications` | student | Inbox |
| GET | `/student/calendar` | student | Events |
| GET/PUT | `/student/profile` | student | Profile |
| GET/PUT | `/student/settings` | student | Dark mode + notification prefs |
| POST | `/student/tickets` | student | Open ticket |

---

## 4. Instructor

Prefix `/instructor/*` — policies: instructor owns/assigned course.

Key resources: courses, lessons, videos/media, assignments, quizzes, live sessions, students, announcements, messages, calendar, reports, analytics, settings.

Mutations validating ownership return 403 when course not authorized.

---

## 5. Support / Team / Finance / Marketing / Admin

| Surface | Prefix | Notable contracts |
|---------|--------|-------------------|
| Support | `/support/*` | tickets CRUD/status, live chat messages, student lookup, FAQ, reports |
| Team | `/team/*` | tasks, announcements, files, meetings, goals, attendance, reports |
| Finance | `/finance/*` | overview metrics, transactions, subscriptions, refunds, expenses, payroll, report jobs |
| Marketing | `/marketing/*` | campaigns, coupons, referrals, ambassadors, leads, conversion, analytics |
| Super Admin | `/admin/*` | users, roles, permissions (incl. assign `enrollments.approve`), logs, settings, storage/queues/jobs views, notifications/emails ops, audit logs, backups, security, monitoring |

---

## 6. Reports

| Method | Path | Description |
|--------|------|-------------|
| POST | `/{surface}/reports` | Enqueue `{ type, format, filters }` → `ReportJob` |
| GET | `/reports/jobs/{id}` | Status |
| GET | `/reports/jobs/{id}/download` | Authorized download when `done` |

---

## 7. Webhooks / integrations (Phase 1)

| Integration | Direction | Notes |
|-------------|-----------|-------|
| SMTP | outbound | Mail notifications |
| Telegram Bot API | outbound (+ optional inbound commands later) | Queued sends; failures logged; in-app still stored |

Payment webhooks (Fawry/Paymob/…) — **out of contract for v1**.
