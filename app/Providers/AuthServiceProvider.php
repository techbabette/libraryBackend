<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('update-loan', function (User $user, Loan $loan) {
            if($user->access_level->access_level === 2){
                return true;
            }
            return $loan->user_id === $user->id;
        });
    }
}
