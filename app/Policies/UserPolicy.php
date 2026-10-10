<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Admins can delete other accounts, but never their own or the last admin.
     */
    public function delete(User $user, User $model): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        if ($user->is($model)) {
            return Response::deny('You cannot delete your own account.');
        }

        if ($this->isLastAdmin($model)) {
            return Response::deny('You cannot delete the last admin.');
        }

        return Response::allow();
    }

    /**
     * Admins can change roles, as long as at least one admin remains.
     */
    public function changeRole(User $user, User $model, Role $role): Response
    {
        if (! $user->isAdmin()) {
            return Response::deny();
        }

        if ($role !== Role::Admin && $this->isLastAdmin($model)) {
            return Response::deny('You cannot remove the admin role from the last admin.');
        }

        return Response::allow();
    }

    private function isLastAdmin(User $model): bool
    {
        return $model->isAdmin() && User::where('role', Role::Admin)->count() === 1;
    }
}
