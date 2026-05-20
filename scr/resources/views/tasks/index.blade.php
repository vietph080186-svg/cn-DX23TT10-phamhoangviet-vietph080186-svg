@extends('layouts.app')

@section('title', 'Quản lý công việc')

@section('content')
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">Quản lý công việc</h1>
            <a class="button" href="{{ route('tasks.create') }}">Thêm công việc</a>
        </div>

        @include('admin.shared.messages')

        <form class="panel" method="GET" action="{{ route('tasks.index') }}">
            <div class="form-grid">
                <div class="form-group">
                    <label for="search">Tìm theo tiêu đề</label>
                    <input id="search" type="text" name="search" value="{{ request('search') }}">
                </div>
                <div class="form-group">
                    <label for="project_id">Dự án</label>
                    <select id="project_id" name="project_id">
                        <option value="">Tất cả</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected((string) request('project_id') === (string) $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assignee_id">Người được giao</label>
                    <select id="assignee_id" name="assignee_id">
                        <option value="">Tất cả</option>
                        @foreach ($staffUsers as $staff)
                            <option value="{{ $staff->id }}" @selected((string) request('assignee_id') === (string) $staff->id)>{{ $staff->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Trạng thái</label>
                    <select id="status" name="status">
                        <option value="">Tất cả</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="priority">Ưu tiên</label>
                    <select id="priority" name="priority">
                        <option value="">Tất cả</option>
                        @foreach ($priorities as $value => $label)
                            <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="button" type="submit">Lọc</button>
            <a class="button light" href="{{ route('tasks.index') }}">Xóa lọc</a>
        </form>

        @include('tasks.table', ['detailRoute' => 'tasks.show'])
    </main>
@endsection
