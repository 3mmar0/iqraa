# Feature Specification: Course Inline Assessments

**Feature Branch**: `002-course-inline-assessments`

**Created**: 2026-08-03

**Status**: Draft

**Input**: User description: "On the admin course detail Quizzes tab, the admin can manage quizzes and their questions from here without going elsewhere. Same for the Assignments tab — manage assignments from the course page without navigating away."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Manage quiz questions on the course Quizzes tab (Priority: P1)

An admin opens a course, goes to the Quizzes tab, and can create or edit a quiz and fully manage that quiz’s questions (add, edit, delete, reorder, set points and options) without leaving the course page.

**Why this priority**: Today quiz metadata can be edited on the course tab, but questions require navigating to a separate quiz page — and even there questions are view-only. Question authoring is the missing core capability.

**Independent Test**: From `/admin/courses/{id}?tab=quizzes`, create a quiz, add at least one single-choice and one text question with options/points as applicable, edit and delete a question, then confirm the course Quizzes tab still shows the updated quiz without visiting `/admin/quizzes/{id}`.

**Acceptance Scenarios**:

1. **Given** an admin is on the course Quizzes tab, **When** they add a new quiz, **Then** the quiz appears in the course quiz list and they remain on the same course Quizzes tab.
2. **Given** a quiz exists on the course, **When** the admin opens that quiz’s question manager on the same tab, **Then** they can add, edit, and delete questions without navigating to another admin page.
3. **Given** a single-choice or multiple-choice question, **When** the admin saves it, **Then** they must provide at least two options and mark the correct answer(s) according to the question type.
4. **Given** a text question, **When** the admin saves it, **Then** the question body and points are stored without requiring choice options.
5. **Given** questions on a quiz, **When** the admin reorders them, **Then** the new order is persisted and shown the next time they open the question manager.
6. **Given** a published quiz with fewer than one question, **When** the admin tries to keep it published (or publish it), **Then** the system blocks publish until at least one question exists (or unpublishes / keeps draft per existing publish rules).

---

### User Story 2 - Manage assignments entirely on the course Assignments tab (Priority: P1)

An admin opens a course, goes to the Assignments tab, and can create, edit, publish/archive status, and delete assignments for that course without going to the global assignments area or a separate assignment detail page for routine management.

**Why this priority**: Assignment create/edit already exists on the tab, but “view” still sends the admin away. The course page should be the self-contained place for course-scoped assignment administration.

**Independent Test**: From `/admin/courses/{id}?tab=assignments`, create, edit, change status, and delete an assignment, and open assignment details (description, due date, linked lesson, status) in-place — without visiting `/admin/assignments` or `/admin/assignments/{id}` for those tasks.

**Acceptance Scenarios**:

1. **Given** an admin is on the course Assignments tab, **When** they create or edit an assignment (title, description, optional lesson, due date, status), **Then** changes save and they remain on the same course Assignments tab.
2. **Given** an assignment on the course, **When** the admin opens its in-tab detail panel, **Then** they see the assignment’s key fields without leaving the course page.
3. **Given** an assignment on the course, **When** the admin deletes it (with confirmation), **Then** it is removed from the list and they remain on the Assignments tab.

---

### User Story 3 - Review assignment submissions without leaving the course (Priority: P2)

An admin on the course Assignments tab can open an assignment’s submissions list on the same page, see student submission status, and grade or request resubmit without navigating to a separate assignment show URL.

**Why this priority**: Completes “no need to go elsewhere” for day-to-day assignment work; grading is common but secondary to creating/editing the assignment itself.

**Independent Test**: With at least one student submission, open submissions from the course Assignments tab, grade one submission, and confirm the admin never left `/admin/courses/{id}?tab=assignments` (or an in-page overlay on that URL).

**Acceptance Scenarios**:

1. **Given** an assignment with submissions, **When** the admin opens submissions from the course tab, **Then** they see each student’s submission status (pending/graded/late as applicable) on the same course page.
2. **Given** a pending submission, **When** the admin enters a grade and saves, **Then** the grade is stored and the student-facing graded state updates according to existing grading rules.
3. **Given** a graded submission, **When** the admin requests resubmit (if that action is available in the product), **Then** the request is recorded without leaving the course page.

---

### User Story 4 - Keep global quiz/assignment indexes as optional overview (Priority: P3)

Cross-course quiz and assignment index pages may still exist for platform-wide browsing, but the primary authoring path for a course’s assessments is the course detail tabs. Links from the course tabs no longer force the admin to leave for basic create/edit/question/submission work.

**Why this priority**: Avoids breaking existing bookmarks while centering the course as the workspace.

**Independent Test**: Course Quizzes/Assignments tabs no longer require outbound “أسئلة” / “عرض” navigation for core tasks; global indexes still list resources if retained.

**Acceptance Scenarios**:

