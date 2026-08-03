# Research: Lesson Video, Content & Exam

## Decision 1: Main video representation

**Decision**: Add `lessons.main_media_asset_id` (nullable FK → `media_assets`) rather than only inferring “first video”.

**Rationale**: Admins may upload multiple videos/files; explicit main pointer matches the product story and avoids fragile ordering heuristics.

**Alternatives considered**: Flag `media_assets.is_primary` (also viable); rejected as sole approach because lesson-level FK is simpler for queries and admin forms.

## Decision 2: Rich text storage

**Decision**: Store sanitized HTML in `lessons.content_html` (mediumText/longText). Keep short `description` for lists/subtitles.

**Rationale**: Admin needs free-form explanation (headings, lists, links, bold). Plain textarea is insufficient.

**Alternatives considered**: Markdown (nicer security, worse WYSIWYG for Arabic admin unless editor added); JSON blocks (overkill). Prefer a simple WYSIWYG or enhanced textarea with sanitizer first.

## Decision 3: Exam = existing Quiz

**Decision**: Continue using `lessons.quiz_id`; student starts exam via existing `student.quizzes.*` routes after unlock.

**Rationale**: Quiz engine, attempts, scoring already exist. No parallel “exam” entity.

## Decision 4: Watch completion rule

**Decision**: Mark watch complete when client reports `ended` **or** `currentTime / duration >= 0.9` (whichever first), via authenticated POST that updates `lesson_progress`. Persist `video_completed_at`. Seeking alone to the end without play may still fire `ended` in some browsers — accept for v1; note abuse risk in admin docs.

**Rationale**: Simple, works with HTML5 `<video>` and streamed responses if playable in-browser. If MIME/streaming prevents inline play, fall back: “Mark video as watched” control + existing “تعليم كمكتمل”.

**Alternatives considered**: Require full `completed` lesson status before exam (stricter, worse UX if student wants exam after video but before optional PDFs).

## Decision 5: Exam gating without video

**Decision**: If no main video, exam unlocks when student marks lesson complete (existing complete action) **or** immediately if product prefers — **choose**: unlock exam when `lesson_progress.status = completed` OR `video_completed_at` set. UI copy explains the rule.

## Decision 6: Student UI structure

**Decision**: Operate-mode layout: primary column = player + rich text + materials + exam band; sidebar = course lesson path (already started in recent polish). Align tokens with `DESIGN.md`.

## Decision 7: Sanitization

**Decision**: Sanitize on save with an allowlist (p, br, strong, em, ul, ol, li, a[href], h2–h4, blockquote). Strip scripts/styles/iframes.
