# Page: Support (الدعم الفني)

### 1. Purpose (الهدف)
Super Admin overview of support queue: open tickets, SLA breaches, unread counts—with deep-link to Support dashboard.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الدعم الفني** · Route: `admin.support.index` · Group: Operations

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.support.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
KPI cards (open, urgent, avg response time), recent tickets table, `<x-admin.filter-bar>`, link to Support dashboard.

### 5. Actions (كل العمليات الممكنة)
View Ticket (read-only or assign), Open Support Dashboard, Export ticket summary, Escalate flag.

### 6. Filters & Search (البحث والفلاتر)
Search: ticket id, student. Filters: status, priority, assignee, date, category.

### 7. Validation Rules (قواعد التحقق)
Escalate requires note min 20 chars. Assign requires valid support agent user.

### 8. Business Rules (قواعد العمل)
Admin overview does not replace Support agent workflow; mutations sync with Support dashboard state.

### 9. Notifications (الإشعارات الناتجة)
Notify assignee on admin escalation.

### 10. Reports (التقارير المرتبطة)
Ticket volume, SLA, resolution time reports.

### 11. Database Tables (الجداول المستخدمة)
`support_tickets`, `ticket_messages`, `users`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Escalate, admin assign override.

### 13. Future Enhancements (أفكار مستقبلية)
Live chat monitor, CSAT dashboard embed.
