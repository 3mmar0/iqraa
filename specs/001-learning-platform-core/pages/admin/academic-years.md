# Page: Academic Years (السنوات الدراسية)

### 1. Purpose (الهدف)
Define academic year periods used to scope courses, enrollments, and reporting.

### 2. Navigation (مكانها في القائمة)
Sidebar: **السنوات الدراسية** · Route: `admin.academic-years.index` · Group: Structure

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.academic-years.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Simple CRUD table (name, code, start_date, end_date, is_current, courses count), set-current toggle.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Set as Current, Export list.

### 6. Filters & Search (البحث والفلاتر)
Search: name, code. Filters: current only, date overlap, has courses.

### 7. Validation Rules (قواعد التحقق)
Required: name, code (unique), start_date, end_date. end_date > start_date. Only one `is_current` at a time.

### 8. Business Rules (قواعد العمل)
Delete blocked if courses or semesters linked; archive instead. Changing current year does not retroactively move enrollments.

### 9. Notifications (الإشعارات الناتجة)
None by default.

### 10. Reports (التقارير المرتبطة)
Enrollment and revenue filtered by academic year.

### 11. Database Tables (الجداول المستخدمة)
`academic_years`, `semesters`, `courses`, `enrollments`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, set current.

### 13. Future Enhancements (أفكار مستقبلية)
Auto-roll forward year, holiday calendar linkage.
