# Super Admin Page Spec Template

Use this template for every Admin Dashboard page under `specs/001-learning-platform-core/pages/admin/`.

---

## Page: [Name]

### 1. Purpose (الهدف)
What this page enables the Super Admin to accomplish in one sentence.

### 2. Navigation (مكانها في القائمة)
Sidebar label, route name, parent group (if any).

### 3. Permissions (مين يقدر يدخلها)
`super_admin` and/or specific `admin.*` permission slugs.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Tables, cards, forms, tabs, charts, modals.

### 5. Actions (كل العمليات الممكنة)
List of create/edit/delete/publish/export/… actions.

### 6. Filters & Search (البحث والفلاتر)
Search fields and filter dimensions.

### 7. Validation Rules (قواعد التحقق)
Required fields, uniqueness, enums, file limits.

### 8. Business Rules (قواعد العمل)
Domain constraints (e.g. cannot delete enrolled course without archive).

### 9. Notifications (الإشعارات الناتجة)
In-app / email / Telegram side effects.

### 10. Reports (التقارير المرتبطة)
Related report types and export formats.

### 11. Database Tables (الجداول المستخدمة)
Primary tables and important FKs.

### 12. Audit Logs (ما الذي يتم تسجيله)
Actions that must call `AuditLogger`.

### 13. Future Enhancements (أفكار مستقبلية)
Deferred ideas that must not block v1.
