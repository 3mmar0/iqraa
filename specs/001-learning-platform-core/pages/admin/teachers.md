# Page: Teachers (المعلمون)

### 1. Purpose (الهدف)
Manage instructor accounts: assign courses/lessons, suspend, and view teaching analytics.

### 2. Navigation (مكانها في القائمة)
Sidebar: **المعلمون** · Route: `admin.teachers.index` · Group: People

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.teachers.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Teachers table (name, email, courses count, students, status), profile view, course assignment picker, analytics mini-charts.

### 5. Actions (كل العمليات الممكنة)
Add, Edit, Delete, Suspend, Activate, Assign Courses/Lessons, View Analytics, Reset Password, Export list.

### 6. Filters & Search (البحث والفلاتر)
Search: name, email. Filters: status, course assigned, registration date.

### 7. Validation Rules (قواعد التحقق)
Required: name, email (unique). Must hold instructor role on create. Bio optional max 2000 chars.

### 8. Business Rules (قواعد العمل)
Cannot delete teacher with active courses without reassignment. Suspend blocks instructor dashboard login.

### 9. Notifications (الإشعارات الناتجة)
Email on account create and course assignment.

### 10. Reports (التقارير المرتبطة)
Teacher activity, course completion, student feedback reports.

### 11. Database Tables (الجداول المستخدمة)
`users`, `roles`, `courses`, `lessons`, `reviews`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, suspend, course/lesson assignment, password reset.

### 13. Future Enhancements (أفكار مستقبلية)
Teacher performance scorecards, payout preview (Finance link).
