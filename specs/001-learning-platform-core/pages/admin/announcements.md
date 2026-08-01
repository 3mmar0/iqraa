# Page: Announcements (الإعلانات)

### 1. Purpose (الهدف)
Create, schedule, publish, and archive platform announcements with multi-channel delivery.

### 2. Navigation (مكانها في القائمة)
Sidebar: **الإعلانات** · Route: `admin.announcements.index` · Group: Communications

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.announcements.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Announcements table (title, audience, status, scheduled_at, channels), rich-text editor, preview, pin toggle.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Save Draft, Schedule, Publish, Delete, Notify, Pin, Unpin, Archive.

### 6. Filters & Search (البحث والفلاتر)
Search: title. Filters: status (draft/scheduled/published/archived), audience, channel, date.

### 7. Validation Rules (قواعد التحقق)
Required: title, body. scheduled_at must be future. Audience: all/students/course/group.

### 8. Business Rules (قواعد العمل)
Only one pinned global announcement. Scheduled publish via queue. Archive hides from feeds, keeps history.

### 9. Notifications (الإشعارات الناتجة)
In-app, email, Telegram per selected channels on publish.

### 10. Reports (التقارير المرتبطة)
Announcement reach / open rate (when tracking enabled).

### 11. Database Tables (الجداول المستخدمة)
`announcements`, `notification_logs`, `courses`, `groups`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Create/update/delete, publish, schedule, pin, archive.

### 13. Future Enhancements (أفكار مستقبلية)
A/B subject lines, read receipts, push notifications.
