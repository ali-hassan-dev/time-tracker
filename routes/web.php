<?php

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

require __DIR__ . '/auth.php';
