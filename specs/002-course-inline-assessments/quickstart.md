# Quickstart: Course Inline Assessments

**Feature**: `002-course-inline-assessments` | **Date**: 2026-08-03

Validate that admins can author quizzes/questions and manage assignments/submissions without leaving the course detail page.

## Prerequisites

- App running locally with migrated DB and seeded admin user
- At least one course with optional lessons and enrolled student (for submission grading check)
- Admin session (super_admin or equivalent)

## Setup

```bash
composer install
cp .env.example .env   # if needed
php artisan migrate --force
php artisan db:seed
npm install && npm run build
php artisan serve
```

Log in at `/login` as admin, open a course: `/admin/courses/{id}`.

## Scenario A — Quizzes and questions (P1)

1. Open `?tab=quizzes`.
2. Click **إضافة اختبار**, save a draft quiz → remain on quizzes tab; flash success.
3. Open **manage questions** for that quiz (in-page, not `/admin/quizzes/{id}` as the only path).
4. Add a `single` question with 2+ options and one correct answer.
5. Add a `text` question with points.
6. Edit one question; delete the other (if no attempts).
7. Reorder questions; reload tab → order persists.
8. Publish the quiz → succeeds only when ≥1 question exists.
9. Create another empty quiz and try publish → blocked with Arabic message.

**Pass**: All steps stay on `/admin/courses/{id}?tab=quizzes` (or return there after POST).

## Scenario B — Assignments (P1)

1. Open `?tab=assignments`.
2. Create an assignment (title, due date, status, optional lesson).
3. Edit it in the modal; open in-page detail (not required to visit `/admin/assignments/{id}`).
4. Delete a draft assignment with confirmation → list updates; stay on tab.

**Pass**: No required navigation to global assignment pages for CRUD.

## Scenario C — Submissions grade / resubmit (P2)

1. Ensure a student submission exists for a course assignment.
2. From course assignments tab, open submissions panel.
3. Grade the submission (score) → status becomes graded; stay on tab.
4. Request resubmit → status becomes `resubmit_requested`; score cleared.

**Pass**: Grading works from the course URL; see [contracts/admin-course-assessments.md](./contracts/admin-course-assessments.md).

## Automated checks

```bash
php artisan test --filter=CourseQuizQuestions
php artisan test --filter=CourseAssignmentSubmissions
```

Expect feature tests covering return-to-course redirects, question validation, publish gate, and grade/resubmit.

## References

- Spec: [spec.md](./spec.md)
- Data model: [data-model.md](./data-model.md)
- Research: [research.md](./research.md)
- UI/HTTP contract: [contracts/admin-course-assessments.md](./contracts/admin-course-assessments.md)
