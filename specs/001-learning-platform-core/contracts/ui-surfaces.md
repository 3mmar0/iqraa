# UI Surface Contracts: Learning Platform Core

**Date**: 2026-07-31 | **Locale**: Arabic RTL only

Shared layout requirements for every authenticated surface:
- `dir="rtl"` `lang="ar"`
- Shared header: user name/avatar, notifications bell, logout
- Multi-role users: switcher to other allowed dashboards
- Empty, forbidden, and pending states must show Arabic copy (never blank)

---

## Public

| Route name (logical) | Must show |
|----------------------|-----------|
| Home / discovery | Platform intro, CTA to register/login, published course teasers |
| Login | Email, password, remember me, links to register/reset |
| Register | Student fields (name, email, phone, university, password) |
| Catalog course | Description + “طلب الانضمام” when authenticated student lacking enrollment |

---

## Student (reference depth)

| Page | Must include |
|------|----------------|
| Home | Name, avatar, term, courses, last lesson, progress, upcoming quizzes, announcements, notifications, quick actions |
| My Courses | Search, filter, cards (title, instructor, image, lesson counts, progress, status, continue) |
| Course Details | Description, schedule, videos, files, quizzes, hours, progress |
| Lesson | Player, description, PDF, attachments, notes, comments, prev/next, mark complete |
| Quiz | Meta + start; result: score, answers, correct answers, analysis |
| Progress / Achievements / Notifications / Calendar / Profile / Settings / Support | Per spec page matrices |

**Settings**: notification prefs + dark mode only (no language control).

---

## Staff dashboards

Each High-priority page from the spec matrices must render:
1. Arabic page title + purpose-aligned primary list/form
2. Empty state
3. Permission-denied state when role lacks access

Course-request queue UI must appear on **any** staff dashboard whose role has `enrollments.approve` (shared partial/component), not only Super Admin.

---

## Super Admin (`/admin/*`)

Full control-plane nav (Arabic RTL). Page specs live under `specs/001-learning-platform-core/pages/admin/` using the 13-section template.

| Area | Route prefix | Notes |
|------|--------------|-------|
| Home | `admin.home` | KPI cards, charts, quick actions, recent activity, exports |
| Students | `admin.students.*` | Table, filters, bulk, profile tabs, impersonation |
| Courses / Lessons / Categories | `admin.courses.*` / `lessons.*` / `categories.*` | Tabbed course/lesson detail |
| Quizzes / Assignments | `admin.quizzes.*` / `assignments.*` | |
| Orders / Payments / Coupons | `admin.orders.*` / `payments.*` / `coupons.*` | Manual payment ops; gateways Phase 2 |
| Teachers / Academic / Groups | `admin.teachers.*` / `academic-years.*` / `semesters.*` / `groups.*` | |
| Telegram / Announcements | `admin.telegram.*` / `announcements.*` | |
| Reports + overviews | `admin.reports.*`, `marketing`, `support`, `team`, `finance` | Finance/Marketing/Team/Support are overviews + deep-links |
| Settings / System Logs | `admin.settings.*`, `system-logs.*` | Tabbed settings; multi-channel logs |
| Roles / Ops / Security | existing baseline routes | |

Permissions: `super_admin` (Gate bypass) plus granular `admin.*` slugs in `RbacSeeder`.

