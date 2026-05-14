<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Society;
use App\Models\User;

class SocietyPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role == UserRole::SUPER_ADMIN) {
            return true;
        }

        return null; // Fall through to the specific policy methods
    }

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
    public function view(User $user, Society $society): bool
    {
        return $user->hasRole([UserRole::ADMIN, UserRole::SUPER_ADMIN])
        || $user->member->id == $society->member->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole([UserRole::ADMIN, UserRole::SUPER_ADMIN]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Society $society): bool
    {
        return $user->hasRole([UserRole::ADMIN, UserRole::SUPER_ADMIN]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Society $society): bool
    {
        return $user->hasRole([UserRole::ADMIN, UserRole::SUPER_ADMIN]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Society $society): bool
    {
        return $user->hasRole([UserRole::ADMIN, UserRole::SUPER_ADMIN]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Society $society): bool
    {
        return $user->hasRole([UserRole::SUPER_ADMIN]);
    }
}
