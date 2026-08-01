# Data Model: Learning Platform Core

**Branch**: `001-learning-platform-core` | **Date**: 2026-07-31  
**Spec**: [spec.md](./spec.md)

Conventions: MySQL 8; Eloquent models; soft deletes where recoverable; timestamps on all tables; FKs + indexes on high-cardinality filters (`user_id`, `course_id`, `status`).

---

## Entity Relationship (logical)

```text
User ──┬── UserRole ── Role ── RolePermission ── Permission
       ├── UserProfile / UserSetting
       ├── CourseAccessRequest ── Course
       ├── Enrollment ── Course ── Lesson ── MediaAsset
       │                      └── Quiz ── Question ── QuizAttempt ── AttemptAnswer
       ├── LessonProgress / LessonNote / LessonComment
       ├── Notification
       ├── Ticket ── TicketMessage
       ├── TeamTask / Attendance / Meeting (team)
       ├── Campaign / Coupon / Lead / Referral (marketing)
       └── Transaction / Subscription / Refund / Expense / PayrollRecord (finance)

AuditLog / ActivityLog / ReportJob (cross-cutting)
```

---

## Core identity & RBAC

### User
| Field | Rules |
|-------|--------|
| id | PK |
| name | required |
| email | required, unique |
| phone | nullable, unique when present |
| password | hashed |
| avatar_path | nullable |
| university | nullable (students) |
| email_verified_at | nullable |
| creation_source | `self_registered` \| `admin_created` |
| status | `active` \| `invited` \| `disabled` |
| remember_token | nullable |

### Role
| Field | Rules |
|-------|--------|
| slug | unique (`student`, `instructor`, `team`, `finance`, `marketing`, `support`, `super_admin`, custom) |
| name_ar | required |
| dashboard_key | maps to surface (`student`, `instructor`, …) nullable for non-dashboard roles |

### Permission
| Field | Rules |
|-------|--------|
| slug | unique (e.g. `enrollments.approve`, `courses.manage`, `finance.refund`) |
| name_ar | required |

### RolePermission / UserRole
Many-to-many pivots. A user may hold multiple roles.

**Validation**: At least one role for operational staff; student self-register assigns `student`.

---

## Catalog & enrollment

### Course
| Field | Rules |
|-------|--------|
| title, description | Arabic content |
| instructor_user_id | FK User (instructor) |
| image_path | nullable |
| hours | decimal ≥ 0 |
| status | `draft` \| `published` \| `archived` |
| schedule_text / term_label | nullable |

### CourseAccessRequest
| Field | Rules |
|-------|--------|
| user_id, course_id | required FKs |
| status | `pending` \| `approved` \| `rejected` |
| message | optional student note |
| reviewed_by | nullable FK User |
| reviewed_at | nullable |
| review_note | nullable |

**Transitions**:
- `pending` → `approved` (creates Enrollment; notify student)
- `pending` → `rejected` (notify student)
- Unique: at most one `pending` row per (user_id, course_id)

### Enrollment
| Field | Rules |
|-------|--------|
| user_id, course_id | unique pair when active |
| status | `active` \| `revoked` \| `completed` |
| source | `access_request` \| `admin_grant` (future: `payment`) |
| access_request_id | nullable FK |
| enrolled_at, revoked_at | |

**Rule**: Lesson/quiz/media access requires `active` enrollment.

---

## Learning content

### Lesson
| Field | Rules |
|-------|--------|
| course_id, title, description | required |
| position | integer order |
| status | `draft` \| `published` |

### MediaAsset
| Field | Rules |
|-------|--------|
| lesson_id | FK |
| type | `video` \| `pdf` \| `attachment` |
| disk | `local_private` (v1) |
| path | private path |
| original_name, mime, size | |

### LessonProgress
| Field | Rules |
|-------|--------|
| user_id, lesson_id | unique |
| status | `not_started` \| `in_progress` \| `completed` |
| completed_at | nullable |
| last_position_seconds | for video resume |

### LessonNote / LessonComment
Owned by user + lesson; comments may be threaded later (v1 flat OK).

---

## Quizzes

### Quiz
course_id, title, duration_minutes, question_count (derived or stored), status, show_correct_answers (bool)

### Question
quiz_id, type (`single` \| `multiple` \| `text`), body, position, points

### QuestionOption
question_id, body, is_correct

### QuizAttempt
user_id, quiz_id, started_at, submitted_at, score, status (`in_progress` \| `submitted` \| `timed_out`)

### AttemptAnswer
attempt_id, question_id, selected payload, is_correct

**Transitions**: start → in_progress → submitted | timed_out (auto-submit answered questions).

---

## Notifications & calendar

### Notification (DB notifications table or custom)
user_id, type, data JSON, read_at

### CalendarEvent
title, starts_at, ends_at, type (`lecture` \| `review` \| `exam` \| `meeting` \| `live_session`), course_id nullable, audience scope

### Announcement
title, body, author_id, course_id nullable, published_at

### UserSetting
user_id, dark_mode, notification preferences JSON (no language key — Arabic fixed)

---

## Support

### Ticket
student_user_id, subject, status (`open` \| `pending` \| `closed`), assignee_id nullable

### TicketMessage
ticket_id, sender_id, body, channel (`ticket` \| `live_chat`)

### FaqArticle
title, body, published bool, position

---

## Team

### TeamTask
assignee_id, title, status, due_at, created_by

### TeamFile / Meeting / Goal / AttendanceRecord
Scoped by team membership permissions (membership table optional: `team_memberships`).

---

## Marketing

### Campaign
name, status, dates, metrics JSON

### Coupon
code unique, discount rules, active flag, usage limits

### Lead
contact fields, stage, source

### Referral / AmbassadorProfile
user links + attribution counters

---

## Finance

### Transaction
amount, currency (EGP default), type, status, user_id nullable, reference, meta JSON

### Subscription
user_id, plan_code, status (`active` \| `expired` \| `canceled`), period dates

### Refund
transaction_id, amount, status, approved_by, note — auditable

### Expense / PayrollRecord
amount, period, payee, status

**Note**: v1 may record manual finance entries even though course access is request/approve (payment gateways Phase 2).

---

## Ops & reports

### AuditLog
actor_id, action, target_type, target_id, ip, properties JSON, created_at

### ReportJob
requester_id, type, format (`pdf` \| `xlsx` \| `csv`), status (`queued` \| `running` \| `done` \| `failed`), file_path, finished_at

### Achievement / UserAchievement
optional ranking/achievements for student progress

### Category
name, slug unique, description, status (`active` \| `archived`), position — SoftDeletes

### AcademicYear / Semester / Group
Academic year periods; semesters belong to a year; groups optionally scoped to year/semester; `group_user` pivot for members

### Order / OrderItem
Admin commerce orders (`pending` \| `approved` \| `rejected` \| `refunded`); items link optional course

### TelegramGroup
title, chat_id, optional course_id, invite_link + expiry, status

### Assignment / AssignmentSubmission
course/lesson scoped assignments with student submissions

### PlatformSetting
key/value bag for Super Admin Settings tabs

### ActivityLog
channel (`activity` \| `authentication` \| `payment` \| `errors` \| `queue` \| `mail`), event, message, context JSON

---

## Validation highlights (from spec)

- Pending/rejected access requests never grant media URLs.
- Progress completion ≤ 100%; concurrent writes last-success-wins.
- Soft-deleted entities hidden from normal lists.
- Approve actions require `enrollments.approve`; grant/revoke of that permission is Super Admin (roles UI).
