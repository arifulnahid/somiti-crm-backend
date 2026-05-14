<?php

namespace App\Providers;

use App\Models\Branch;
use App\Policies\DashboardPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'branch' => 'App\Models\Branch',
        ]);

        Gate::policy(Branch::class, DashboardPolicy::class);
    }
}
