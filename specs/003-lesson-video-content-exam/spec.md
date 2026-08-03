# Feature Specification: Lesson Video, Content & Exam

**Feature Branch**: `003-lesson-video-content-exam`

**Created**: 2026-08-04

**Status**: Draft

**Input**: User description: "The lesson is: (1) the lesson main video the student should watch, and they can open text/PDF materials; (2) rich text where the admin describes/explains the lesson; (3) an exam after the student finishes watching that they must answer. Update student and admin dashboards to fit that."

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Admin authors the three lesson parts (Priority: P1)

An admin editing a lesson can (a) designate or upload the **main lesson video**, (b) write a **rich-text explanation** of the lesson, and (c) attach the **post-lesson exam** (quiz), without relying on ambiguous free-form media lists alone.

**Why this priority**: Authoring is the prerequisite for the student experience; without clear main video, body content, and exam linkage, student UI cannot present the intended path.

**Independent Test**: From admin lesson create/edit (or course Lessons tab), set main video, save rich HTML body, attach a published course quiz, publish the lesson, and confirm the three fields persist on reload.

**Acceptance Scenarios**:

1. **Given** an admin is editing a lesson, **When** they upload or select a video as the main lesson video, **Then** that asset is stored as the lesson’s primary video and shown as such in admin UI.
2. **Given** an admin is editing a lesson, **When** they enter rich-text explanation content and save, **Then** the HTML/body is persisted and reloaded safely (sanitized) on the next edit.
3. **Given** a published quiz exists for the course, **When** the admin attaches it as the lesson exam, **Then** the lesson stores that `quiz_id` and admin UI shows the linked exam title.
4. **Given** a lesson with only attachments and no main video, **When** the admin publishes, **Then** they may still publish (video optional) but the UI clearly indicates “no main video” so authors know the student path is incomplete.
5. **Given** additional PDFs/attachments, **When** the admin uploads them, **Then** they remain available as secondary materials (not replacing the main video).

---

### User Story 2 - Student learns via video + rich text + materials (Priority: P1)

An enrolled student opens a lesson and sees a clear learning surface: **main video first**, then the **admin rich-text explanation**, then **other materials** (PDF/attachments). They can resume video position and mark progress.

**Why this priority**: This is the core student promise of the lesson page.

**Independent Test**: As an enrolled student, open a published lesson that has main video + body + files; watch/seek video, read body, open a PDF; confirm layout matches that order and course path sidebar still works.

**Acceptance Scenarios**:

1. **Given** a published lesson with a main video, **When** the student opens the lesson, **Then** the main video is the primary content (not buried among generic file links).
2. **Given** rich-text body content exists, **When** the student views the lesson, **Then** the explanation renders below (or beside on wide screens) the video in readable Arabic RTL typography.
3. **Given** secondary PDFs/attachments, **When** the student views the lesson, **Then** they appear in a materials list distinct from the main video.
4. **Given** the student has previously watched part of the video, **When** they reopen the lesson, **Then** playback can resume from `last_position_seconds` when the player supports it.
5. **Given** no main video but body/files exist, **When** the student opens the lesson, **Then** body and materials still display without a broken video player.

---

### User Story 3 - Exam appears after finishing the lesson watch (Priority: P1)

After the student **finishes watching** the main lesson video (or completes the lesson when there is no video), the **linked exam** becomes available and they are prompted to answer it before considering the lesson path complete.

**Why this priority**: Completes the three-part lesson contract; exam must not be a random side link available before the learning moment.

**Independent Test**: Attach a quiz to a lesson with a main video; as student, confirm exam CTA is locked/hidden until watch completion (or explicit finish rule); after completion, start and submit the quiz from the lesson page.

**Acceptance Scenarios**:

1. **Given** a lesson with a main video and linked published quiz, **When** the student has not finished watching, **Then** the exam is not startable (shown locked or with clear “أكمل مشاهدة الفيديو أولاً” messaging).
2. **Given** the student reaches the watch-completion threshold (or marks video finished per product rule), **When** they return to or stay on the lesson, **Then** the exam CTA unlocks and links to the existing student quiz flow.
3. **Given** a lesson with no video but a linked quiz, **When** the student marks the lesson complete (or finishes reading path per rule), **Then** the exam unlocks.
4. **Given** the student already submitted the lesson exam, **When** they reopen the lesson, **Then** they see result/retry affordances consistent with existing quiz attempt rules.
5. **Given** no quiz is linked, **When** the student finishes the lesson, **Then** they can still mark the lesson complete without an exam section error.

---

### Edge Cases

- Lesson published with quiz that is still draft → exam section hidden; admin warned on lesson edit.
- Multiple videos uploaded historically → only one is “main”; others demoted to secondary or rejected as second main.
- Watch completion: define threshold (e.g. ≥90% of duration or player `ended` event); short videos and seek-to-end abuse documented in research.
- XSS in rich text: server-side sanitize on save; student render uses safe HTML allowlist.
- Locked lessons remain inaccessible to students (existing rule).
- Unenrolled users cannot access media or exam (existing enrollment gate).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST support a designated **main video** per lesson (FK to media asset or equivalent flag).
- **FR-002**: System MUST store a **rich-text lesson body** (`content_html` or equivalent) authored by admin.
- **FR-003**: System MUST allow attaching one **lesson exam** via existing `quiz_id` (course-scoped quiz).
- **FR-004**: Student lesson UI MUST present order: main video → rich text → secondary materials → exam (gated).
- **FR-005**: System MUST record video watch progress (`last_position_seconds` and a watch-completed flag or derived state).
- **FR-006**: System MUST unlock the lesson exam only after watch completion (or no-video completion rule).
- **FR-007**: Admin lesson forms and course Lessons tab MUST expose fields/controls for main video, rich text, and exam.
- **FR-008**: Rich text MUST be sanitized on save; student view MUST not execute unsafe scripts.
- **FR-009**: Existing enrollment, publish, and media download authorization MUST continue to apply.

### Key Entities

- **Lesson**: title, description (short), `content_html` (rich explanation), main video reference, `quiz_id`, status, position.
- **MediaAsset**: type video/pdf/attachment; one asset may be flagged or referenced as main video.
- **LessonProgress**: completion + `last_position_seconds` + watch-completed indicator.
- **Quiz / QuizAttempt**: existing exam and attempt models reused for the post-lesson exam.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Admin can configure main video + rich text + exam on a lesson in one edit session without leaving lesson admin UI.
- **SC-002**: Enrolled students see video → text → materials hierarchy on first open of a configured lesson.
- **SC-003**: Linked exam is not startable before watch completion; after completion, student can start the exam from the lesson page in under two clicks.
- **SC-004**: No XSS from admin rich text on student lesson page (sanitizer allowlist verified).
- **SC-005**: Lessons without video or without exam still function without errors (graceful empty states).
