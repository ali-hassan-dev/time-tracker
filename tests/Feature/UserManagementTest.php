<?php

use App\Enums\Role;
use App\Models\User;

function userUpdatePayload(User $user, array $overrides = []): array
{
    return array_merge([
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
        'role' => $user->role->value,
    ], $overrides);
}

test('admins can delete another user', function () {
    $admin = User::factory()->admin()->create();
    $employee = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $employee))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    expect($employee->fresh())->toBeNull();
});

test('admins cannot delete their own account', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->admin()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('error', 'You cannot delete your own account.');

    expect($admin->fresh())->not->toBeNull();
});

test('the last admin cannot be deleted', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertSessionHas('error');

    expect($admin->fresh())->not->toBeNull();
});

test('the error is shown on the users page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->followingRedirects()
        ->delete(route('admin.users.destroy', $admin))
        ->assertSee('You cannot delete your own account.');
});

test('the last admin cannot be demoted', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->from(route('admin.users.edit', $admin))
        ->put(route('admin.users.update', $admin), userUpdatePayload($admin, ['role' => 'employee']))
        ->assertRedirect(route('admin.users.edit', $admin))
        ->assertSessionHasErrors(['role' => 'You cannot remove the admin role from the last admin.']);

    expect($admin->fresh()->role)->toBe(Role::Admin);
});

test('an admin can be demoted while another admin remains', function () {
    $admin = User::factory()->admin()->create();
    $otherAdmin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.update', $otherAdmin), userUpdatePayload($otherAdmin, ['role' => 'employee']))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasNoErrors();

    expect($otherAdmin->fresh()->role)->toBe(Role::Employee);
});

test('the last admin can still update their other details', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.users.update', $admin), userUpdatePayload($admin, ['name' => 'Renamed Admin']))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasNoErrors();

    expect($admin->fresh()->name)->toBe('Renamed Admin');
});
