# Page: Categories (التصنيفات)

### 1. Purpose (الهدف)
Organize courses into hierarchical categories with merge, archive, and restore capabilities.

### 2. Navigation (مكانها في القائمة)
Sidebar: **التصنيفات** · Route: `admin.categories.index` · Group: Catalog

### 3. Permissions (مين يقدر يدخلها)
`super_admin` only. Permission slug: `admin.categories.*`.

### 4. UI Components (الجداول، البطاقات، الأزرار)
Tree or flat table (name, slug, parent, course count, status), create/edit modal, merge wizard.

### 5. Actions (كل العمليات الممكنة)
Create, Edit, Delete, Merge, Archive, Restore, Reorder.

### 6. Filters & Search (البحث والفلاتر)
Search: name, slug. Filters: parent, status (active/archived), empty categories.

### 7. Validation Rules (قواعد التحقق)
Required: name, slug (unique). Parent cannot be self or descendant. Max depth 3 levels.

### 8. Business Rules (قواعد العمل)
Merge reassigns all courses to target category. Archive hides from public catalog; courses remain accessible to enrolled students.

### 9. Notifications (الإشعارات الناتجة)
None by default.

### 10. Reports (التقارير المرتبطة)
Courses-by-category export.

### 11. Database Tables (الجداول المستخدمة)
`categories`, `courses` (category_id FK).

### 12. Audit Logs (ما الذي يتم تسجيله)
CRUD, merge, archive, restore.

### 13. Future Enhancements (أفكار مستقبلية)
Category icons for public catalog, multilingual names (Arabic fixed in v1).
