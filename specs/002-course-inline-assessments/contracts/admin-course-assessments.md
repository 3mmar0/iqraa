# Contract: Admin Course Assessments (Inline)

**Feature**: `002-course-inline-assessments` | **Date**: 2026-08-03  
**Surface**: Super Admin Arabic RTL Blade UI  
**Base**: Course show `admin.courses.show` with `tab=quizzes` or `tab=assignments`

## Shared mutation contract

All mutating forms initiated from a course tab MUST include:

- `return_to=course`
- `return_course_id={course.id}`
- `return_tab=quizzes` or `return_tab=assignments`

Successful mutations MUST redirect to `admin.courses.show` with that tab and an Arabic `status` flash. Failures MUST redirect back with validation errors (Arabic messages) without sending the admin to a different primary page.

Destructive actions MUST use browser confirm (or equivalent) before submit.

---

## Quizzes tab UI contract

**Route**: `GET /admin/courses/{course}?tab=quizzes`

Must provide:

1. List of course quizzes (title, status, duration)
2. Add / edit / delete / publish / unpublish quiz (existing behavior retained)
3. **Manage questions** control that opens an in-page manager (not a required navigation to `admin.quizzes.show`)
4. Question manager for a selected quiz:
   - List questions (position, body preview, type, points)
   - Add / edit / delete question
   - Reorder questions
   - For `single` / `multiple`: option rows with correct marker(s)
   - For `text`: body + points only

Optional: secondary link to standalone quiz show for attempts/statistics remains allowed but must not be required for authoring.

### Question HTTP actions (logical)

| Action | Method | Path pattern | Notes |
|--------|--------|--------------|-------|
| Store | POST | `/admin/quizzes/{quiz}/questions` | Nested under quiz |
| Update | PUT/PATCH | `/admin/quizzes/{quiz}/questions/{question}` | Question must belong to quiz |
| Destroy | DELETE | `/admin/quizzes/{quiz}/questions/{question}` | Block if attempt answers exist |
| Reorder | POST | `/admin/quizzes/{quiz}/questions/reorder` | Body: ordered question ids |

### Question payload

- `type`: `single` | `multiple` | `text`
- `body`: string, required
- `points`: integer >= 1
- `options`: array of `{ body, is_correct }` when type is choice
- plus return fields above

### Publish gate

`POST .../quizzes/{quiz}/publish` MUST fail with Arabic error when the quiz has zero questions.

---

## Assignments tab UI contract

**Route**: `GET /admin/courses/{course}?tab=assignments`

Must provide:

1. List of course assignments (title, status, due date, lesson label)
2. Add / edit / delete assignment (existing behavior retained)
3. **In-page detail** for an assignment (fields above) without requiring `admin.assignments.show`
4. **Submissions panel** for that assignment: student name, status, score, submitted_at
5. Grade action and request-resubmit action from the panel

Optional: keep standalone assignment index/show for cross-course overview.

### Submission HTTP actions (logical)

| Action | Method | Path pattern | Notes |
|--------|--------|--------------|-------|
| Grade | POST | `/admin/assignments/{assignment}/submissions/{submission}/grade` | Sets score + status `graded` |
| Resubmit | POST | `/admin/assignments/{assignment}/submissions/{submission}/resubmit` | Sets status `resubmit_requested`; clears score |

Grade payload: `score` (0–100 numeric), return fields.

### Delete gate

Deleting an assignment that has graded submissions MUST be blocked or converted to archive with Arabic messaging.

---

## Permissions

Same admin gates as existing `admin.quizzes.*` and `admin.assignments.*`. No new public/student routes in this feature.

## Out of scope (contract non-goals)

- Question bank import/export
- Quiz attempts / leaderboard / statistics authoring on the course tab
- Bulk remind students
- Instructor dashboard parity
