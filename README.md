# Đồ án học phần: Hệ thống quản lý giao việc

## Thông tin sinh viên

- Họ và tên: Phạm Hoàng Việt
- MSSV: 170123377
- Lớp: DX23TT10
- Định danh repo: vietph080186
- Repo: cn-DX23TT10-phamhoangviet-vietph080186-svg

## Mô tả dự án

Hệ thống quản lý giao việc là ứng dụng web hỗ trợ tổ chức quản lý dự án, phân công nhiệm vụ, theo dõi trạng thái công việc và trao đổi thông tin trong quá trình thực hiện.

Dự án sử dụng Laravel. Mã nguồn chính hiện nằm trong thư mục `scr`.

## Công nghệ sử dụng

- PHP
- Laravel
- MySQL
- Blade
- CSS đơn giản

## Chức năng hiện có

- Đăng nhập và đăng xuất bằng session Laravel.
- Điều hướng dashboard theo vai trò Admin, Manager, Staff.
- Dashboard Admin hiển thị tổng người dùng, phòng ban, dự án và công việc.
- Dashboard Manager hiển thị thống kê công việc đã tạo và được giao.
- Dashboard Staff hiển thị thống kê công việc cá nhân.
- Nền tảng cơ sở dữ liệu cho vai trò, phòng ban, dự án, danh mục công việc, công việc, bình luận, lịch sử trạng thái và thông báo.

## Thiết lập cơ sở dữ liệu

1. Cấu hình kết nối cơ sở dữ liệu trong `scr/.env`.
2. Chạy migration và seed dữ liệu mẫu:

```bash
cd scr
php artisan migrate:fresh --seed
```

## Chạy ứng dụng

```bash
cd scr
php artisan serve
```

Sau đó mở trình duyệt tại:

```text
http://127.0.0.1:8000/login
```

## Tài khoản demo

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | admin@example.com | password |
| Manager | manager@example.com | password |
| Staff | staff@example.com | password |

Sau khi đăng nhập, hệ thống tự chuyển người dùng đến dashboard đúng với vai trò.

## Tệp SQL tham khảo

- `setup/database/schema.sql`: cấu trúc bảng cơ sở dữ liệu chính.
- `setup/database/seed.sql`: dữ liệu mẫu tương ứng với seeder Laravel.

## Trạng thái hiện tại

Đã hoàn thành nền tảng cơ sở dữ liệu, đăng nhập, đăng xuất và dashboard cơ bản theo vai trò. Các chức năng CRUD chi tiết sẽ được thực hiện ở giai đoạn sau.
