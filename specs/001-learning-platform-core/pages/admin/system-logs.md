# Page: System Logs (سجلات النظام)

### 1. Purpose (الهدف)
Unified hub to inspect Activity, Authentication, Payment, Error, Queue, Mail, and Audit logs for troubleshooting and compliance.

### 2. Navigation (مكانها في القائمة)
Sidebar: **سجلات النظام** · Route: `admin.system-logs.index` · Group: Platform

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.system-logs.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
`<x-admin.tab-nav>` log type tabs, `<x-admin.filter-bar>`, `<x-admin.data-table>` with severity badges, detail modal, export button.

### 5. Actions (كل العمليات الممكنة)
View Log Entry, Filter, Export CSV, Copy correlation id, Link to related user/order (read-only).

### 6. Filters & Search (البحث والفلاتر)
Search: message, user email, correlation id. Filters: log type, level, date range, user, IP.

### 7. Validation Rules (قواعد التحقق)
Export date range max 90 days. Pagination required (no unbounded dump in UI).

### 8. Business Rules (قواعد العمل)
PII masked in payment/auth logs per retention policy. Logs read-only; no delete from UI in v1 (retention via jobs).

### 9. Notifications (الإشعارات الناتجة)
None from viewer; Ops may alert on error spike separately.

### 10. Reports (التقارير المرتبطة)
Audit trail export, error summary report.

### 11. Database Tables (الجداول المستخدمة)
`audit_logs`, `activity_logs`, `auth_logs`, `payment_logs`, `error_logs`, `failed_jobs`, `mail_logs`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Log export requests and sensitive log views (optional meta-audit).

### 13. Future Enhancements (أفكار مستقبلية)
Live tail stream, log retention UI, SIEM webhook.
