# Hệ thống quản lý giao việc

Mã nguồn Laravel cho ứng dụng quản lý, phân công và theo dõi tiến độ công việc trong đơn vị.

## Công nghệ

- Laravel
- PHP
- MySQL
- Blade
- CSS
- JavaScript

## Cài đặt cơ bản

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

## Tài khoản dùng thử

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Quản trị viên | admin@example.com | password |
| Quản lý | manager@example.com | password |
| Nhân viên | staff@example.com | password |
