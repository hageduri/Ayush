<?php

namespace App\Policies;

use App\Models\Indicator;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IndicatorPolicy
{


    public function viewByAdmin(User $user): bool
    {
        return $user->role === 'ADMIN';
    }
    public function viewReport(User $user): bool//DMoverification resource
    {
        return $user->role === 'DMO';
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'MO';
    }



    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Indicator $indicator): bool
    {
        return $user->role === 'MO'||$user->role === 'DMO'||$user->role === 'ADMIN';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'MO';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Indicator $indicator): bool
    {
        return $user->role === 'MO';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Indicator $indicator): bool
    {
        return $user->role === 'MO';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Indicator $indicator): bool
    {
        return $user->role === 'MO';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Indicator $indicator): bool
    {
        return $user->role === 'MO';
    }
}
