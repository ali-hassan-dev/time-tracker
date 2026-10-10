<?php

use App\Models\TimeLog;
use App\Models\User;

beforeEach(function () {
    $this->employee = User::factory()->create(['name' => 'Erin Employee']);
    $this->colleague = User::factory()->create(['name' => 'Cole Colleague']);

    TimeLog::factory()->for($this->employee)->create([
        'client' => 'Own Client',
        'country' => 'Norway',
        'payout' => 111.11,
    ]);
    TimeLog::factory()->for($this->colleague)->create([
        'client' => 'Colleague Client',
        'country' => 'Peru',
        'payout' => 222.22,
    ]);
});

test('an employee sees only their own logs', function (string $page) {
    $this->actingAs($this->employee)
        ->get($page)
        ->assertOk()
        ->assertSee('Erin Employee')
        ->assertDontSee('Cole Colleague');
})->with(['/timelogs', '/dashboard']);

test('an admin sees every employee\'s logs', function (string $page) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get($page)
        ->assertOk()
        ->assertSee('Erin Employee')
        ->assertSee('Cole Colleague');
})->with(['/timelogs', '/dashboard']);

test('an employee cannot find other logs through the filters', function () {
    $this->actingAs($this->employee)
        ->get('/timelogs?client=Colleague')
        ->assertOk()
        ->assertDontSee('Cole Colleague');
});

test('the country filter only lists countries from the employee\'s own logs', function () {
    $this->actingAs($this->employee)
        ->get('/timelogs')
        ->assertSee('Norway')
        ->assertDontSee('Peru');
});

test('the payout column is hidden from employees', function () {
    $this->actingAs($this->employee)
        ->get('/timelogs')
        ->assertDontSee('Payout')
        ->assertDontSee('111.11');
});

test('admins see the payout column', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/timelogs')
        ->assertSee('Payout')
        ->assertSee('111.11')
        ->assertSee('222.22');
});
