@extends('layouts.app')

@section('title', 'Thêm công việc')

@section('content')
    <main class="container">
        <h1 class="page-title">Thêm công việc</h1>
        <form class="panel" method="POST" action="{{ route('tasks.store') }}">
            @include('tasks.form')
        </form>
    </main>
@endsection
