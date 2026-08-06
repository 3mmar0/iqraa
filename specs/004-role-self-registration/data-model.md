# Data Model: Role Self-Registration

**Feature**: `004-role-self-registration` | **Date**: 2026-08-07

Extends existing platform entities. No new tables or columns for the core flow.

## Entity overview

```text
Guest registration
  → User (creation_source = self_registered, status = active)
       ├── Role (exactly one of: student | instructor via user_role)
       └── UserSetting (1:1, created on register)
```

## User (existing)

| Field | Registration behavior |
|-------|------------------------|
| name | Required |
| email | Required, unique |
| phone | Optional; unique when present (empty → null) |
| university | Optional for both account types |
| password | Required, confirmed, platform Password defaults |
| creation_source | Always `self_registered` on this path |
| status | `active` immediately (students and instructors) |

**Not set by public register**: avatar, gender, academic_year_id, semester_id, group_id, admin_notes (admin flows only).

## Account type (request-only)

Not persisted as its own column. Maps at write time:

| `account_type` | Role slug attached | Dashboard key |
|----------------|--------------------|---------------|
| `student` | `student` | `student` |
| `instructor` | `instructor` | `instructor` |

**Validation**:
- Required
- `in:student,instructor` only
- Reject any other value (including staff role slugs)

## Role (existing)

Seeded roles used by this feature:

| slug | name_ar | dashboard_key |
|------|---------|---------------|
| student | طالب | student |
| instructor | محاضر | instructor |

Public registration MUST NOT attach: `team`, `finance`, `marketing`, `support`, `super_admin`, or custom staff roles.

## UserSetting (existing)

Created with `user_id` on successful registration (same as current student flow).

## State transitions

```text
[Guest form]
    → validate
    → create User (active, self_registered)
    → attach student OR instructor role
    → create UserSetting
    → login
    → dashboard.redirect
         → single key → student.home OR instructor.home
         → (multi-role not created by this form)
```

No pending/approval state for instructors in this feature.

## Relationships

- `User` belongsToMany `Role` through `user_role`
- `User` hasOne `UserSetting`
- Instructor-owned courses (`courses.instructor_user_id`) are unchanged; self-registered instructors start with zero courses until admin/course assignment
