# سیستم مدیریت ERP

سیستم مدیریت ساده برای مدیریت مشتریان، محصولات و فاکتورها

## نیازمندی‌ها

- PHP >= 8.2
- Composer
- Node.js & NPM

## نصب و راه‌اندازی

```bash
# نصب وابستگی‌ها
composer install
npm install

# کپی فایل تنظیمات
cp .env.example .env

# تولید کلید اپلیکیشن
php artisan key:generate

# اجرای migration
php artisan migrate

# اجرای seeder (اختیاری)
php artisan db:seed

# کامپایل فایل‌های frontend
npm run build
```

## اجرای پروژه

```bash
# اجرای سرور توسعه
php artisan serve

# یا استفاده از script آماده
composer run dev
```

سپس به آدرس `http://localhost:8000` بروید.

## استفاده

1. ثبت‌نام یا ورود به سیستم
2. مدیریت مشتریان از بخش Customers
3. مدیریت محصولات از بخش Products
4. ایجاد و مدیریت فاکتورها از بخش Invoices
5. مشاهده آمار و گزارشات در Dashboard

## ویژگی‌ها

- مدیریت مشتریان
- مدیریت محصولات و موجودی
- ایجاد و مدیریت فاکتورها
- داشبورد با آمار و گزارشات
- پشتیبانی از تاریخ شمسی (جلالی)
