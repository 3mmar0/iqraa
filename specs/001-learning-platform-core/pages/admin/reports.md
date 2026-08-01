# Page: Reports (التقارير)

### 1. Purpose (الهدف)
Central hub to generate, schedule, and download platform reports (Students, Revenue, Courses, Quizzes, etc.).

### 2. Navigation (مكانها في القائمة)
Sidebar: **التقارير** · Route: `admin.reports.index` · Group: Insights

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.reports.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Report type cards, parameter form (date range, filters), job queue table, download links, schedule email option.

### 5. Actions (كل العمليات الممكنة)
Generate PDF/Excel/CSV, Schedule Recurring, Email on Complete, Cancel Job, Download Past Reports.

### 6. Filters & Search (البحث والفلاتر)
Per report type: date range, course, teacher, group, payment status. Job list filter by status.

### 7. Validation Rules (قواعد التحقق)
Date range required, max span per report type. Email recipients must be staff with report permission.

### 8. Business Rules (قواعد العمل)
Large exports queued; user notified when ready. Files expire after 7 days unless archived in Settings.

### 9. Notifications (الإشعارات الناتجة)
In-app + email when report job completes or fails.

### 10. Reports (التقارير المرتبطة)
Students, Revenue, Course, Quiz, Teacher, Attendance, Activity, Finance (read-only aggregates).

### 11. Database Tables (الجداول المستخدمة)
`report_jobs`, plus source tables per report type.

### 12. Audit Logs (ما الذي يتم تسجيله)
Generate, schedule, download sensitive finance reports.

### 13. Future Enhancements (أفكار مستقبلية)
Custom report builder, BI export, dashboard embed.
