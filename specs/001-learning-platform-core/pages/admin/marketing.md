# Page: Marketing (التسويق)

### 1. Purpose (الهدف)
Admin overview of marketing performance: campaigns, referrals, coupons, UTM, conversions—deep work remains on Marketing dashboard.

### 2. Navigation (مكانها في القائمة)
Sidebar: **التسويق** · Route: `admin.marketing.index` · Group: Insights

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.marketing.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
KPI cards (referrals, conversions, active campaigns), `<x-admin.chart-shell>` for funnel/UTM, deep-links to Marketing dashboard, campaign pause/resume controls.

### 5. Actions (كل العمليات الممكنة)
View Overview, Pause/Resume Campaign, Open Marketing Dashboard, Export snapshot, Manage Coupons (link).

### 6. Filters & Search (البحث والفلاتر)
Date range, campaign status, UTM source/medium, landing page.

### 7. Validation Rules (قواعد التحقق)
Campaign actions require valid campaign id and allowed state transition.

### 8. Business Rules (قواعد العمل)
Read-mostly overview; canonical campaign CRUD on Marketing dashboard. Admin can emergency-pause campaigns.

### 9. Notifications (الإشعارات الناتجة)
Optional alert to Marketing team on admin pause/resume.

### 10. Reports (التقارير المرتبطة)
Conversion, referral, UTM attribution exports.

### 11. Database Tables (الجداول المستخدمة)
`campaigns`, `referrals`, `coupons`, `orders`, `utm_events`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Campaign pause/resume from Admin overview.

### 13. Future Enhancements (أفكار مستقبلية)
Embedded full Marketing UI, cohort comparison widgets.
