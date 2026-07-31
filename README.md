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
