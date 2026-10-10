<?php

use App\Models\User;

test('employees get 403 on the employees page', function () {
    $employee = User::factory()->create();

    $this->actingAs($employee)
        ->get('/employees')
        ->assertForbidden();
});

test('admins can open the employees page', function () {
    $admin = User::factory()->admin()->create();
    $employee = User::factory()->create(['username' => 'erin.employee']);

    $this->actingAs($admin)
        ->get('/employees')
        ->assertOk()
        ->assertSee('erin.employee');
});

test('employees do not see links to the employees page', function () {
    $employee = User::factory()->create();

    $this->actingAs($employee)
        ->get('/timelogs')
        ->assertOk()
        ->assertDontSee('View All Employees')
        ->assertDontSee(route('employees.index'));
});

test('admins see links to the employees page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/timelogs')
        ->assertOk()
        ->assertSee('View All Employees')
        ->assertSee(route('employees.index'));
});
