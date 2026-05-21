@extends('layouts.app')

@section('title', 'Bảng điều khiển nhân viên')

@section('content')
    <main class="container">
        <h1 class="page-title">Bảng điều khiển nhân viên</h1>

        <section class="stats-grid">
            <article class="stat-card stat-card-primary">
                <span class="stat-icon"></span>
                <p class="stat-label">Công việc của tôi</p>
                <p class="stat-value">{{ $assignedTasks }}</p>
            </article>

            <article class="stat-card stat-card-primary">
                <span class="stat-icon"></span>
                <p class="stat-label">Mới</p>
                <p class="stat-value">{{ $newTasks }}</p>
            </article>

            <article class="stat-card stat-card-primary">
                <span class="stat-icon"></span>
                <p class="stat-label">Đang thực hiện</p>
                <p class="stat-value">{{ $inProgressTasks }}</p>
            </article>

            <article class="stat-card stat-card-success">
                <span class="stat-icon"></span>
                <p class="stat-label">Đã hoàn thành</p>
                <p class="stat-value">{{ $completedTasks }}</p>
            </article>

            <article class="stat-card stat-card-warning">
                <span class="stat-icon"></span>
                <p class="stat-label">Chờ duyệt</p>
                <p class="stat-value">{{ $reviewTasks }}</p>
            </article>

            <article class="stat-card stat-card-warning">
                <span class="stat-icon"></span>
                <p class="stat-label">Quá hạn</p>
                <p class="stat-value">{{ $overdueTasks }}</p>
            </article>
        </section>

        <p style="margin-top: 20px;">
            <a class="button" href="{{ route('reports.index') }}">Xem báo cáo cá nhân</a>
        </p>
    </main>
@endsection
