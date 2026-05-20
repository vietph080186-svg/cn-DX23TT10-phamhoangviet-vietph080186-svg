<table style="margin-top: 16px;">
    <thead>
        <tr>
            <th>Tiêu đề</th>
            <th>Dự án</th>
            <th>Danh mục</th>
            <th>Người được giao</th>
            <th>Ưu tiên</th>
            <th>Trạng thái</th>
            <th>Hạn hoàn thành</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tasks as $task)
            @php
                $isOverdue = $task->due_date && $task->due_date->isPast() && ! in_array($task->status, ['completed'], true);
            @endphp
            <tr style="{{ $isOverdue ? 'background:#fff1f2;' : '' }}">
                <td>{{ $task->title }}</td>
                <td>{{ $task->project?->name }}</td>
                <td>{{ $task->category?->name ?? 'Chưa phân loại' }}</td>
                <td>{{ $task->assignee?->full_name }}</td>
                <td>{{ $priorities[$task->priority] ?? $task->priority }}</td>
                <td>{{ $isOverdue ? 'Quá hạn' : ($statuses[$task->status] ?? $task->status) }}</td>
                <td>{{ $task->due_date?->format('d/m/Y') ?? 'Chưa có' }}</td>
                <td class="actions">
                    <a class="button light" href="{{ route($detailRoute, $task) }}">Xem</a>
                    @if (Route::has('tasks.edit') && in_array(strtolower(Auth::user()->role?->name ?? ''), ['admin', 'manager'], true))
                        <a class="button secondary" href="{{ route('tasks.edit', $task) }}">Sửa</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Chưa có công việc phù hợp.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination">{{ $tasks->links() }}</div>
