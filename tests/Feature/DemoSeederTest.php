<?php

use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;
use Database\Seeders\DemoSeeder;

test('the demo seeder creates one admin, four employees, and about 60 logs', function () {
    $this->seed(DemoSeeder::class);

    expect(User::where('role', 'admin')->count())->toBe(1)
        ->and(User::where('role', 'employee')->count())->toBe(4)
        ->and(TimeLog::count())->toBe(61)
        ->and(TimeLog::whereNull('end_time')->count())->toBe(1);
});

test('demo data is obviously fake', function () {
    $this->seed(DemoSeeder::class);

    User::all()->each(fn (User $user) => expect($user->email)->toEndWith('@example.com'));
});

test('demo logs cover both session types, both payment methods, and listed countries', function () {
    $this->seed(DemoSeeder::class);

    expect(TimeLog::distinct()->pluck('talk_type')->sort()->values()->all())->toBe(['Chat', 'Talk'])
        ->and(TimeLog::distinct()->pluck('payment_method')->sort()->values()->all())->toBe(['Crypto', 'Phone'])
        ->and(TimeLog::distinct()->pluck('country')->diff(Country::pluck('name')))->toBeEmpty()
        ->and(TimeLog::where('start_time', '<', now()->subDays(15))->exists())->toBeFalse();
});

test('demo accounts can log in and are verified', function () {
    $this->seed(DemoSeeder::class);

    $admin = User::where('username', 'admin')->firstOrFail();

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->hasVerifiedEmail())->toBeTrue();

    $this->actingAs($admin)->get('/employees')->assertOk()->assertSee('alex');
});

test('the demo seeder can run twice', function () {
    $this->seed(DemoSeeder::class);
    $this->seed(DemoSeeder::class);

    expect(User::count())->toBe(5)
        ->and(TimeLog::count())->toBe(61)
        ->and(Country::count())->toBe(4);
});
