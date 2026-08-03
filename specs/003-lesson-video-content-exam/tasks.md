# Tasks: Lesson Video, Content & Exam

**Input**: Design documents from `/specs/003-lesson-video-content-exam/`

**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/, quickstart.md

**Tests**: Include focused feature tests (requested implicitly by gating/security critical path)

**Organization**: Tasks grouped by user story for independent implementation and testing.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: US1 / US2 / US3 maps to spec.md user stories

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Align feature workspace and confirm baseline lesson/quiz/media code paths

- [x] T001 Confirm feature docs under `specs/003-lesson-video-content-exam/` and set `SPECIFY_FEATURE=003-lesson-video-content-exam` in the working shell when implementing
- [x] T002 [P] Inventory current lesson admin/student surfaces in `resources/views/admin/lessons/_form.blade.php`, `resources/views/admin/courses/tabs/lessons.blade.php`, and `resources/views/student/lessons/show.blade.php`
- [x] T003 [P] Choose HTML sanitizer approach (package or allowlist helper) and note it in `specs/003-lesson-video-content-exam/research.md` if it changes

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Schema + model + shared progress rules that all stories need

**âڑ ï¸ڈ CRITICAL**: No user story UI work depends on unfinished migrations/models

- [x] T004 Create migration adding `content_html` and `main_media_asset_id` to `lessons` and `video_completed_at` to `lesson_progress` in `database/migrations/`
- [x] T005 [P] Update `app/Models/Lesson.php` fillable/casts/relations (`mainMediaAsset`, keep `quiz`, `mediaAssets`)
- [x] T006 [P] Update `app/Models/LessonProgress.php` for `video_completed_at` cast/fillable
- [x] T007 Extend `app/Services/LessonProgressService.php` with methods to upsert position, mark video complete, and compute `examUnlocked`
- [x] T008 [P] Add HTML sanitizer helper/service (e.g. `app/Support/LessonContentSanitizer.php`) with allowlist from research.md
- [x] T009 Register student progress route in `routes/web.php` (`student.lessons.progress`) pointing at `app/Http/Controllers/Web/Student/LessonController.php`

**Checkpoint**: Migrate locally; models load new attributes; service methods unit-callable

---

## Phase 3: User Story 1 - Admin authors the three lesson parts (Priority: P1) ًںژ¯ MVP

**Goal**: Admin can set main video, rich-text body, and lesson exam on lesson authoring UI

**Independent Test**: Create/edit lesson with main video + `content_html` + `quiz_id`; reload form and confirm persistence

### Tests for User Story 1

- [x] T010 [P] [US1] Feature test admin can save `content_html`, `main_media_asset_id`, and `quiz_id` in `tests/Feature/Admin/LessonContentAuthoringTest.php`

### Implementation for User Story 1

- [x] T011 [P] [US1] Extend admin lesson Form Request(s) under `app/Http/Requests/` to validate `content_html`, `main_media_asset_id`, `quiz_id`
- [x] T012 [US1] Wire store/update in `app/Http/Controllers/Web/Admin/LessonController.php` to sanitize `content_html` and persist main video FK
- [x] T013 [US1] Update `resources/views/admin/lessons/_form.blade.php` with rich-text field, main-video selector/upload flag, and clearer exam (`quiz_id`) labeling
- [x] T014 [P] [US1] Align `resources/views/admin/lessons/create.blade.php` / `edit.blade.php` / `show.blade.php` copy to the three-part lesson model
- [x] T015 [US1] When uploading video via `app/Http/Controllers/Web/Admin/LessonMediaController.php`, support `set_as_main` to set `lessons.main_media_asset_id`
- [x] T016 [US1] Update `resources/views/admin/courses/tabs/lessons.blade.php` so inline lesson create/edit exposes the same three parts (or deep-links to full form with those fields required)
- [x] T017 [US1] Null `main_media_asset_id` safely when main media is deleted in admin media destroy flow

**Checkpoint**: Admin can author video + rich text + exam without leaving lesson admin flows

---

## Phase 4: User Story 2 - Student learns via video + rich text + materials (Priority: P1)

**Goal**: Student lesson page presents main video â†’ rich text â†’ secondary materials with resume support

**Independent Test**: Enrolled student opens published lesson and sees ordered sections; video position persists after reload

### Tests for User Story 2

- [x] T018 [P] [US2] Feature test student lesson show exposes main video, sanitized body, and non-main files in `tests/Feature/Student/LessonLearningPathTest.php`

### Implementation for User Story 2

- [x] T019 [US2] Expand `app/Http/Controllers/Web/Student/LessonController.php` `show` to pass `mainVideo`, `contentHtml`, `files`, progress, siblings, and exam state
- [x] T020 [US2] Redesign `resources/views/student/lessons/show.blade.php` for Operate layout: player, rich text, materials list, path sidebar (Reading Room tokens / Tajawal)
- [x] T021 [US2] Implement inline or linked video playback UX for main video (HTML5 player when streamable) calling progress endpoint; keep download/open fallback for non-playable streams
- [x] T022 [US2] Persist `last_position_seconds` from player timeupdate (throttled) via `POST student.lessons.progress` in `LessonController`
- [x] T023 [P] [US2] Empty states for missing video / empty body / no files without layout breakage in `resources/views/student/lessons/show.blade.php`

