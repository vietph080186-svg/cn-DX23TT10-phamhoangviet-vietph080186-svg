@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
    <main class="login-page">
        <section class="login-box">
            <h1 class="page-title">Đăng nhập</h1>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="checkbox-row">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ghi nhớ đăng nhập</span>
                </label>

                <button class="button" type="submit">Đăng nhập</button>
            </form>
        </section>
    </main>
@endsection
