<?php

namespace App\Policies;

use App\Models\User;

class TimeLogPolicy
{
    /**
     * Any signed-in user can open the time log list. Employees only get their own logs.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admins see every user's logs, including payout.
     */
    public function viewAll(User $user): bool
    {
        return $user->isAdmin();
    }
}
