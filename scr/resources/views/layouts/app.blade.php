<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống quản lý giao việc')</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f7fb; color: #1f2937; font-family: Arial, sans-serif; }
        a { color: inherit; text-decoration: none; }
        .navbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 32px; background: #fff; border-bottom: 1px solid #e5e7eb; }
        .nav-left, .nav-right, .actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .brand { font-weight: 700; }
        .nav-link { color: #475569; font-weight: 600; }
        .container { width: min(100% - 32px, 1100px); margin: 32px auto; }
        .page-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 20px; }
        .page-title { margin: 0; font-size: 28px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; }
        .card, .panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; }
        .card-title { margin: 0 0 10px; color: #64748b; font-size: 14px; }
        .card-number { margin: 0; font-size: 32px; font-weight: 700; }
        .login-page { display: grid; min-height: 100vh; place-items: center; padding: 24px; }
        .login-box { width: min(100%, 420px); background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 28px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="color"], input[type="url"], select, textarea { width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 15px; }
        input[type="color"] { height: 42px; padding: 4px; }
        textarea { min-height: 90px; resize: vertical; }
        table { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { background: #f8fafc; color: #475569; font-size: 14px; }
        .button { display: inline-flex; align-items: center; justify-content: center; min-height: 38px; padding: 0 14px; border: 0; border-radius: 6px; background: #2563eb; color: #fff; font-weight: 700; cursor: pointer; }
        .button.secondary { background: #475569; }
        .button.danger { background: #dc2626; }
        .button.light { background: #e2e8f0; color: #1f2937; }
        .alert { margin-bottom: 16px; padding: 12px 14px; border-radius: 6px; }
        .alert.success { background: #dcfce7; color: #166534; }
        .alert.error, .error { color: #dc2626; }
        .alert.error { background: #fee2e2; }
        .error { margin: 6px 0 0; font-size: 14px; }
        .pagination { margin-top: 16px; }
        .checkbox-row { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
        .kanban-board { display: grid; grid-template-columns: repeat(6, minmax(240px, 1fr)); gap: 16px; overflow-x: auto; padding-bottom: 12px; }
        .kanban-column { min-width: 240px; background: #eef2f7; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; }
        .kanban-title { margin: 0 0 12px; font-size: 16px; }
        .kanban-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
        .kanban-card.overdue { border-color: #fb7185; background: #fff1f2; }
        .muted { color: #64748b; font-size: 14px; }
        @media (max-width: 800px) {
            .kanban-board { display: block; overflow-x: visible; }
            .kanban-column { margin-bottom: 16px; }
        }
    </style>
</head>
<body>
    @auth
        <nav class="navbar">
            <div class="nav-left">
                <a class="brand" href="{{ route('dashboard') }}">Bảng điều khiển</a>
                <a class="nav-link" href="{{ route('kanban.index') }}">Kanban</a>
                @if (in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'manager'], true))
                    <a class="nav-link" href="{{ route('tasks.index') }}">Công việc</a>
                    <a class="nav-link" href="{{ route('projects.index') }}">Dự án</a>
                    <a class="nav-link" href="{{ route('task-categories.index') }}">Danh mục công việc</a>
                @endif
                <a class="nav-link" href="{{ route('my-tasks.index') }}">Công việc của tôi</a>
                @if (strtolower(Auth::user()->role?->name ?? '') === 'admin')
                    <a class="nav-link" href="{{ route('admin.users.index') }}">Người dùng</a>
                    <a class="nav-link" href="{{ route('admin.departments.index') }}">Phòng ban</a>
                @endif
            </div>
            <div class="nav-right">
                <span>{{ Auth::user()->full_name ?? Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="button secondary" type="submit">Đăng xuất</button>
                </form>
            </div>
        </nav>
    @endauth

    @yield('content')
</body>
</html>
