<?php

use App\Http\Controllers\AdminTimeLogController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
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
    Route::post('/timelogs/start', [TimeLogController::class, 'start'])->name('timelogs.start');
    Route::post('/timelogs/{id}/stop', [TimeLogController::class, 'stop'])->name('timelogs.stop');
});

Route::get('/employees', [EmployeeController::class, 'index'])->middleware('auth')->name('employees.index');

Route::middleware(['auth', 'can:access-admin'])->group(function () {
    Route::get('/admin/users/{user}/time-logs', [AdminTimeLogController::class, 'index'])->name('admin.users.time-logs.index');
    Route::get('/admin/users/{user}/time-logs/{timeLog}/edit', [AdminTimeLogController::class, 'edit'])->name('admin.users.time-logs.edit');
    Route::put('/admin/users/{user}/time-logs/{timeLog}', [AdminTimeLogController::class, 'update'])->name('admin.users.time-logs.update');
});

require __DIR__ . '/auth.php';
