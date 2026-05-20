<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
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
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('dashboard.manager');
    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])->name('dashboard.staff');
});
