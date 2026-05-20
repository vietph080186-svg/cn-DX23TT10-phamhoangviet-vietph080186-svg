@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <main class="container">
        <h1 class="page-title">Dashboard Admin</h1>

        <section class="grid">
            <article class="card">
                <p class="card-title">Tổng người dùng</p>
                <p class="card-number">{{ $totalUsers }}</p>
            </article>

            <article class="card">
                <p class="card-title">Tổng phòng ban</p>
                <p class="card-number">{{ $totalDepartments }}</p>
            </article>

            <article class="card">
                <p class="card-title">Tổng dự án</p>
                <p class="card-number">{{ $totalProjects }}</p>
            </article>

            <article class="card">
                <p class="card-title">Tổng công việc</p>
                <p class="card-number">{{ $totalTasks }}</p>
            </article>
        </section>
    </main>
@endsection
