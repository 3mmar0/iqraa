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
