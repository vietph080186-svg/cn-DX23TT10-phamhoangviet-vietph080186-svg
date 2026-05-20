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
- Quản lý người dùng, phòng ban, dự án và danh mục công việc.
- Quản lý công việc: danh sách, thêm, xem, sửa, xóa, tìm kiếm và lọc.
- Giao công việc cho nhân viên.
- Nhân viên xem công việc được giao, cập nhật trạng thái và gửi ghi chú kết quả.
- Admin và Manager duyệt hoàn thành hoặc yêu cầu sửa lại công việc.
- Bình luận trong chi tiết công việc.
- Ghi lịch sử thay đổi trạng thái công việc.
- Tạo thông báo cơ bản khi giao việc, gửi duyệt, hoàn thành hoặc yêu cầu sửa lại.
- Bảng Kanban công việc theo trạng thái, có bộ lọc và nút chuyển trạng thái.

## Phân quyền chính

- Admin: quản lý toàn bộ người dùng, phòng ban, dự án, danh mục, công việc và bảng Kanban.
- Manager: quản lý dự án, danh mục, công việc do mình tạo hoặc được giao, và bảng Kanban liên quan.
- Staff: chỉ xem công việc được giao, cập nhật tiến độ, gửi kết quả, bình luận và xem Kanban cá nhân.

## Luồng xử lý công việc

- Staff chuyển `Mới giao` sang `Đang làm`.
- Staff chuyển `Đang làm` sang `Chờ duyệt` sau khi nhập kết quả.
- Admin hoặc Manager duyệt `Hoàn thành` hoặc yêu cầu `Cần sửa lại`.
- Staff có thể chuyển `Cần sửa lại` về `Đang làm`.
- Bảng Kanban hiện dùng nút thao tác để chuyển trạng thái, chưa hỗ trợ kéo thả.

## Thiết lập cơ sở dữ liệu

```bash
cd scr
php artisan migrate:fresh --seed
```

## Chạy ứng dụng

```bash
cd scr
php artisan serve
```

Mở trình duyệt tại:

```text
http://127.0.0.1:8000/login
```

## Tài khoản demo

| Vai trò | Email | Mật khẩu |
| --- | --- | --- |
| Admin | admin@example.com | password |
| Manager | manager@example.com | password |
| Staff | staff@example.com | password |

## Tệp SQL tham khảo

- `setup/database/schema.sql`: cấu trúc bảng cơ sở dữ liệu chính.
- `setup/database/seed.sql`: dữ liệu mẫu tương ứng với seeder Laravel.

## Trạng thái hiện tại

Đã hoàn thành nền tảng cơ sở dữ liệu, đăng nhập, dashboard theo vai trò, quản lý danh mục nền tảng, quy trình giao việc cơ bản và Kanban dạng nút thao tác. Báo cáo nâng cao, giao diện thông báo và Kanban kéo thả sẽ được thực hiện ở giai đoạn sau.