1. **Given** an admin on the course Quizzes tab, **When** they manage questions, **Then** they are not required to open the standalone quiz show page.
2. **Given** an admin on the course Assignments tab, **When** they manage an assignment or its submissions, **Then** they are not required to open the standalone assignment show page for those actions.

---

### Edge Cases

- What happens when the admin deletes a question that already has student attempt answers? The system either soft-blocks deletion with a clear message, or deletes and cleans related answers per existing data integrity rules — deletion must not corrupt attempt history silently.
- What happens when publishing a quiz with zero questions? Publish is refused with a clear Arabic message.
- What happens when a multiple-choice question is saved with no correct option marked? Save is refused until at least one correct option is marked.
- What happens when a single-choice question has more than one correct option marked? Save is refused or only one correct option is allowed.
- How does the system handle deleting an assignment that has graded submissions? Follow existing business rule (block delete or archive instead) with a clear message on the course tab.
- What happens if two admins edit the same quiz questions concurrently? Last successful save wins; the next page load shows the current server state.
- Empty course with no lessons: assignment lesson field remains optional; quiz creation still works with course only.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: From the course Quizzes tab, admins MUST be able to create, edit, publish/unpublish, and delete quizzes for that course without leaving the course page.
- **FR-002**: From the course Quizzes tab, admins MUST be able to add, edit, delete, and reorder questions for a selected quiz without leaving the course page.
- **FR-003**: Question authoring MUST support at least these types: single choice, multiple choice, and text — matching the platform’s existing question model.
- **FR-004**: For choice questions, admins MUST be able to manage option text and mark correct answer(s); the system MUST validate option count and correctness rules before save.
- **FR-005**: Each question MUST allow setting body text, type, points, and position/order.
- **FR-006**: From the course Assignments tab, admins MUST be able to create, edit, change status, and delete assignments for that course without leaving the course page.
- **FR-007**: From the course Assignments tab, admins MUST be able to open an assignment’s details in place (without navigating to a separate assignment page) for routine management.
- **FR-008**: From the course Assignments tab, admins MUST be able to view that assignment’s submissions and perform grade / resubmit actions available in the product without navigating to a separate assignment page.
- **FR-009**: After any successful create/update/delete of quizzes, questions, or assignments initiated from a course tab, the admin MUST remain on (or return to) the same course tab.
- **FR-010**: Destructive actions (delete quiz, question, or assignment) MUST require confirmation and show a clear Arabic outcome message on success or failure.
- **FR-011**: Standalone global quiz/assignment pages MAY remain for cross-course overview, but MUST NOT be required for course-scoped authoring described above.
- **FR-012**: Only users who already have admin permission to manage quizzes and assignments MAY use these in-tab capabilities (same role gates as existing admin assessment tools).

### Key Entities

- **Course**: Container; Quizzes and Assignments tabs are scoped to one course.
- **Quiz**: Course-owned assessment with title, duration, publish status, and show-correct-answers setting.
- **Question**: Belongs to a quiz; has type (single / multiple / text), body, points, and position.
- **Question option**: Belongs to a choice question; has body and correctness flag.
- **Assignment**: Course-owned task with title, description, optional lesson link, due date, and status.
- **Assignment submission**: Student work against an assignment; status and grade used in the in-tab review flow.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: An admin can create a quiz and add three questions (mixed types) entirely from the course Quizzes tab in under 5 minutes without opening another admin URL.
- **SC-002**: An admin can create and edit an assignment entirely from the course Assignments tab in under 2 minutes without opening another admin URL.
- **SC-003**: 100% of routine course-scoped quiz/question and assignment CRUD tasks that previously required leaving the course page can be completed while staying on the course detail URL with the relevant tab.
- **SC-004**: Publishing a quiz with no questions fails with a clear message in 100% of attempts (no silent publish).
- **SC-005**: In usability checks, admins can locate “manage questions” and “manage submissions” from the course tabs on the first try without using the global sidebar Assessment links.

## Assumptions

- Course Quizzes and Assignments tabs already list resources and support basic quiz/assignment create-edit-delete; this feature extends them with full question management and in-place assignment detail/submissions.
- Question types follow the existing platform model: `single`, `multiple`, and `text`.
- Global `/admin/quizzes` and `/admin/assignments` indexes remain available unless a later decision removes them; they are not the primary authoring surface for a single course.
- Attempt statistics, leaderboards, import/export question banks, and bulk remind are out of scope for this feature unless already present on the course tab.
- Arabic UI copy continues to match the existing admin language conventions.
- Existing publish, grading, and delete business rules for quizzes and assignments continue to apply; this feature changes *where* the admin works, not the core policy rules (except clarifying publish-requires-questions if not already enforced).
- Instructor-facing quiz tools are out of scope; this feature targets the admin course detail experience.
