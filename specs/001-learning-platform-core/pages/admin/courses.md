# Page: Courses (المقررات)

### 1. Purpose (الهدف)
Full admin control over catalog courses: list, create, publish, assign teachers/terms, and manage tabbed course detail.

### 2. Navigation (مكانها في القائمة)
Sidebar: **المقررات** · Route: `admin.courses.index` · Group: Catalog

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.courses.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Data table (name, image, term, year, teacher, price, student/lesson counts, status), `<x-admin.tab-nav>` on show (General, Lessons, Files, Videos, Quizzes, Assignments, Students, Analytics, Reviews, Settings).

### 5. Actions (كل العمليات الممكنة)
Create, Edit, View, Delete, Archive, Duplicate, Publish, Hide, Assign Teacher/Semester, Export list.

### 6. Filters & Search (البحث والفلاتر)
Search: title, slug. Filters: status, category, teacher, academic year, semester, price range.

### 7. Validation Rules (قواعد التحقق)
Required: title, slug (unique), price ≥ 0. Image max 2MB. Teacher must have instructor role.

### 8. Business Rules (قواعد العمل)
Cannot delete course with active enrollments without archive. Published courses require ≥1 lesson. Hide preserves enrollments read-only.

### 9. Notifications (الإشعارات الناتجة)
Notify enrolled students on publish/hide/price change (configurable in Settings).

### 10. Reports (التقارير المرتبطة)
Course performance, enrollment, revenue-by-course exports.

### 11. Database Tables (الجداول المستخدمة)
`courses`, `categories`, `lessons`, `enrollments`, `users`, `academic_years`, `semesters`, `reviews`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Create/update/delete, publish/hide, archive, duplicate, teacher assignment.

### 13. Future Enhancements (أفكار مستقبلية)
Course templates, prerequisite chains, version history.
