@extends('layouts.app')

@section('title', 'Dashboard Manager')

@section('content')
    <main class="container">
        <h1 class="page-title">Dashboard Manager</h1>

        <section class="grid">
            <article class="card">
                <p class="card-title">Công việc đã tạo</p>
                <p class="card-number">{{ $createdTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Công việc được giao</p>
                <p class="card-number">{{ $assignedTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Đang chờ duyệt</p>
                <p class="card-number">{{ $reviewTasks }}</p>
            </article>

            <article class="card">
                <p class="card-title">Đã hoàn thành</p>
                <p class="card-number">{{ $completedTasks }}</p>
            </article>
        </section>
    </main>
@endsection
