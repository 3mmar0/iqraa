# Page: Assignments (الواجبات)

### 1. Purpose (الهدف)
Manage assignments and review student submissions from a central admin queue.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الواجبات** · Route: `admin.assignments.index` · Group: Assessment

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.assignments.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Assignments table, submission review list, grade modal, file preview, due-date badges.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Publish, Grade Submission, Request Resubmit, Export submissions, Bulk remind.

### 6. Filters & Search (البحث والفلاتر)
Search: title, student name. Filters: course, status (pending/graded/late), due date, grader.

### 7. Validation Rules (قواعد التحقق)
Required: title, course_id, due_at. Max file size per Settings. Grade 0–100 or rubric scale.

### 8. Business Rules (قواعد العمل)
Late submissions flagged; resubmit clears prior grade pending re-review. Delete blocked if graded submissions exist (archive instead).

### 9. Notifications (الإشعارات الناتجة)
Student notify on assign, grade, resubmit request, due reminder.

### 10. Reports (التقارير المرتبطة)
Assignment completion and grading turnaround reports.

### 11. Database Tables (الجداول المستخدمة)
`assignments`, `assignment_submissions`, `courses`, `users`, `media`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, grade changes, resubmit requests, bulk remind.

### 13. Future Enhancements (أفكار مستقبلية)
Rubric templates, plagiarism check integration, peer review.
