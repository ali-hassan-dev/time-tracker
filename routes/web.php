<?php

use App\Http\Controllers\AdminTimeLogController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/timelogs', [TimeLogController::class, 'index'])->name('timelogs.index');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
});


Route::middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/users/{user}/time-logs', [AdminTimeLogController::class, 'index'])->name('admin.users.time-logs.index');
    Route::get('/admin/users/{user}/time-logs/{timeLog}/edit', [AdminTimeLogController::class, 'edit'])->name('admin.users.time-logs.edit');
    Route::put('/admin/users/{user}/time-logs/{timeLog}', [AdminTimeLogController::class, 'update'])->name('admin.users.time-logs.update');
    Route::delete('/admin/users/{user}/time-logs/{timeLog}', [AdminTimeLogController::class, 'destroy'])->name('admin.users.time-logs.destroy');
});

require __DIR__ . '/auth.php';
