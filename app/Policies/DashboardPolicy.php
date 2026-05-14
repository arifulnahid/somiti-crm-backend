<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class DashboardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user can view dashboard statistics.
     */
    public function viewDashboard(User $user, Branch $branch): bool
    {
        // Example: Only Admins and Branch Managers can see the dashboard
        return $user->hasAnyRole(['admin', 'branch_manager', 'vendor']);
    }
}
