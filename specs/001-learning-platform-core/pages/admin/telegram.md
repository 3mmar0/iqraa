# Page: Telegram (تيليجرام)

### 1. Purpose (الهدف)
Configure Telegram groups, invite links, and broadcast announcements to connected channels.

### 2. Navigation (مكانها في القائمة)
Sidebar: **تيليجرام** · Route: `admin.telegram.index` · Group: Communications

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.telegram.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Groups table (name, chat_id, course/group link, invite status), create/attach form, send announcement composer, invite link generator.

### 5. Actions (كل العمليات الممكنة)
Create Group Record, Attach to Course/Group, Generate Invite, Expire Invite, Send Announcement, Test Bot Connection.

### 6. Filters & Search (البحث والفلاتر)
Search: group name, chat_id. Filters: linked course, active invite, bot status.

### 7. Validation Rules (قواعد التحقق)
Valid chat_id format. Bot token configured in Settings. Message max 4096 chars.

### 8. Business Rules (قواعد العمل)
Invite links expire per Settings TTL. Failed sends queue retry; do not double-post on retry success.

### 9. Notifications (الإشعارات الناتجة)
Telegram message to channel; optional mirror in-app announcement.

### 10. Reports (التقارير المرتبطة)
Delivery log export (message id, status, timestamp).

### 11. Database Tables (الجداول المستخدمة)
`telegram_groups`, `courses`, `groups`, `announcements`.

### 12. Audit Logs (ما الذي يتم تسجيله)
Create/attach, invite generate/expire, send announcement, bot test.

### 13. Future Enhancements (أفكار مستقبلية)
Two-way bot commands, member sync from Telegram API.