**Checkpoint**: Student learning path readable and resumable without exam gating yet (exam may show locked)

---

## Phase 5: User Story 3 - Exam appears after finishing watch (Priority: P1)

**Goal**: Linked exam unlocks only after video completion (or no-video lesson complete)

**Independent Test**: Before watch complete, quiz start blocked; after complete, student starts quiz from lesson page

### Tests for User Story 3

- [x] T024 [P] [US3] Feature test exam locked until `video_completed_at` (and no-video unlock via complete) in `tests/Feature/Student/LessonExamGateTest.php`
- [x] T025 [P] [US3] Feature test starting quiz while locked returns 403/422 from gate in `tests/Feature/Student/LessonExamGateTest.php`

### Implementation for User Story 3

- [x] T026 [US3] Implement watch-completion handling in `LessonProgressService` + `LessonController@progress` (`completed` flag / threshold)
- [x] T027 [US3] Render locked vs unlocked exam band on `resources/views/student/lessons/show.blade.php` with Arabic copy from spec
- [x] T028 [US3] Enforce unlock server-side before `Student\QuizController` start when quiz is the lessonâ€™s linked exam (shared gate helper)
- [x] T029 [US3] On `student.lessons.complete`, if lesson has no main video, set unlock conditions so exam becomes available
- [x] T030 [US3] After successful quiz submit for lesson exam, show result/retry affordance on lesson page consistent with `student.quizzes.result`
- [x] T031 [P] [US3] Hide exam section entirely when `quiz_id` empty or quiz not published; warn admin on lesson edit if quiz draft

**Checkpoint**: Full three-part lesson path works end-to-end for happy path + no-video + no-quiz

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Consistency, security, docs validation

- [x] T032 [P] Ensure student/admin lesson UI uses design tokens (no indigo washes, Tajawal on dashboards) in touched Blade files
- [x] T033 [P] Add/adjust Arabic labels and validation messages for new fields in lang or inline Blade
- [x] T034 Run `specs/003-lesson-video-content-exam/quickstart.md` validation manually and fix gaps
- [x] T035 [P] Update `specs/001-learning-platform-core/pages/admin/lessons.md` to document main video, rich text, and post-watch exam behavior
- [x] T036 Code cleanup: remove obsolete â€œgeneric media-onlyâ€‌ student assumptions that conflict with main-video hierarchy

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: start immediately
- **Foundational (Phase 2)**: depends on Setup â€” **blocks** US1â€“US3
- **US1 (Phase 3)**: after Foundational â€” admin authoring MVP
- **US2 (Phase 4)**: after Foundational; ideally after US1 so fixtures exist (can mock DB fields)
- **US3 (Phase 5)**: after US2 player/progress endpoint exists
- **Polish (Phase 6)**: after desired stories complete

### User Story Dependencies

- **US1**: no dependency on US2/US3
- **US2**: needs schema from Phase 2; better after US1 for real content
- **US3**: needs US2 progress reporting + US1 quiz linkage

### Parallel Opportunities

- T002â€“T003 in parallel
- T005â€“T006â€“T008 in parallel after T004
- T010 with T011 early
- T014 with T013 after controller wiring starts
- T024â€“T025 in parallel
- T032â€“T033â€“T035 in parallel during polish

### Parallel Example: User Story 1

```bash
Task: "Feature test admin lesson content authoring in tests/Feature/Admin/LessonContentAuthoringTest.php"
Task: "Extend admin lesson Form Requests under app/Http/Requests/"
Task: "Align admin lesson create/edit/show Blade copy"
```

---

## Implementation Strategy

### MVP First (US1 + minimal student read)

1. Phase 1â€“2 foundation
2. Phase 3 US1 admin authoring
3. Minimal student show of the three fields (subset of US2)
4. Validate authoring â†’ student visibility
5. Then finish US2 player polish + US3 gating

### Incremental Delivery

1. Foundation â†’ migrate
2. US1 â†’ admins can configure lessons
3. US2 â†’ students learn with correct hierarchy
4. US3 â†’ exam gate closes the loop
5. Polish â†’ docs + token consistency

### Suggested MVP scope

**US1 + T019/T020 (student read-only of authored fields)** â€” proves the three-part lesson model before watch-gating complexity.

---

## Notes

- Do not invent a second exam engine; reuse `Quiz` / `QuizAttempt`
- Sanitize on save; never render raw admin HTML
- Preserve enrollment checks on media and quiz routes
- Format validation: all tasks use `- [ ]`, Task IDs, story labels where required, and file paths
