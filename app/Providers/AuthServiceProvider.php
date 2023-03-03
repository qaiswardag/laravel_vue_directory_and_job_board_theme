<?php

namespace App\Providers;

use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;

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

        // middleware gate for can create and update
        Gate::define("for-middleware-can-read", function ($user) {
            $allowedRoles = ["Owner", "Administrator", "Editor", "Reader"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // middleware gate for can create and update
        Gate::define("for-middleware-can-create-and-update", function ($user) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // middleware gate for can destroy
        Gate::define("for-middleware-can-destroy", function ($user) {
            $allowedRoles = ["Owner", "Administrator"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // gate for can create and update
        Gate::define("can-read", function ($user, $team) {
            $allowedRoles = ["Owner", "Administrator", "Editor", "Reader"];

            if ($user->teamRole($team) !== null) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // gate for can create and update
        Gate::define("can-create-and-update", function ($user, $team) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            if ($user->teamRole($team) !== null) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // gate for can destroy
        Gate::define("can-destroy", function ($user, $team) {
            $allowedRoles = ["Owner", "Administrator"];

            if ($user->teamRole($team) !== null) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // superadmin gate for CRUD
        Gate::define("superadmin", function () {
            return Auth::user()->superadmin === 1 ? true : null;
        });
    }
}
