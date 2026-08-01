# Page: Students (الطلاب)

### 1. Purpose (الهدف)
Manage all student accounts: search, filter, CRUD, enrollment, discounts, bulk ops, and profile inspection.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الطلاب** · Route: `admin.students.index` · Group: People

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.students.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
`<x-admin.page-header>`, `<x-admin.filter-bar>`, `<x-admin.data-table>` (avatar, name, phone, email, university, group, status, registered_at, last_login, subscription), `<x-admin.bulk-bar>`, profile `<x-admin.tab-nav>` on show page.

### 5. Actions (كل العمليات الممكنة)
Add, Edit, View, Delete, Suspend, Activate, Reset Password, Login As, Send Notification/Email, Assign/Remove Course, Add Discount, Export CSV/Excel, bulk suspend/activate/notify/export.

### 6. Filters & Search (البحث والفلاتر)
Search: name, email, phone. Filters: status, group, university, subscription, registration date, last login.

### 7. Validation Rules (قواعد التحقق)
Required: name, email (unique), phone (optional unique). Password min 8 on create/reset. Course assignment requires active course.

### 8. Business Rules (قواعد العمل)
Cannot hard-delete student with paid orders; archive/suspend instead. Login As requires audit + session banner. Discounts cannot exceed course price.

### 9. Notifications (الإشعارات الناتجة)
In-app + email on account create, suspend, password reset, course assign/remove, bulk notify.

### 10. Reports (التقارير المرتبطة)
Student list export; links to Admin Reports → Students report.

### 11. Database Tables (الجداول المستخدمة)
`users`, `roles`, `enrollments`, `orders`, `payments`, `groups`, `group_user`, `audit_logs`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Create/update/delete, suspend, reset password, impersonate, assign course, discount, bulk actions.

### 13. Future Enhancements (أفكار مستقبلية)
Merge duplicate accounts, import CSV, parent/guardian linkage.
