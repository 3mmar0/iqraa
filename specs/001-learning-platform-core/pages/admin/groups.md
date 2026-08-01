# Page: Groups (المجموعات)

### 1. Purpose (الهدف)
Create student groups for cohort management, bulk messaging, and scoped course access.

### 2. Navigation (مكانها في القائمة)
Sidebar: **المجموعات** · Route: `admin.groups.index` · Group: Structure

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.groups.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Groups table (name, code, member count, year/semester), member attach/detach UI, bulk import members.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Add/Remove Members, Bulk Import, Export members, Notify group.

### 6. Filters & Search (البحث والفلاتر)
Search: name, code, member name. Filters: academic year, semester, empty groups.

### 7. Validation Rules (قواعد التحقق)
Required: name. Code optional unique. Member must be student role.

### 8. Business Rules (قواعد العمل)
Student may belong to multiple groups. Delete removes memberships only, not user accounts.

### 9. Notifications (الإشعارات الناتجة)
In-app/email on group notify action.

### 10. Reports (التقارير المرتبطة)
Group attendance and progress exports.

### 11. Database Tables (الجداول المستخدمة)
`groups`, `group_user`, `users`, `academic_years`, `semesters`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, member attach/detach, bulk notify.

### 13. Future Enhancements (أفكار مستقبلية)
Auto-assign by registration rules, Telegram group sync.
