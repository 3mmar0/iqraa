# Page: Quizzes (الاختبارات)

### 1. Purpose (الهدف)
Administer quizzes: create, assign to courses/lessons, manage questions, attempts, and statistics.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الاختبارات** · Route: `admin.quizzes.index` · Group: Assessment

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.quizzes.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Index table, show tabs (Questions, Attempts, Statistics, Settings, Results, Leaderboard), question editor, import/export panel.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Duplicate, Publish/Unpublish, Assign Course/Lesson, Import/Export Questions, Randomize order, Reset attempts.

### 6. Filters & Search (البحث والفلاتر)
Search: title. Filters: course, lesson, status, date created, attempt count.

### 7. Validation Rules (قواعد التحقق)
Required: title, ≥1 question before publish. Time limit optional (minutes). Passing score 0–100.

### 8. Business Rules (قواعد العمل)
Unpublish hides new attempts; in-progress attempts may finish per setting. Duplicate creates draft copy.

### 9. Notifications (الإشعارات الناتجة)
Optional notify on quiz publish or graded result (student-facing).

### 10. Reports (التقارير المرتبطة)
Quiz performance, attempt distribution, leaderboard export.

### 11. Database Tables (الجداول المستخدمة)
`quizzes`, `quiz_questions`, `quiz_attempts`, `courses`, `lessons`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, publish/unpublish, assign, import/export, reset attempts.

### 13. Future Enhancements (أفكار مستقبلية)
Question bank pools, proctoring hooks, adaptive difficulty.
