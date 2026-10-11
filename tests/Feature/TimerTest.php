<?php

use App\Livewire\EmployeeTimer;
use App\Livewire\StartStopTimer;
use App\Models\TimeLog;
use App\Models\User;
use Livewire\Livewire;

test('starting the timer creates an open log and stopping closes it', function () {
    $user = User::factory()->create();

    $timer = Livewire::actingAs($user)->test(StartStopTimer::class)
        ->call('toggleTimer')
        ->assertSet('isRunning', true);

    expect($user->timeLogs()->whereNull('end_time')->count())->toBe(1);

    $timer->call('toggleTimer')->assertSet('isRunning', false);

    expect($user->timeLogs()->count())->toBe(1)
        ->and($user->timeLogs()->whereNull('end_time')->count())->toBe(0);
});

test('starting the timer creates a log dated today', function () {
    $this->freezeTime();
    $user = User::factory()->create();

    Livewire::actingAs($user)->test(StartStopTimer::class)->call('toggleTimer');

    expect($user->timeLogs()->sole()->date)->toBe(now()->toDateString());
});

test('starting the timer from two tabs creates only one open log', function () {
    $user = User::factory()->create();

    $firstTab = Livewire::actingAs($user)->test(StartStopTimer::class);
    $secondTab = Livewire::actingAs($user)->test(StartStopTimer::class);

    $firstTab->call('toggleTimer')->assertSet('isRunning', true);
    $secondTab->call('toggleTimer')->assertSet('isRunning', true);

    expect($user->timeLogs()->count())->toBe(1)
        ->and($user->hasActiveTimeLog())->toBeTrue();
});

test('stopping from a stale tab closes the open log', function () {
    $user = User::factory()->create();

    $staleTab = Livewire::actingAs($user)->test(StartStopTimer::class)
        ->assertSet('isRunning', false);

    $runningLog = TimeLog::factory()->running()->for($user)->create();

    // The stale tab still shows "Start". Clicking it picks up the running log instead of opening a second one.
    $staleTab->call('toggleTimer')->assertSet('isRunning', true);
    expect($user->timeLogs()->count())->toBe(1);

    $staleTab->call('toggleTimer')->assertSet('isRunning', false);
    expect($runningLog->fresh()->end_time)->not->toBeNull();
});

test('stopping after another tab already stopped does not start a new log', function () {
    $user = User::factory()->create();

    $firstTab = Livewire::actingAs($user)->test(StartStopTimer::class)->call('toggleTimer');
    $secondTab = Livewire::actingAs($user)->test(StartStopTimer::class)->assertSet('isRunning', true);

    $firstTab->call('toggleTimer')->assertSet('isRunning', false);
    $secondTab->call('toggleTimer')->assertSet('isRunning', false);

    expect($user->timeLogs()->count())->toBe(1)
        ->and($user->hasActiveTimeLog())->toBeFalse();
});

test('stopping closes every open log left by the old double-start bug', function () {
    $user = User::factory()->create();
    TimeLog::factory()->running()->count(2)->for($user)->create();

    Livewire::actingAs($user)->test(StartStopTimer::class)
        ->assertSet('isRunning', true)
        ->call('toggleTimer')
        ->assertSet('isRunning', false);

    expect($user->hasActiveTimeLog())->toBeFalse();
});

test('the admin timer does not open a second log for an employee who is already running', function () {
    $admin = User::factory()->admin()->create();
    $employee = User::factory()->create();

    $adminTab = Livewire::actingAs($admin)
        ->test(EmployeeTimer::class, ['employee' => $employee])
        ->assertSet('isRunning', false);

    // The employee starts their own timer while the admin's page still shows "Start".
    $employee->startTimer();

    $adminTab->call('toggleTimer')->assertSet('isRunning', true);

    expect($employee->timeLogs()->count())->toBe(1);

    $adminTab->call('toggleTimer')->assertSet('isRunning', false);

    expect($employee->hasActiveTimeLog())->toBeFalse();
});
