@csrf

<div class="form-grid">
    <div class="form-group">
        <label for="title">Tiêu đề</label>
        <input id="title" type="text" name="title" value="{{ old('title', $task->title ?? '') }}" required>
        @error('title') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="project_id">Dự án</label>
        <select id="project_id" name="project_id" required>
            <option value="">Chọn dự án</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((string) old('project_id', $task->project_id ?? '') === (string) $project->id)>{{ $project->name }}</option>
            @endforeach
        </select>
        @error('project_id') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="task_category_id">Danh mục</label>
        <select id="task_category_id" name="task_category_id">
            <option value="">Chưa phân loại</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('task_category_id', $task->task_category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('task_category_id') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="assignee_id">Người được giao</label>
        <select id="assignee_id" name="assignee_id" required>
            <option value="">Chọn nhân viên</option>
            @foreach ($staffUsers as $staff)
                <option value="{{ $staff->id }}" @selected((string) old('assignee_id', $task->assignee_id ?? '') === (string) $staff->id)>{{ $staff->full_name }}</option>
            @endforeach
        </select>
        @error('assignee_id') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="priority">Mức ưu tiên</label>
        <select id="priority" name="priority" required>
            @foreach ($priorities as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $task->priority ?? 'medium') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('priority') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="status">Trạng thái</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $task->status ?? 'new') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="start_date">Ngày bắt đầu</label>
        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', isset($task) && $task->start_date ? $task->start_date->format('Y-m-d') : '') }}">
        @error('start_date') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="due_date">Hạn hoàn thành</label>
        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" required>
        @error('due_date') <p class="error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="form-group">
    <label for="description">Mô tả</label>
    <textarea id="description" name="description">{{ old('description', $task->description ?? '') }}</textarea>
    @error('description') <p class="error">{{ $message }}</p> @enderror
</div>

<div class="form-grid">
    <div class="form-group">
        <label for="result_note">Ghi chú kết quả</label>
        <textarea id="result_note" name="result_note">{{ old('result_note', $task->result_note ?? '') }}</textarea>
        @error('result_note') <p class="error">{{ $message }}</p> @enderror
    </div>

    <div class="form-group">
        <label for="result_link">Liên kết kết quả</label>
        <input id="result_link" type="url" name="result_link" value="{{ old('result_link', $task->result_link ?? '') }}">
        @error('result_link') <p class="error">{{ $message }}</p> @enderror
    </div>
</div>

<button class="button" type="submit">Lưu</button>
<a class="button light" href="{{ route('tasks.index') }}">Quay lại</a>
