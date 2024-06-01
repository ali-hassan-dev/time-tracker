<?php

use App\Http\Controllers\AdminTimeLogController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/timelogs', [TimeLogController::class, 'index'])->name('timelogs.index');
    Route::get('/dashboard', [TimeLogController::class, 'index'])->name('dashboard');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
});

Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('users/{user}/time-logs', [AdminTimeLogController::class, 'index'])->name('admin.users.time-logs.index');
    Route::get('users/{user}/time-logs/{timeLog}/edit', [AdminTimeLogController::class, 'edit'])->name('admin.users.time-logs.edit');
    Route::put('users/{user}/time-logs/{timeLog}', [AdminTimeLogController::class, 'update'])->name('admin.users.time-logs.update');
    Route::delete('users/{user}/time-logs/{timeLog}', [AdminTimeLogController::class, 'destroy'])->name('admin.users.time-logs.destroy');

    Route::get('countries', [CountryController::class, 'index'])->name('admin.countries.index');
    Route::get('countries/create', [CountryController::class, 'create'])->name('admin.countries.create');
    Route::post('countries', [CountryController::class, 'store'])->name('admin.countries.store');
    Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('admin.countries.edit');
    Route::put('countries/{country}', [CountryController::class, 'update'])->name('admin.countries.update');
    Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('admin.countries.destroy');
});

require __DIR__ . '/auth.php';