<?php

namespace App\Policies;

use App\Models\User;

class CountryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->hasPermissionTo('List Country')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        if($user->hasPermissionTo('View Country')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->hasPermissionTo('Create Country')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        if($user->hasPermissionTo('Update Country')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        if($user->hasPermissionTo('Delete Country')) {
            return true;
        }
        return false;
    }
}
