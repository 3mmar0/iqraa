# Page: Lessons (الدروس)

### 1. Purpose (الهدف)
Manage lesson content across courses: CRUD, ordering, media attachments, scheduling, and quiz linkage.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الدروس** · Route: `admin.lessons.index` · Group: Catalog

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.lessons.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Index table (title, course, order, status, video, quiz), show sections via tabs (General, Video, Files, Resources, Quiz, Notes, Comments, Settings), drag-reorder UI.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Lock/Unlock, Move, Duplicate, Reorder, Schedule Publish, Attach Quiz, Upload Video/PDF/Files.

### 6. Filters & Search (البحث والفلاتر)
Search: title. Filters: course, status (draft/published/locked), has video, has quiz, scheduled date.

### 7. Validation Rules (قواعد التحقق)
Required: title, course_id. Video/PDF size limits per Settings. Order integer ≥ 0.

### 8. Business Rules (قواعد العمل)
Locked lessons invisible to students. Scheduled publish runs via queue. Deleting lesson removes orphan media after grace period.

### 9. Notifications (الإشعارات الناتجة)
Optional student notify on new lesson publish.

### 10. Reports (التقارير المرتبطة)
Lesson completion / video watch reports (Admin Reports hub).

### 11. Database Tables (الجداول المستخدمة)
`lessons`, `courses`, `media`, `quizzes`, `lesson_progress`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, lock/unlock, reorder, schedule, media attach/delete.

### 13. Future Enhancements (أفكار مستقبلية)
Interactive H5P embeds, auto-transcription, AI summary.
