# Page: Admin Dashboard (لوحة التحكم)

### 1. Purpose (الهدف)
Give Super Admin a platform health snapshot in under 30 seconds: KPIs, trends, quick actions, and recent activity.

### 2. Navigation (مكانها في القائمة)
Sidebar: **لوحة التحكم** · Route: `admin.home` · Group: Overview (top)

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Middleware: `dashboard:admin`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
`<x-admin.kpi-card>` grid, `<x-admin.chart-shell>` (Revenue, Student Growth, Sales, DAU, Quiz Attempts, Subscriptions), quick-action links, recent users/audit feeds, date-range filter, refresh + export buttons.

### 5. Actions (كل العمليات الممكنة)
Refresh stats cache, apply date filter, enqueue dashboard PDF/Excel export, deep-link to Students/Courses/Ops/Security.

### 6. Filters & Search (البحث والفلاتر)
Date range (today / 7d / 30d / custom). No row-level search on home.

### 7. Validation Rules (قواعد التحقق)
Export: valid date range, max 365 days. Refresh rate-limited (e.g. 1/min per user).

### 8. Business Rules (قواعد العمل)
Aggregates served from Redis cache with scheduled refresh; stale badge if cache >15 min. Finance totals reconcile with Finance dashboard source of truth.

### 9. Notifications (الإشعارات الناتجة)
Export-ready in-app notification when queued report completes.

### 10. Reports (التقارير المرتبطة)
Dashboard summary export (PDF/Excel) via `ExportAdminDashboardJob`.

### 11. Database Tables (الجداول المستخدمة)
Read-only aggregates from `users`, `courses`, `orders`, `payments`, `quiz_attempts`, `subscriptions`, `support_tickets`, `audit_logs`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Log manual cache refresh and export requests (`dashboard.refresh`, `dashboard.export`).

### 13. Future Enhancements (أفكار مستقبلية)
Configurable widget layout, anomaly alerts, comparison vs prior period.
