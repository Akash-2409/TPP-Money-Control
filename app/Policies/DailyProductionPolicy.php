<?php

namespace App\Policies;

use App\Models\DailyProduction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DailyProductionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DailyProduction $dailyProduction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // dd('policy-ran', $user->id, $user->role);
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyProduction $dailyProduction): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyProduction $dailyProduction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DailyProduction $dailyProduction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DailyProduction $dailyProduction): bool
    {
        return false;
    }
}
