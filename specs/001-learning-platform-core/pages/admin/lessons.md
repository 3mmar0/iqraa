# Page: Lessons (الدروس)

### 1. Purpose (الهدف)
Manage lesson content across courses as a three-part learning unit: **main video**, **rich-text explanation**, and **post-watch exam**, plus secondary files and ordering.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الدروس** · Route: `admin.lessons.index` · Group: Catalog  
Also: course detail → Lessons tab (`?tab=lessons`).

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.lessons.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Index table (title, course, order, status, main video, quiz). Show sections (General, Video, Files, Resources, Quiz, Notes, Comments, Settings). Edit form: short description, `content_html`, main video selector, lesson exam (`quiz_id`). Course tab modal: title/description/content_html; deep-link to lesson page for media + exam.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Lock/Unlock, Duplicate, Reorder, Schedule Publish, Attach Quiz, Upload Video/PDF/Files (`set_as_main` for video), Designate main video.

### 6. Filters & Search (البحث والفلاتر)
Search: title. Filters: course, status (draft/published/locked), has video, has quiz, scheduled date.

### 7. Validation Rules (قواعد التحقق)
Required: title, course_id. `content_html` sanitized allowlist. `main_media_asset_id` must be a video media asset of the same lesson. Video/PDF size limits per Settings.

### 8. Business Rules (قواعد العمل)
- Student path order: main video → rich text → secondary materials → exam (gated).
- Exam unlocks after `video_completed_at` (or lesson complete when no main video).
- Locked lessons invisible to students. First uploaded video becomes main if none set.
- Deleting main media clears `main_media_asset_id`.

### 9. Notifications (الإشعارات الناتجة)
Optional student notify on new lesson publish.

### 10. Reports (التقارير المرتبطة)
Lesson completion / video watch reports (Admin Reports hub).

### 11. Database Tables (الجداول المستخدمة)
`lessons` (`content_html`, `main_media_asset_id`, `quiz_id`), `media_assets`, `quizzes`, `lesson_progress` (`last_position_seconds`, `video_completed_at`).

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, lock/unlock, reorder, schedule, media attach/delete, main video designation.

### 13. Future Enhancements (أفكار مستقبلية)
WYSIWYG editor, stricter anti-seek watch proof, H5P embeds, auto-transcription.
