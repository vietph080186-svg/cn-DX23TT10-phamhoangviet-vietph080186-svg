@extends('layouts.app')

@section('title', 'Bảng Kanban công việc')

@section('content')
    @php
        $roleName = strtolower(Auth::user()->role?->name ?? '');
    @endphp

    <main class="container">
        <h1 class="page-title">Bảng Kanban công việc</h1>

        @include('admin.shared.messages')

        <form class="panel" method="GET" action="{{ route('kanban.index') }}" style="margin-bottom: 16px;">
            <div class="form-grid">
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
                    <label for="priority">Ưu tiên</label>
                    <select id="priority" name="priority">
                        <option value="">Tất cả</option>
                        @foreach ($priorities as $value => $label)
                            <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
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
            </div>

            <button class="button" type="submit">Lọc</button>
            <a class="button light" href="{{ route('kanban.index') }}">Xóa lọc</a>
        </form>

        <section class="kanban-board">
            @foreach ($statuses as $status => $label)
                <div class="kanban-column">
                    <h2 class="kanban-title">{{ $label }} ({{ $groupedTasks[$status]->count() }})</h2>

                    @forelse ($groupedTasks[$status] as $task)
                        @php
                            $isOverdue = $status === 'overdue';
                            $detailRoute = $roleName === 'staff' ? 'my-tasks.show' : 'tasks.show';
                            $buttons = [];

                            if ($roleName === 'staff' && $task->assignee_id === Auth::id()) {
                                $buttons = match ($task->status) {
                                    'new' => ['in_progress' => 'Bắt đầu làm'],
                                    'in_progress' => ['review' => 'Gửi duyệt'],
                                    'revision' => ['in_progress' => 'Sửa lại'],
                                    default => [],
                                };
                            }

                            if (in_array($roleName, ['admin', 'manager'], true)) {
                                $buttons = match ($task->status) {
                                    'new' => ['in_progress' => 'Bắt đầu'],
                                    'review' => ['completed' => 'Duyệt hoàn thành', 'revision' => 'Yêu cầu sửa'],
                                    'in_progress' => ['revision' => 'Yêu cầu sửa'],
                                    default => [],
                                };
                            }
                        @endphp

                        <article class="kanban-card {{ $isOverdue ? 'overdue' : '' }}">
                            <h3 style="margin:0 0 8px;">{{ $task->title }}</h3>
                            <p class="muted">Dự án: {{ $task->project?->name ?? 'Chưa có' }}</p>
                            <p class="muted">Người được giao: {{ $task->assignee?->full_name ?? 'Chưa có' }}</p>
                            <p class="muted">Ưu tiên: {{ $priorities[$task->priority] ?? $task->priority }}</p>
                            <p class="muted">Hạn: {{ $task->due_date?->format('d/m/Y') ?? 'Chưa có' }}</p>
                            <p class="muted">Trạng thái: {{ $isOverdue ? 'Quá hạn' : ($statuses[$task->status] ?? $task->status) }}</p>

                            <div class="actions" style="margin-top: 10px;">
                                <a class="button light" href="{{ route($detailRoute, $task) }}">Chi tiết</a>

                                @foreach ($buttons as $nextStatus => $buttonLabel)
                                    <form method="POST" action="{{ route('tasks.status', $task) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                                        <input type="hidden" name="note" value="Cập nhật từ bảng Kanban.">
                                        <button class="button" type="submit">{{ $buttonLabel }}</button>
                                    </form>
                                @endforeach
                            </div>
                        </article>
                    @empty
                        <p class="muted">Không có công việc.</p>
                    @endforelse
                </div>
            @endforeach
        </section>
    </main>
@endsection
