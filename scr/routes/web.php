<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('dashboard.manager');
    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])->name('dashboard.staff');

    Route::middleware('role:Admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
    });

    Route::middleware('role:Admin,Manager')->group(function () {
        Route::resource('projects', ProjectController::class);
        Route::resource('task-categories', TaskCategoryController::class);
        Route::resource('tasks', TaskController::class);
    });

    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my-tasks.index');
    Route::get('/my-tasks/{task}', [TaskController::class, 'show'])->name('my-tasks.show');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
    Route::post('/tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
});
