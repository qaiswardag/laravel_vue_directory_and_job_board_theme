<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // gate for can create and update
        Gate::define("for-middleware-can-create-and-update", function ($user) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            return in_array(
                $user->teamRole($user->currentTeam)->name,
                $allowedRoles
            );
        });

        // gate for can destroy
        Gate::define("for-middleware-can-destroy", function ($user) {
            $allowedRoles = ["Owner", "Administrator"];
            return in_array(
                $user->teamRole($user->currentTeam)->name,
                $allowedRoles
            );
        });
        // gate for can create and update
        Gate::define("can-create-and-update", function ($user, $team) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            return in_array($user->teamRole($team)->name, $allowedRoles);
        });

        // gate for can destroy
        Gate::define("can-destroy", function ($user, $team) {
            $allowedRoles = ["Owner", "Administrator"];
            return in_array($user->teamRole($team)->name, $allowedRoles);
        });
    }
}
