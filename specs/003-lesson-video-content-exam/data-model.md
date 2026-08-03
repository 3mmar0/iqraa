# Data Model: Lesson Video, Content & Exam

## Lesson (extended)

| Field | Type | Rules |
|-------|------|--------|
| id, course_id, title | existing | required |
| description | text nullable | short blurb for lists |
| **content_html** | mediumText/longText nullable | **NEW** — sanitized rich explanation |
| position, status, is_locked, published_at | existing | |
| quiz_id | FK nullable | lesson exam (published quiz preferred for student unlock) |
| **main_media_asset_id** | FK nullable → media_assets | **NEW** — main lesson video |

**Rules**:
- `main_media_asset_id` must belong to this lesson and `type = video` when set.
- On media delete, null out `main_media_asset_id` if it pointed at deleted asset.
- Secondary materials = `media_assets` for lesson excluding main video id.

## MediaAsset (unchanged shape)

| Field | Rules |
|-------|--------|
| lesson_id, type (`video`\|`pdf`\|`attachment`), disk, path, original_name, mime, size | existing |

Optional later: `is_primary` bool — not required if lesson FK is enough.

## LessonProgress (extended)

| Field | Type | Rules |
|-------|------|--------|
| user_id, lesson_id | unique | existing |
| status | not_started \| in_progress \| completed | existing |
| completed_at | nullable | existing |
| last_position_seconds | unsigned int | existing — video resume |
| **video_completed_at** | timestamp nullable | **NEW** — watch finished |

**Derived**:
- `watchCompleted` = `video_completed_at !== null`
- `examUnlocked` = (`watchCompleted` OR (no main video AND status completed)) AND quiz published

## Quiz / QuizAttempt

Unchanged. Lesson references quiz; attempts remain user+quiz scoped.

## State transitions (student)

```text
open lesson → in_progress
play video → update last_position_seconds
reach threshold/ended → set video_completed_at → examUnlocked
mark complete → status=completed (may also set video_completed_at if no video)
start quiz → existing attempt flow
```
