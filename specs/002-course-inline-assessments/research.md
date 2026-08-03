# Research: Course Inline Assessments

**Feature**: `002-course-inline-assessments` | **Date**: 2026-08-03

## 1. In-tab authoring UX vs separate pages

**Decision**: Keep admins on `admin.courses.show` with `?tab=quizzes|assignments`. Replace outbound primary “أسئلة” / “عرض” actions with Alpine panels/modals on the same tab. Retain global `/admin/quizzes` and `/admin/assignments` as optional cross-course overview.

**Rationale**: Spec FR-001–FR-009; course tabs already use Alpine create/edit modals and `ReturnsToCourse`. Matching that pattern minimizes UX and code divergence.

**Alternatives considered**:
- Deep-link only to standalone show pages with return links — rejected (still leaves the course page).
- Livewire/Inertia SPA panel — rejected (platform stack forbids Livewire/Inertia/React/Vue).

## 2. Question CRUD placement

**Decision**: Add nested admin routes under the quiz (`admin.quizzes.questions.store|update|destroy|reorder`), handled by a new `App\Http\Controllers\Web\Admin\QuestionController`. Persist via Eloquent (helpers on `QuizAdminService` as needed). Wire course-tab forms with `_return_fields` so redirects land on `?tab=quizzes`.

**Rationale**: No admin Question routes exist today; quiz show only lists questions read-only. Nested resource matches domain ownership.

**Alternatives considered**:
- Stuff question actions into `QuizController` — rejected (controller already large).
- JSON API + fetch-only UI — deferred; classic form posts match existing course-tab mutations.

## 3. Question types and option validation

**Decision**: Support types already in the data model: `single`, `multiple`, `text`.
- `single` / `multiple`: at least 2 options; `single` exactly one `is_correct`; `multiple` at least one `is_correct`.
- `text`: no options (clear options on type change to text).
- Points: positive integer (default 1). Position: ordered integers; reorder updates in a transaction.

**Rationale**: Spec FR-003–FR-005 and core data model already define these types. Normalize any legacy `short_text` to `text` in admin authoring.

**Alternatives considered**:
- Additional question types (file, matching) — out of scope.
- Keep `short_text` as a fourth admin type — rejected; consolidate on `text`.

## 4. Publish requires at least one question

**Decision**: `QuizAdminService::publish` refuses when question count is zero, with Arabic flash (“لا يمكن نشر اختبار بدون أسئلة”). Store-as-published with zero questions is rejected or forced to draft the same way.

**Rationale**: Spec SC-004; current `publish()` only flips status.

**Alternatives considered**:
- Allow empty published quizzes — rejected by spec.
- Soft-warn only — rejected; must hard-block.

## 5. Deleting questions with attempt answers

**Decision**: Block delete when `attempt_answers` reference the question; show Arabic flash. When no attempts reference it, delete question and cascade options. Application-level block prevents silent history loss despite DB `cascadeOnDelete` on answers.

**Rationale**: Spec edge case — must not corrupt attempt history silently.

**Alternatives considered**:
- Soft-delete questions — heavier schema change; deferred.
- Allow cascade delete of answers — rejected for attempt auditability.

## 6. Assignment detail and submissions on course tab

**Decision**: Replace “عرض” with an in-tab detail/submissions panel. Eager-load submissions on course show for the assignments tab. Add:
- `POST` grade submission → `score`, `status = graded`
- `POST` request resubmit → `status = resubmit_requested`, clear score for re-grade

Both use `ReturnsToCourse` → `?tab=assignments`. Reuse the same actions on standalone assignment show later if desired.

**Rationale**: Spec US3 / FR-007–FR-008. Assignment show lists submissions but has no grade/resubmit endpoints yet.

**Alternatives considered**:
- Embed/iframe of assignment show — brittle.
- Ship submissions list without grading in first slice — acceptable as task ordering, but plan includes grade/resubmit.

## 7. Assignment delete with graded submissions

**Decision**: If any submission is `graded`, block hard delete from the course tab and suggest/perform archive (`status = archived`) with Arabic messaging, matching core assignments page business rules. SoftDeletes remain available for allowed deletes.

**Rationale**: Spec edge case + `001` assignments page rule.

**Alternatives considered**:
- Always force-delete — rejected.
- Silent soft-delete without messaging — weaker UX.

## 8. Course show data loading

**Decision**: For `tab=quizzes`, eager-load `quizzes.questions.options` (or load questions when opening the manager if payload is large). For `tab=assignments`, eager-load `assignments.lesson` and `assignments.submissions.user`. Cap listed submissions (e.g. latest 50) if needed.

**Rationale**: Avoid N+1 while not loading heavy relations on unrelated tabs.

**Alternatives considered**:
- Always load all relations on every course show — wasteful.
- XHR-only question/submission panels — optional later optimization.

## 9. Testing strategy

**Decision**: Pest/PHPUnit feature tests as admin: question CRUD with course return context; option validation failures; publish blocked with zero questions; grade submission redirects to course assignments tab.

**Rationale**: Aligns with platform testing stack and measurable success criteria.

**Alternatives considered**:
- Browser Dusk-only gate — slower; keep as optional quickstart smoke.
