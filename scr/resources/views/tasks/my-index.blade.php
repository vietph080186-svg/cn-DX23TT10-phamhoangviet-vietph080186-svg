@extends('layouts.app')

@section('title', 'Công việc của tôi')

@section('content')
    <main class="container">
        <h1 class="page-title">Công việc của tôi</h1>
        @include('admin.shared.messages')
        @include('tasks.table', ['detailRoute' => 'my-tasks.show'])
    </main>
@endsection
