<?php

namespace App\Policies;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepositPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
    
    public function view(User $user, Deposit $deposit): bool
    {
        return $user->id === $deposit->user_id || $user->isAdmin();
    }
    
    public function create(User $user): bool
    {
        return true;
    }
    
    public function update(User $user, Deposit $deposit): bool
    {
        return $user->id === $deposit->user_id || $user->isAdmin();
    }
    
    public function delete(User $user, Deposit $deposit): bool
    {
        return $user->id === $deposit->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Deposit $deposit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Deposit $deposit): bool
    {
        return false;
    }
}
