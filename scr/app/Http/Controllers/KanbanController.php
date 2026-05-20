<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    private array $statuses = [
        'new' => 'Mới giao',
        'in_progress' => 'Đang làm',
        'review' => 'Chờ duyệt',
        'revision' => 'Cần sửa lại',
        'completed' => 'Hoàn thành',
        'overdue' => 'Quá hạn',
    ];

    private array $priorities = [
        'low' => 'Thấp',
        'medium' => 'Trung bình',
        'high' => 'Cao',
        'urgent' => 'Khẩn cấp',
    ];

    public function index(Request $request)
    {
        $tasks = Task::with(['project', 'assignee'])
            ->when($this->isManager(), function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('creator_id', Auth::id())
                        ->orWhere('assignee_id', Auth::id());
                });
            })
            ->when($this->isStaff(), fn ($query) => $query->where('assignee_id', Auth::id()))
            ->when($request->project_id, fn ($query, $projectId) => $query->where('project_id', $projectId))
            ->when($request->assignee_id, fn ($query, $assigneeId) => $query->where('assignee_id', $assigneeId))
            ->when($request->priority, fn ($query, $priority) => $query->where('priority', $priority))
            ->when($request->status, function ($query, $status) {
                if ($status !== 'overdue') {
                    $query->where('status', $status);
                }
            })
            ->orderByRaw('due_date IS NULL, due_date ASC')
            ->get()
            ->filter(function ($task) use ($request) {
                if ($request->status !== 'overdue') {
                    return true;
                }

                return $this->displayStatus($task) === 'overdue';
            });

        $groupedTasks = collect(array_keys($this->statuses))
            ->mapWithKeys(fn ($status) => [$status => $tasks->filter(fn ($task) => $this->displayStatus($task) === $status)]);

        $staffRoleId = Role::where('name', 'Staff')->value('id');

        return view('kanban.index', [
            'groupedTasks' => $groupedTasks,
            'statuses' => $this->statuses,
            'priorities' => $this->priorities,
            'projects' => Project::orderBy('name')->get(),
            'staffUsers' => User::where('role_id', $staffRoleId)->orderBy('full_name')->get(),
        ]);
    }

    private function displayStatus(Task $task): string
    {
        if ($task->due_date && $task->due_date->isPast() && $task->status !== 'completed') {
            return 'overdue';
        }

        return $task->status;
    }

    private function isManager(): bool
    {
        return strtolower(Auth::user()->role?->name ?? '') === 'manager';
    }

    private function isStaff(): bool
    {
        return strtolower(Auth::user()->role?->name ?? '') === 'staff';
    }
}
