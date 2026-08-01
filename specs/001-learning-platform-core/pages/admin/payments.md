# Page: Payments (المدفوعات)

### 1. Purpose (الهدف)
Verify, approve, reject, refund, and record manual payments with full audit trail.

### 2. Navigation (مكانها في القائمة)
Sidebar: **المدفوعات** · Route: `admin.payments.index` · Group: Commerce

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.payments.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Payments table (reference, student, amount, method, status, verified_at), verify modal, manual payment form, export actions.

### 5. Actions (كل العمليات الممكنة)
View, Verify, Approve, Reject, Refund, Record Manual Payment, Export, Attach receipt proof.

### 6. Filters & Search (البحث والفلاتر)
Search: reference, student. Filters: status, method, date range, amount, pending verification.

### 7. Validation Rules (قواعد التحقق)
Amount > 0. Manual payment requires order or student target. Receipt image optional, max 5MB.

### 8. Business Rules (قواعد العمل)
Double verification blocked. Refund syncs with order status. Pending payments expire after configurable days (Settings).

### 9. Notifications (الإشعارات الناتجة)
Student notify on verify/reject/refund; Finance dashboard counters refresh.

### 10. Reports (التقارير المرتبطة)
Payment ledger, reconciliation, daily revenue exports.

### 11. Database Tables (الجداول المستخدمة)
`payments`, `orders`, `users`, `payment_proofs`.

### 12. Audit Logs (ما الذي يتم تسجيله)
All status changes, manual entries, refunds—high sensitivity.

### 13. Future Enhancements (أفكار مستقبلية)
Gateway webhook viewer, multi-currency, automated reconciliation.
