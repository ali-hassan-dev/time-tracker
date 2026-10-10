<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('app:create-admin creates a verified admin', function () {
    $this->artisan('app:create-admin')
        ->expectsQuestion('Name', 'Ada Admin')
        ->expectsQuestion('Username', 'ada')
        ->expectsQuestion('Email', 'ada@example.com')
        ->expectsQuestion('Password', 'secret-password')
        ->expectsQuestion('Confirm password', 'secret-password')
        ->expectsOutputToContain("Admin account 'ada' created.")
        ->assertSuccessful();

    $user = User::where('email', 'ada@example.com')->firstOrFail();

    expect($user->name)->toBe('Ada Admin')
        ->and($user->username)->toBe('ada')
        ->and($user->isAdmin())->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeTrue()
        ->and(Hash::check('secret-password', $user->password))->toBeTrue();
});

test('the new admin can open the admin pages', function () {
    $this->artisan('app:create-admin')
        ->expectsQuestion('Name', 'Ada Admin')
        ->expectsQuestion('Username', 'ada')
        ->expectsQuestion('Email', 'ada@example.com')
        ->expectsQuestion('Password', 'secret-password')
        ->expectsQuestion('Confirm password', 'secret-password')
        ->assertSuccessful();

    $this->actingAs(User::where('username', 'ada')->firstOrFail())
        ->get(route('admin.users.index'))
        ->assertOk();
});

test('app:create-admin rejects invalid input', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->artisan('app:create-admin')
        ->expectsQuestion('Name', 'Ada Admin')
        ->expectsQuestion('Username', 'ada')
        ->expectsQuestion('Email', 'taken@example.com')
        ->expectsQuestion('Password', 'secret-password')
        ->expectsQuestion('Confirm password', 'different-password')
        ->expectsOutputToContain('The email has already been taken.')
        ->expectsOutputToContain('The password field confirmation does not match.')
        ->assertFailed();

    expect(User::count())->toBe(1);
});
