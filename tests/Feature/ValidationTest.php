<?php

use App\Enums\Role;
use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('creating a user validates every field', function () {
    User::factory()->create(['username' => 'taken', 'email' => 'taken@example.com']);

    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => '',
            'username' => 'taken',
            'email' => 'taken@example.com',
            'password' => 'short',
            'password_confirmation' => 'different',
            'role' => 'owner',
        ])
        ->assertSessionHasErrors(['name', 'username', 'email', 'password', 'role']);
});

test('admins can create a user', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.users.store'), [
            'name' => 'New Employee',
            'username' => 'new.employee',
            'email' => 'new.employee@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'employee',
        ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasNoErrors();

    expect(User::where('username', 'new.employee')->first()->role)->toBe(Role::Employee);
});

test('updating a user keeps their own username and email valid', function () {
    $employee = User::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $employee), [
            'name' => 'Renamed',
            'username' => $employee->username,
            'email' => $employee->email,
            'role' => 'employee',
        ])
        ->assertSessionHasNoErrors();

    expect($employee->fresh()->name)->toBe('Renamed');
});

test('updating a user rejects another user\'s username and email', function () {
    $employee = User::factory()->create();
    $other = User::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.users.update', $employee), [
            'name' => 'Renamed',
            'username' => $other->username,
            'email' => $other->email,
            'password' => 'short',
            'role' => 'employee',
        ])
        ->assertSessionHasErrors(['username', 'email', 'password']);
});

test('a time log update validates its fields and the country list', function () {
    $timeLog = TimeLog::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.users.time-logs.update', [$timeLog->user, $timeLog]), [
            'login_time' => 'not a date',
            'talk_type' => 'Video',
            'talk_time' => 'ten',
            'payout' => 'lots',
            'country' => 'Atlantis',
            'payment_method' => 'Cash',
        ])
        ->assertSessionHasErrors(['login_time', 'talk_type', 'talk_time', 'payout', 'country', 'payment_method']);
});

test('countries need a unique name and a unique 3-letter code', function () {
    Country::create(['name' => 'Norway', 'code' => 'NOR']);

    $this->actingAs($this->admin)
        ->post(route('admin.countries.store'), ['name' => 'Norway', 'code' => 'NO'])
        ->assertSessionHasErrors(['name', 'code']);

    $this->actingAs($this->admin)
        ->post(route('admin.countries.store'), ['name' => 'Sweden', 'code' => 'NOR'])
        ->assertSessionHasErrors(['code']);

    $this->actingAs($this->admin)
        ->post(route('admin.countries.store'), ['name' => 'Sweden', 'code' => 'SWE'])
        ->assertRedirect(route('admin.countries.index'))
        ->assertSessionHasNoErrors();
});

test('a country can be saved with its own name and code', function () {
    $country = Country::create(['name' => 'Norway', 'code' => 'NOR']);
    Country::create(['name' => 'Sweden', 'code' => 'SWE']);

    $this->actingAs($this->admin)
        ->put(route('admin.countries.update', $country), ['name' => 'Norway', 'code' => 'NOR'])
        ->assertSessionHasNoErrors();

    $this->actingAs($this->admin)
        ->put(route('admin.countries.update', $country), ['name' => 'Sweden', 'code' => 'SWE'])
        ->assertSessionHasErrors(['name', 'code']);
});
