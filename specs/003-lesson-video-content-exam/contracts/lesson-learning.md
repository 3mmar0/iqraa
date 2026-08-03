# Contract: Lesson Learning Path

## Admin — Lesson authoring

### Upsert lesson content fields

- **Routes** (existing, extended validation): `admin.lessons.store`, `admin.lessons.update`
- **Body (additional)**:
  - `content_html` (string, nullable, max reasonable size)
  - `main_media_asset_id` (nullable int, must be video asset of this lesson)
  - `quiz_id` (nullable, course-scoped quiz) — already present
- **Response**: redirect back to lesson edit/show or course Lessons tab with flash status

### Upload / designate main video

- **Upload**: existing `admin.lessons.media.store` with `type=video`
- **Designate**: on media store success OR via lesson update setting `main_media_asset_id`; optional `set_as_main=1` on upload

## Student — Lesson consume

### Show lesson

- **GET** `student.lessons.show` `{lesson}`
- **Auth**: enrolled active
- **View model**:
  - `mainVideo` (MediaAsset|null)
  - `contentHtml` (sanitized string|null)
  - `files` (collection excluding main video)
  - `examUnlocked` (bool)
  - `quiz` (Quiz|null if published)
  - progress fields, siblings path

### Report video progress

- **POST** `student.lessons.progress` `{lesson}` (new)
- **Body**: `position_seconds` (int ≥ 0), `completed` (bool optional)
- **Effect**: upsert `lesson_progress.last_position_seconds`; if `completed` or threshold met server-side confirmation, set `video_completed_at`
- **Response**: JSON `{ exam_unlocked: bool, last_position_seconds: int }` or redirect back

### Complete lesson

- **POST** `student.lessons.complete` `{lesson}` (existing)
- **Effect**: mark completed; if no main video, treat as unlock for exam

### Start exam

- **GET/POST** existing `student.quizzes.show` / `student.quizzes.start` when `examUnlocked`

## Errors

- 403 if not enrolled
- 422 if `main_media_asset_id` not a video of this lesson
- 403/422 if starting quiz while exam locked
