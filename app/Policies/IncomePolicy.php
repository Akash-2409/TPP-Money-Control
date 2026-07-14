<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;

class IncomePolicy
{
    public function create(User $user)
    {
        return true; // all logged-in users can create their own income
    }

    public function delete(User $user, Income $income)
    {
        return $user->hasRole('superadmin') || $income->user_id === $user->id;
    }
}
