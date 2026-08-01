# Page: Settings (الإعدادات)

### 1. Purpose (الهدف)
Configure platform-wide settings across tabbed sections (General, Auth, Email, Payments, Security, etc.).

### 2. Navigation (مكانها في القائمة)
Sidebar: **الإعدادات** · Route: `admin.settings.index` · Group: Platform

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.settings.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
`<x-admin.tab-nav>` tabs: General, Platform, Authentication, Email, Telegram, Payments, Media, Storage, Cache, Queue, SEO, Theme, Languages (Arabic fixed), Security, Backup, Maintenance, Logs. Per-tab forms with save/reset.

### 5. Actions (كل العمليات الممكنة)
View Tab, Save Settings, Reset to Default (per key group), Test Email, Test Telegram, Clear Cache, Trigger Backup, Enable Maintenance Mode.

### 6. Filters & Search (البحث والفلاتر)
In-tab search for setting keys (optional). No list filters.

### 7. Validation Rules (قواعد التحقق)
Typed values per key (bool/int/url/email). SMTP and payment keys validated on test. Maintenance message required when mode on.

### 8. Business Rules (قواعد العمل)
Changes apply after save + config cache clear where needed. Maintenance mode blocks non-admin routes. Secrets masked in UI.

### 9. Notifications (الإشعارات الناتجة)
Optional staff alert on maintenance toggle or backup completion.

### 10. Reports (التقارير المرتبطة)
Settings change history export (audit-derived).

### 11. Database Tables (الجداول المستخدمة)
`platform_settings`, `audit_logs`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Every save, reset, maintenance toggle, backup trigger, cache clear—all high sensitivity.

### 13. Future Enhancements (أفكار مستقبلية)
Settings import/export, environment diff viewer, feature flags UI.
