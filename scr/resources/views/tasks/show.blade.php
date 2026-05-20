@extends('layouts.app')

@section('title', 'Chi tiết công việc')

@section('content')
    <main class="container">
        <div class="page-header">
            <h1 class="page-title">Chi tiết công việc</h1>
            <div class="actions">
                @if ($canEdit)
                    <a class="button secondary" href="{{ route('tasks.edit', $task) }}">Sửa</a>
                @endif
                @if ($canDelete)
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Bạn có chắc muốn xóa công việc này?')">
                        @csrf
                        @method('DELETE')
                        <button class="button danger" type="submit">Xóa</button>
                    </form>
                @endif
            </div>
        </div>

        @include('admin.shared.messages')

        <section class="panel">
            <p><strong>Tiêu đề:</strong> {{ $task->title }}</p>
            <p><strong>Mô tả:</strong> {{ $task->description ?: 'Chưa có mô tả' }}</p>
            <p><strong>Dự án:</strong> {{ $task->project?->name }}</p>
            <p><strong>Danh mục:</strong> {{ $task->category?->name ?? 'Chưa phân loại' }}</p>
            <p><strong>Người tạo:</strong> {{ $task->creator?->full_name }}</p>
            <p><strong>Người được giao:</strong> {{ $task->assignee?->full_name }}</p>
            <p><strong>Mức ưu tiên:</strong> {{ $priorities[$task->priority] ?? $task->priority }}</p>
            <p><strong>Trạng thái:</strong> {{ $statuses[$task->status] ?? $task->status }}</p>
            <p><strong>Ngày bắt đầu:</strong> {{ $task->start_date?->format('d/m/Y') ?? 'Chưa có' }}</p>
            <p><strong>Hạn hoàn thành:</strong> {{ $task->due_date?->format('d/m/Y') ?? 'Chưa có' }}</p>
            <p><strong>Ngày hoàn thành:</strong> {{ $task->completed_at?->format('d/m/Y H:i') ?? 'Chưa hoàn thành' }}</p>
            <p><strong>Ghi chú kết quả:</strong> {{ $task->result_note ?: 'Chưa có' }}</p>
            <p><strong>Liên kết kết quả:</strong>
                @if ($task->result_link)
                    <a href="{{ $task->result_link }}" target="_blank">{{ $task->result_link }}</a>
                @else
                    Chưa có
                @endif
            </p>
        </section>

        @if (count($allowedStatuses) > 0)
            <section class="panel" style="margin-top: 16px;">
                <h2>Cập nhật trạng thái</h2>
                <form method="POST" action="{{ route('tasks.status', $task) }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="status">Trạng thái mới</label>
                            <select id="status" name="status" required>
                                @foreach ($allowedStatuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="result_link">Liên kết kết quả</label>
                            <input id="result_link" type="url" name="result_link" value="{{ old('result_link', $task->result_link) }}">
                            @error('result_link') <p class="error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="result_note">Ghi chú kết quả</label>
                        <textarea id="result_note" name="result_note">{{ old('result_note', $task->result_note) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="note">Ghi chú thay đổi trạng thái</label>
                        <textarea id="note" name="note">{{ old('note') }}</textarea>
                    </div>

                    <button class="button" type="submit">Cập nhật</button>
                </form>
            </section>
        @endif

        <section class="panel" style="margin-top: 16px;">
            <h2>Bình luận</h2>
            <form method="POST" action="{{ route('tasks.comments.store', $task) }}">
                @csrf
                <div class="form-group">
                    <label for="content">Nội dung</label>
                    <textarea id="content" name="content" required>{{ old('content') }}</textarea>
                    @error('content') <p class="error">{{ $message }}</p> @enderror
                </div>
                <button class="button" type="submit">Gửi bình luận</button>
            </form>

            <div style="margin-top: 16px;">
                @forelse ($task->comments as $comment)
                    <div style="border-top:1px solid #e5e7eb;padding:12px 0;">
                        <strong>{{ $comment->user?->full_name ?? 'Người dùng' }}</strong>
                        <span style="color:#64748b;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        <p>{{ $comment->content }}</p>
                    </div>
                @empty
                    <p>Chưa có bình luận.</p>
                @endforelse
            </div>
        </section>

        <section class="panel" style="margin-top: 16px;">
            <h2>Lịch sử trạng thái</h2>
            @forelse ($task->statusLogs as $log)
                <div style="border-top:1px solid #e5e7eb;padding:12px 0;">
                    <strong>{{ $log->user?->full_name ?? $log->changer?->full_name ?? 'Người dùng' }}</strong>
                    <span style="color:#64748b;">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                    <p>
                        {{ $statuses[$log->old_status] ?? $log->old_status ?? 'Chưa có' }}
                        ->
                        {{ $statuses[$log->new_status] ?? $log->new_status }}
                    </p>
                    @if ($log->note)
                        <p>{{ $log->note }}</p>
                    @endif
                </div>
            @empty
                <p>Chưa có lịch sử trạng thái.</p>
            @endforelse
        </section>
    </main>
@endsection
