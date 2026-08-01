# Page: Orders (الطلبات)

### 1. Purpose (الهدف)
Oversee purchase orders: approve, reject, refund, invoice, and export—Admin ops layer above Finance dashboard.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الطلبات** · Route: `admin.orders.index` · Group: Commerce

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.orders.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Orders table (id, student, items, total, status, payment method, created_at), detail drawer, invoice PDF button, `<x-admin.filter-bar>`.

### 5. Actions (كل العمليات الممكنة)
View, Approve, Reject, Refund (partial/full), Download Invoice PDF, Export CSV/Excel, Add manual note.

### 6. Filters & Search (البحث والفلاتر)
Search: order id, student email. Filters: status, date range, amount range, payment method, course.

### 7. Validation Rules (قواعد التحقق)
Refund amount ≤ paid total. Reject requires reason (min 10 chars). Status transitions enforced by state machine.

### 8. Business Rules (قواعد العمل)
Approve grants enrollment/coupon effects atomically. Refund revokes access per policy. Finance dashboard remains reconciliation source.

### 9. Notifications (الإشعارات الناتجة)
Student email + in-app on approve/reject/refund.

### 10. Reports (التقارير المرتبطة)
Sales, revenue, order funnel exports via Admin Reports.

### 11. Database Tables (الجداول المستخدمة)
`orders`, `order_items`, `payments`, `users`, `courses`, `coupons`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Approve, reject, refund, manual status override, invoice download (optional).

### 13. Future Enhancements (أفكار مستقبلية)
Split payments, subscription orders, webhook replay.
