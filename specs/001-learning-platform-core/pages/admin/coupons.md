# Page: Coupons (كوبونات الخصم)

### 1. Purpose (الهدف)
Create and manage discount coupons: limits, course/student assignment, activation lifecycle.

### 2. Navigation (مكانها في القائمة)
Sidebar: **كوبونات الخصم** · Route: `admin.coupons.index` · Group: Commerce

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.coupons.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Coupons table (code, type, value, usage/limit, status, expires), create/edit form, generate-codes action, usage stats KPI.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Activate, Deactivate, Generate Batch, Duplicate, Assign Course/Student, Export usage.

### 6. Filters & Search (البحث والفلاتر)
Search: code. Filters: status, type (percent/fixed), expiry, course, exhausted.

### 7. Validation Rules (قواعد التحقق)
Code unique, alphanumeric 6–20 chars. Percent 1–100. Fixed ≤ max order. expires_at optional, must be future on create.

### 8. Business Rules (قواعد العمل)
One coupon per order unless stackable flag (off in v1). Deactivate prevents new redemptions; existing orders honored.

### 9. Notifications (الإشعارات الناتجة)
Optional email student when personal coupon assigned.

### 10. Reports (التقارير المرتبطة)
Coupon redemption report (Marketing + Admin Reports).

### 11. Database Tables (الجداول المستخدمة)
`coupons`, `coupon_redemptions`, `orders`, `courses`, `users`.

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, activate/deactivate, batch generate, assign.

### 13. Future Enhancements (أفكار مستقبلية)
Referral-linked coupons, A/B test cohorts, auto-expire job dashboard.
