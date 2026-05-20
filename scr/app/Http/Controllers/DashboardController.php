<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = $this->roleName();

        return match ($role) {
            'admin' => redirect()->route('dashboard.admin'),
            'manager' => redirect()->route('dashboard.manager'),
            default => redirect()->route('dashboard.staff'),
        };
    }

    public function admin()
    {
        if ($this->roleName() !== 'admin') {
            return redirect()->route('dashboard');
        }

        return view('dashboards.admin', [
            'totalUsers' => User::count(),
            'totalDepartments' => Department::count(),
            'totalProjects' => Project::count(),
            'totalTasks' => Task::count(),
        ]);
    }

    public function manager()
    {
        if ($this->roleName() !== 'manager') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        return view('dashboards.manager', [
            'createdTasks' => Task::where('creator_id', $user->id)->count(),
            'assignedTasks' => Task::where('assignee_id', $user->id)->count(),
            'reviewTasks' => Task::where('creator_id', $user->id)->where('status', 'review')->count(),
            'completedTasks' => Task::where('creator_id', $user->id)->where('status', 'completed')->count(),
        ]);
    }

    public function staff()
    {
        if ($this->roleName() !== 'staff') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        return view('dashboards.staff', [
            'assignedTasks' => Task::where('assignee_id', $user->id)->count(),
            'newTasks' => Task::where('assignee_id', $user->id)->where('status', 'new')->count(),
            'inProgressTasks' => Task::where('assignee_id', $user->id)->where('status', 'in_progress')->count(),
            'completedTasks' => Task::where('assignee_id', $user->id)->where('status', 'completed')->count(),
        ]);
    }

    private function roleName(): string
    {
        return strtolower(Auth::user()->role?->name ?? 'staff');
    }
}
