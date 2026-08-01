# Page: Semesters (الفصول الدراسية)

### 1. Purpose (الهدف)
Manage semesters/terms within academic years for course scheduling and filtering.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الفصول الدراسية** · Route: `admin.semesters.index` · Group: Structure

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.semesters.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
CRUD table (name, academic_year, start, end, status), link to parent year filter.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Activate, Deactivate, Export.

### 6. Filters & Search (البحث والفلاتر)
Search: name. Filters: academic year, status, date range.

### 7. Validation Rules (قواعد التحقق)
Required: name, academic_year_id, dates within parent year bounds. Unique name per year.

### 8. Business Rules (قواعد العمل)
Deactivate hides semester from new course assignment; existing courses unchanged.

### 9. Notifications (الإشعارات الناتجة)
None by default.

### 10. Reports (التقارير المرتبطة)
Semester enrollment and completion reports.

### 11. Database Tables (الجداول المستخدمة)
`semesters`, `academic_years`, `courses`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, activate/deactivate.

### 13. Future Enhancements (أفكار مستقبلية)
Registration windows per semester, exam period flags.
