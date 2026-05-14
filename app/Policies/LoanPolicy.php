<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LoanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }
    
    public function view(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id || $user->isAdmin();
    }
    
    public function create(User $user): bool
    {
        return true;
    }
    
    public function update(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id || $user->isAdmin();
    }
    
    public function delete(User $user, Loan $loan): bool
    {
        return $user->id === $loan->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Loan $loan): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Loan $loan): bool
    {
        return false;
    }
}
