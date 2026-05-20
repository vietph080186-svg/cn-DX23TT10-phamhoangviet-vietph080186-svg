<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskComment;
use App\Models\TaskStatusLog;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    private array $statuses = [
        'new' => 'Mới giao',
        'in_progress' => 'Đang làm',
        'review' => 'Chờ duyệt',
        'completed' => 'Hoàn thành',
        'overdue' => 'Quá hạn',
        'revision' => 'Cần sửa lại',
    ];

    private array $priorities = [
        'low' => 'Thấp',
        'medium' => 'Trung bình',
        'high' => 'Cao',
        'urgent' => 'Khẩn cấp',
    ];

    public function index(Request $request)
    {
        abort_if($this->isStaff(), 403, 'Bạn không có quyền truy cập trang này.');

        $tasks = Task::with(['project', 'category', 'assignee'])
            ->when($this->isManager(), function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('creator_id', Auth::id())
                        ->orWhere('assignee_id', Auth::id());
                });
            })
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->project_id, fn ($query, $projectId) => $query->where('project_id', $projectId))
            ->when($request->assignee_id, fn ($query, $assigneeId) => $query->where('assignee_id', $assigneeId))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->priority, fn ($query, $priority) => $query->where('priority', $priority))
            ->orderByRaw('due_date IS NULL, due_date ASC')
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', $this->formData([
            'tasks' => $tasks,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
        ]));
    }

    public function create()
    {
        abort_if($this->isStaff(), 403, 'Bạn không có quyền truy cập trang này.');

        return view('tasks.create', $this->formData([
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
        ]));
    }

    public function store(Request $request)
    {
        abort_if($this->isStaff(), 403, 'Bạn không có quyền truy cập trang này.');

        $data = $this->validatedData($request);
        $data['creator_id'] = Auth::id();

        $task = Task::create($data);
        $this->createNotification($task->assignee_id, $task, 'Công việc mới được giao', 'Bạn vừa được giao một công việc mới.', 'task_assigned');

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Đã tạo công việc thành công.');
    }

    public function show(Task $task)
    {
        $this->authorizeView($task);

        $task->load(['project', 'category', 'assignee', 'creator', 'comments.user', 'statusLogs.user', 'statusLogs.changer']);

        return view('tasks.show', [
            'task' => $task,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
            'canEdit' => $this->canEdit($task),
            'canDelete' => $this->canDelete($task),
            'allowedStatuses' => $this->allowedNextStatuses($task),
        ]);
    }

    public function edit(Task $task)
    {
        abort_unless($this->canEdit($task), 403, 'Bạn không có quyền sửa công việc này.');

        return view('tasks.edit', $this->formData([
            'task' => $task,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
        ]));
    }

    public function update(Request $request, Task $task)
    {
        abort_unless($this->canEdit($task), 403, 'Bạn không có quyền sửa công việc này.');

        $oldStatus = $task->status;
        $data = $this->validatedData($request);

        if ($data['status'] === 'completed' && $oldStatus !== 'completed') {
            $data['completed_at'] = now();
        }

        if ($data['status'] !== 'completed') {
            $data['completed_at'] = null;
        }

        $task->update($data);

        if ($oldStatus !== $task->status) {
            $this->logStatus($task, $oldStatus, $task->status, 'Cập nhật trong form công việc.');
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Đã cập nhật công việc thành công.');
    }

    public function destroy(Task $task)
    {
        abort_unless($this->canDelete($task), 403, 'Bạn không có quyền xóa công việc này.');

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Đã xóa công việc thành công.');
    }

    public function myTasks()
    {
        $tasks = Task::with(['project', 'category'])
            ->where('assignee_id', Auth::id())
            ->orderByRaw('due_date IS NULL, due_date ASC')
            ->paginate(10);

        return view('tasks.my-index', [
            'tasks' => $tasks,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
        ]);
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeView($task);

        $allowedStatuses = array_keys($this->allowedNextStatuses($task));

        $data = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
            'result_note' => ['nullable'],
            'result_link' => ['nullable', 'url', 'max:255'],
            'note' => ['nullable', 'max:255'],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'result_link.url' => 'Liên kết kết quả không đúng định dạng.',
        ]);

        $oldStatus = $task->status;
        $task->result_note = $data['result_note'] ?? $task->result_note;
        $task->result_link = $data['result_link'] ?? $task->result_link;
        $task->status = $data['status'];
        $task->completed_at = $data['status'] === 'completed' ? now() : null;
        $task->save();

        $this->logStatus($task, $oldStatus, $task->status, $data['note'] ?? null);
        $this->notifyByStatus($task);

        return back()
            ->with('success', 'Đã cập nhật trạng thái công việc.');
    }

    public function storeComment(Request $request, Task $task)
    {
        $this->authorizeView($task);

        $data = $request->validate([
            'content' => ['required', 'max:1000'],
        ], [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
        ]);

        TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $data['content'],
        ]);

        collect([$task->creator_id, $task->assignee_id])
            ->filter(fn ($userId) => $userId && $userId !== Auth::id())
            ->unique()
            ->each(function ($userId) use ($task) {
                $this->createNotification($userId, $task, 'Bình luận mới', 'Có bình luận mới trong công việc: '.$task->title, 'task_commented');
            });

        return back()->with('success', 'Đã thêm bình luận.');
    }

    private function validatedData(Request $request): array
    {
        $staffRoleId = Role::where('name', 'Staff')->value('id');

        return $request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['nullable'],
            'project_id' => ['required', 'exists:projects,id'],
            'task_category_id' => ['nullable', 'exists:task_categories,id'],
            'assignee_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role_id', $staffRoleId)],
            'priority' => ['required', Rule::in(array_keys($this->priorities))],
            'status' => ['required', Rule::in(array_keys($this->statuses))],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:start_date'],
            'result_note' => ['nullable'],
            'result_link' => ['nullable', 'url', 'max:255'],
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề công việc.',
            'project_id.required' => 'Vui lòng chọn dự án.',
            'assignee_id.required' => 'Vui lòng chọn người được giao.',
            'assignee_id.exists' => 'Người được giao phải là nhân viên.',
            'priority.required' => 'Vui lòng chọn mức ưu tiên.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'due_date.required' => 'Vui lòng chọn hạn hoàn thành.',
            'due_date.after_or_equal' => 'Hạn hoàn thành phải sau hoặc bằng ngày bắt đầu.',
            'result_link.url' => 'Liên kết kết quả không đúng định dạng.',
        ]);
    }

    private function formData(array $extra = []): array
    {
        $staffRoleId = Role::where('name', 'Staff')->value('id');

        return array_merge([
            'projects' => Project::orderBy('name')->get(),
            'categories' => TaskCategory::orderBy('name')->get(),
            'staffUsers' => User::where('role_id', $staffRoleId)->orderBy('full_name')->get(),
        ], $extra);
    }

    private function authorizeView(Task $task): void
    {
        abort_unless($this->canView($task), 403, 'Bạn không có quyền xem công việc này.');
    }

    private function canView(Task $task): bool
    {
        return $this->isAdmin()
            || ($this->isManager() && ($task->creator_id === Auth::id() || $task->assignee_id === Auth::id()))
            || ($this->isStaff() && $task->assignee_id === Auth::id());
    }

    private function canEdit(Task $task): bool
    {
        return $this->isAdmin()
            || ($this->isManager() && ($task->creator_id === Auth::id() || $task->assignee_id === Auth::id()));
    }

    private function canDelete(Task $task): bool
    {
        return $this->isAdmin()
            || ($this->isManager() && $task->creator_id === Auth::id());
    }

    private function allowedNextStatuses(Task $task): array
    {
        if ($this->isStaff() && $task->assignee_id === Auth::id()) {
            return match ($task->status) {
                'new' => ['in_progress' => $this->statuses['in_progress']],
                'in_progress' => ['review' => $this->statuses['review']],
                'revision' => ['in_progress' => $this->statuses['in_progress']],
                default => [],
            };
        }

        if ($this->isAdmin() || $this->isManager()) {
            return match ($task->status) {
                'new' => ['in_progress' => $this->statuses['in_progress']],
                'review' => ['completed' => $this->statuses['completed'], 'revision' => $this->statuses['revision']],
                'in_progress' => ['revision' => $this->statuses['revision']],
                default => [],
            };
        }

        return [];
    }

    private function logStatus(Task $task, string $oldStatus, string $newStatus, ?string $note = null): void
    {
        TaskStatusLog::create([
            'task_id' => $task->id,
            'changed_by' => Auth::id(),
            'user_id' => Auth::id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $note,
        ]);
    }

    private function notifyByStatus(Task $task): void
    {
        if ($task->status === 'review') {
            $this->createNotification($task->creator_id, $task, 'Công việc chờ duyệt', 'Nhân viên đã gửi công việc để duyệt.', 'task_submitted');
        }

        if ($task->status === 'completed') {
            $this->createNotification($task->assignee_id, $task, 'Công việc đã hoàn thành', 'Công việc của bạn đã được duyệt hoàn thành.', 'task_completed');
        }

        if ($task->status === 'revision') {
            $this->createNotification($task->assignee_id, $task, 'Công việc cần sửa lại', 'Công việc cần được chỉnh sửa và gửi lại.', 'task_revision');
        }
    }

    private function createNotification(?int $userId, Task $task, string $title, string $message, string $type): void
    {
        if ($userId === Auth::id()) {
            return;
        }

        $link = $userId === $task->assignee_id
            ? route('my-tasks.show', $task, false)
            : route('tasks.show', $task, false);

        $this->notifications->createForUser($userId, $title, $message, $type, $link, $task->id);
    }

    private function roleName(): string
    {
        return strtolower(Auth::user()->role?->name ?? '');
    }

    private function isAdmin(): bool
    {
        return $this->roleName() === 'admin';
    }

    private function isManager(): bool
    {
        return $this->roleName() === 'manager';
    }

    private function isStaff(): bool
    {
        return $this->roleName() === 'staff';
    }
}
