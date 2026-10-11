<?php

use App\Models\Country;
use App\Models\TimeLog;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->owner = User::factory()->create();
    $this->otherUser = User::factory()->create();
    $this->timeLog = TimeLog::factory()->for($this->owner)->create(['client' => 'Original Client']);

    Country::create(['name' => 'Norway', 'code' => 'NOR']);
});

function timeLogUpdatePayload(array $overrides = []): array
{
    return array_merge([
        'talk_type' => 'Chat',
        'talk_time' => 30,
        'client' => 'Updated Client',
        'date' => '2024-06-01',
        'payout' => 42.5,
        'country' => 'Norway',
        'payment_method' => 'Phone',
    ], $overrides);
}

test('admins can edit a log through its owner\'s URL', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.users.time-logs.edit', [$this->owner, $this->timeLog]))
        ->assertOk();

    $this->actingAs($this->admin)
        ->put(route('admin.users.time-logs.update', [$this->owner, $this->timeLog]), timeLogUpdatePayload())
        ->assertRedirect(route('admin.users.time-logs.index', $this->owner));

    expect($this->timeLog->fresh()->client)->toBe('Updated Client');
});

test('the payout input accepts cents', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('admin.users.time-logs.edit', [$this->owner, $this->timeLog]))
        ->assertOk();

    expect($response->getContent())->toMatch('/<input[^>]*name="payout"[^>]*step="0\.01"/');
});

test('admins can save a log with a decimal payout', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.users.time-logs.update', [$this->owner, $this->timeLog]), timeLogUpdatePayload(['payout' => '89.6']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.users.time-logs.index', $this->owner));

    expect($this->timeLog->fresh()->payout)->toEqual(89.6);
});

test('a negative payout is rejected', function () {
    $payout = $this->timeLog->payout;

    $this->actingAs($this->admin)
        ->put(route('admin.users.time-logs.update', [$this->owner, $this->timeLog]), timeLogUpdatePayload(['payout' => '-5']))
        ->assertSessionHasErrors(['payout']);

    expect($this->timeLog->fresh()->payout)->toEqual($payout);
});

test('admins can delete a log through its owner\'s URL', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.users.time-logs.destroy', [$this->owner, $this->timeLog]))
        ->assertRedirect();

    expect($this->timeLog->fresh())->toBeNull();
});

test('a log cannot be opened through another user\'s URL', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.users.time-logs.edit', [$this->otherUser, $this->timeLog]))
        ->assertNotFound();
});

test('a log cannot be updated through another user\'s URL', function () {
    $this->actingAs($this->admin)
        ->put(route('admin.users.time-logs.update', [$this->otherUser, $this->timeLog]), timeLogUpdatePayload())
        ->assertNotFound();

    expect($this->timeLog->fresh()->client)->toBe('Original Client');
});

test('a log cannot be deleted through another user\'s URL', function () {
    $this->actingAs($this->admin)
        ->delete(route('admin.users.time-logs.destroy', [$this->otherUser, $this->timeLog]))
        ->assertNotFound();

    expect($this->timeLog->fresh())->not->toBeNull();
});
