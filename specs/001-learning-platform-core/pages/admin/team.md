# Page: Team (فريق العمل)

### 1. Purpose (الهدف)
Overview of internal team: members, tasks, roles, meetings, goals—with deep-links to Team dashboard.

### 2. Navigation (مكانها في القائمة)
Sidebar: **فريق العمل** · Route: `admin.team.index` · Group: Operations

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.team.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Member summary cards, open tasks list, role distribution chart, deep-link tiles to Team dashboard sections.

### 5. Actions (كل العمليات الممكنة)
View Overview, Open Team Dashboard, Export member list, Quick-link to user edit (staff).

### 6. Filters & Search (البحث والفلاتر)
Search: member name. Filters: role, department, task status, active only.

### 7. Validation Rules (قواعد التحقق)
Staff user edits follow Users/Roles validation when linked from overview.

### 8. Business Rules (قواعد العمل)
Read-mostly; task CRUD remains on Team dashboard. Admin sees cross-team aggregates only.

### 9. Notifications (الإشعارات الناتجة)
None from overview page directly.

### 10. Reports (التقارير المرتبطة)
Team productivity, task completion exports (Team dashboard source).

### 11. Database Tables (الجداول المستخدمة)
`users`, `roles`, `tasks`, `meetings`, `goals`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Export member list, linked staff edits (via Users module).

### 13. Future Enhancements (أفكار مستقبلية)
Org chart view, capacity planning widgets.
