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
- Thông báo trong hệ thống bằng cơ sở dữ liệu khi giao việc, gửi duyệt, hoàn thành, yêu cầu sửa lại và có bình luận mới.
- Bảng Kanban công việc theo trạng thái, có bộ lọc và nút chuyển trạng thái.
- Báo cáo và thống kê công việc theo trạng thái, mức ưu tiên, dự án, người dùng và phòng ban.
- Báo cáo hiệu suất nhân viên và tiến độ dự án bằng bảng và thanh tiến độ đơn giản.

## Phân quyền chính

- Admin: quản lý toàn bộ người dùng, phòng ban, dự án, danh mục, công việc và bảng Kanban.
- Manager: quản lý dự án, danh mục, công việc do mình tạo hoặc được giao, và bảng Kanban liên quan.
- Staff: chỉ xem công việc được giao, cập nhật tiến độ, gửi kết quả, bình luận và xem Kanban cá nhân.
- Báo cáo được lọc theo quyền: Admin xem toàn hệ thống, Manager xem dữ liệu liên quan, Staff xem dữ liệu cá nhân.
- Thông báo chỉ hiển thị cho đúng người nhận, không có cập nhật thời gian thực.

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

## Đường dẫn báo cáo

- `http://127.0.0.1:8000/reports`: báo cáo tổng quan.
- `http://127.0.0.1:8000/reports/tasks`: thống kê công việc.
- `http://127.0.0.1:8000/reports/users`: hiệu suất nhân viên.
- `http://127.0.0.1:8000/reports/projects`: thống kê dự án.

## Kiểm thử thông báo

1. Đăng nhập Admin hoặc Manager và tạo công việc giao cho Staff.
2. Đăng nhập Staff, mở `http://127.0.0.1:8000/notifications` và kiểm tra thông báo mới.
3. Staff chuyển công việc sang `Chờ duyệt`, sau đó kiểm tra thông báo của người tạo.
4. Admin hoặc Manager duyệt hoàn thành hoặc yêu cầu sửa lại, sau đó kiểm tra thông báo của Staff.
5. Thêm bình luận trong chi tiết công việc và kiểm tra thông báo của người liên quan.

Thông báo hiện chỉ dùng dữ liệu trong cơ sở dữ liệu, không dùng WebSocket, broadcast, email hoặc tự động làm mới.

## Kiểm thử báo cáo

1. Đăng nhập bằng tài khoản Admin, Manager và Staff.
2. Mở từng đường dẫn báo cáo.
3. Kiểm tra số liệu hiển thị theo đúng quyền.
4. Thử lọc theo ngày, dự án, phòng ban, người được giao, trạng thái và mức ưu tiên.

## Tệp SQL tham khảo

- `setup/database/schema.sql`: cấu trúc bảng cơ sở dữ liệu chính.
- `setup/database/seed.sql`: dữ liệu mẫu tương ứng với seeder Laravel.

## Trạng thái hiện tại

Đã hoàn thành nền tảng cơ sở dữ liệu, đăng nhập, dashboard theo vai trò, quản lý danh mục nền tảng, quy trình giao việc cơ bản, Kanban dạng nút thao tác, báo cáo thống kê cơ bản và thông báo trong hệ thống. Xuất báo cáo nâng cao, thông báo thời gian thực và Kanban kéo thả sẽ được thực hiện ở giai đoạn sau.
