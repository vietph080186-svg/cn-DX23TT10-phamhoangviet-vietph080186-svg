<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống quản lý giao việc')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 32px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand {
            font-weight: 700;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .container {
            width: min(100% - 32px, 1100px);
            margin: 32px auto;
        }

        .page-title {
            margin: 0 0 20px;
            font-size: 28px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }

        .card-title {
            margin: 0 0 10px;
            color: #64748b;
            font-size: 14px;
        }

        .card-number {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .login-page {
            display: grid;
            min-height: 100vh;
            place-items: center;
            padding: 24px;
        }

        .login-box {
            width: min(100%, 420px);
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 28px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 16px;
            border: 0;
            border-radius: 6px;
            background: #2563eb;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
        }

        .button.secondary {
            background: #475569;
        }

        .error {
            margin: 8px 0 0;
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>
<body>
    @auth
        <nav class="navbar">
            <a class="brand" href="{{ route('dashboard') }}">Dashboard</a>
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
