<?php

use App\Http\Controllers\AdminTimeLogController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/timelogs', [TimeLogController::class, 'index'])->name('timelogs.index');
    Route::get('/dashboard', [TimeLogController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->middleware('can:access-admin')->name('employees.index');
});

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except('show');

    Route::resource('users.time-logs', AdminTimeLogController::class)
        ->only(['index', 'edit', 'update', 'destroy'])
        ->parameters(['time-logs' => 'timeLog'])
        ->scoped();

    Route::resource('countries', CountryController::class)->except('show');
});

require __DIR__.'/auth.php';
