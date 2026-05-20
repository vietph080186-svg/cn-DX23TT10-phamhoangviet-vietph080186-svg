@extends('layouts.app')

@section('title', 'Sửa công việc')

@section('content')
    <main class="container">
        <h1 class="page-title">Sửa công việc</h1>
        <form class="panel" method="POST" action="{{ route('tasks.update', $task) }}">
            @method('PUT')
            @include('tasks.form')
        </form>
    </main>
@endsection
