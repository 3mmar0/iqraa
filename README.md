# يُطْمَئِن — Learning Platform

منصة تعليمية عربية (RTL) مبنية بـ Laravel 12.

## المتطلبات

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL 8+ و Redis للإنتاج (للتطوير المحلي يمكن SQLite + file drivers)

## التثبيت

```bash
composer install
cp .env.example .env
php artisan key:generate
# اضبط DB_* أو استخدم SQLite كما في التطوير
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

كلمة مرور الحسابات التجريبية من الـ seeder: `password`

أمثلة: `student@example.com`, `approver@example.com`, `admin@example.com`, `multi@example.com`

## ملاحظة

واجهة عربية بالكامل — لا يوجد مبدّل لغة.

## لوحة Super Admin (وحدات)

المسار `/admin` (دور `super_admin` فقط). المنطق موزّع على وحدات تحت `modules/`:

| وحدة | مسؤولية |
|------|---------|
| `Admin` | الصفحة الرئيسية، الإحصائيات، الهيكل العام |
| `Students` | إدارة الطلاب والعمليات الجماعية |
| `Catalog` | المقررات / الدروس / التصنيفات |
| `Quizzes` | الاختبارات الإدارية |
| `Finance` | الطلبات والمدفوعات (مع لوحة Finance المستقلة) |
| `Marketing` | الكوبونات والحملات (مع لوحة Marketing) |
| `Teaching` | المعلمون |
| `Notifications` | تيليجرام / إعلانات |
| `Reports` | تقارير الإدارة |
| `Settings` | إعدادات المنصة المبوّبة |
| `Team` / `Support` | روابط نظرة عامة فقط |

مواصفات الصفحات: `specs/001-learning-platform-core/pages/admin/`  
قالب الصفحة: `specs/001-learning-platform-core/admin-page-template.md`

للإنتاج شغّل أيضاً: `php artisan queue:work` وScheduler عبر Cron.
