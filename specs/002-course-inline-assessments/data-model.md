# Data Model: Course Inline Assessments

**Feature**: `002-course-inline-assessments` | **Date**: 2026-08-03

Extends existing platform entities. No new tables required for the core flow.

## Entity overview

```text
Course
  ├── Quiz (hasMany)
  │     └── Question (hasMany, ordered by position)
  │           └── QuestionOption (hasMany)
  │     └── QuizAttempt → AttemptAnswer (references Question)
  └── Assignment (hasMany)
        └── AssignmentSubmission (hasMany, unique per user)
```

## Quiz

| Field | Notes |
|-------|--------|
| course_id | Required FK |
| title | Required |
| duration_minutes | Optional/positive |
| status | `draft` or `published` |
| show_correct_answers | Boolean |

**Transitions**: draft and published. Publish requires at least one question.

## Question

| Field | Notes |
|-------|--------|
| quiz_id | Required FK (cascade with quiz) |
| type | `single`, `multiple`, or `text` (map legacy `short_text` to `text`) |
| body | Required text |
| position | Unsigned int; ordered within quiz |
| points | Unsigned int at least 1 (default 1) |

**Validation**:
- Choice types: at least 2 options; `single` exactly one correct; `multiple` at least one correct
- `text`: zero options stored

**Delete rule**: Refuse if any `attempt_answers` row references the question.

## QuestionOption

| Field | Notes |
|-------|--------|
| question_id | FK cascade |
| body | Required |
| is_correct | Boolean |

## Assignment

| Field | Notes |
|-------|--------|
| course_id | Required |
| lesson_id | Optional; must belong to same course when set |
| title | Required |
| description | Optional |
| due_at | Required (existing admin validation) |
| status | `draft`, `published`, or `archived` |
| deleted_at | Soft delete |

**Delete rule**: If graded submissions exist, prefer archive over delete (block destructive delete with message).

## AssignmentSubmission

| Field | Notes |
|-------|--------|
| assignment_id + user_id | Unique pair |
| body / file_path | Student content |
| status | `submitted`, `graded`, or `resubmit_requested` |
| score | Nullable decimal; set on grade; cleared on resubmit request |
| submitted_at | Timestamp |

**Transitions**:
- `submitted` to `graded` when admin grades
- `graded` to `resubmit_requested` when admin requests resubmit
- `resubmit_requested` to `submitted` when student resubmits (student flow may already exist or remain future)

## Relationships used by course tabs

- Course Quizzes tab: `course.quizzes.questions.options`
- Course Assignments tab: `course.assignments.lesson`, `course.assignments.submissions.user`
