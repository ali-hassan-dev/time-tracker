<?php

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function countQueriesOnEmployeesPage(User $admin): int
{
    DB::flushQueryLog();
    DB::enableQueryLog();

    test()->actingAs($admin)->get('/employees')->assertOk();

    return count(DB::getQueryLog());
}

test('the employees page runs the same number of queries for any number of employees', function () {
    $admin = User::factory()->admin()->create();

    User::factory()->count(2)->has(TimeLog::factory()->count(3))->create();
    $fewEmployees = countQueriesOnEmployeesPage($admin);

    User::factory()->count(5)->has(TimeLog::factory()->count(3))->create();
    $manyEmployees = countQueriesOnEmployeesPage($admin);

    expect($manyEmployees)->toBe($fewEmployees);
});

test('the employees page shows each employee\'s total and timer state', function () {
    $admin = User::factory()->admin()->create();
    $idle = User::factory()->create(['username' => 'idle.employee']);
    $working = User::factory()->create(['username' => 'working.employee']);

    TimeLog::factory()->for($idle)->create([
        'start_time' => now()->subHours(2),
        'end_time' => now()->subHour(),
    ]);
    TimeLog::factory()->running()->for($working)->create();

    $this->actingAs($admin)
        ->get('/employees')
        ->assertOk()
        ->assertSeeInOrder(['idle.employee', '1 hour', 'Start Timer', 'working.employee', 'Stop Timer']);
});
