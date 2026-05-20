@extends('layouts.app')

@section('title', 'Dashboard Staff')

@section('content')
    <main class="container">
        <h1 class="page-title">Dashboard Staff</h1>

        <section class="grid">
            <article class="card">
                <p class="card-title">Công việc của tôi</p>
                <p class="card-number">{{ $assignedTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Mới</p>
                <p class="card-number">{{ $newTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Đang thực hiện</p>
                <p class="card-number">{{ $inProgressTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Đã hoàn thành</p>
                <p class="card-number">{{ $completedTasks }}</p>
            </article>
        </section>
    </main>
@endsection
