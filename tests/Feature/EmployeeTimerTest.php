<?php

use App\Livewire\EmployeeTimer;
use App\Models\TimeLog;
use App\Models\User;
use Livewire\Livewire;

test('employees cannot load the admin timer', function () {
    $employee = User::factory()->create();
    $colleague = User::factory()->create();

    Livewire::actingAs($employee)
        ->test(EmployeeTimer::class, ['employeeId' => $colleague->id])
        ->assertForbidden();
});

test('employees cannot toggle the admin timer', function () {
    $admin = User::factory()->admin()->create();
    $employee = User::factory()->create();
    $colleague = User::factory()->create();

    $component = Livewire::actingAs($admin)
        ->test(EmployeeTimer::class, ['employeeId' => $colleague->id]);

    $this->actingAs($employee);

    $component->call('toggleTimer')->assertForbidden();

    expect(TimeLog::count())->toBe(0);
});

test('admins can start and stop an employee\'s timer', function () {
    $admin = User::factory()->admin()->create();
    $employee = User::factory()->create();

    $component = Livewire::actingAs($admin)
        ->test(EmployeeTimer::class, ['employeeId' => $employee->id])
        ->call('toggleTimer')
        ->assertSet('isRunning', true);

    expect($employee->hasActiveTimeLog())->toBeTrue();

    $component->call('toggleTimer')->assertSet('isRunning', false);

    expect($employee->hasActiveTimeLog())->toBeFalse()
        ->and($employee->timeLogs()->count())->toBe(1);
});
