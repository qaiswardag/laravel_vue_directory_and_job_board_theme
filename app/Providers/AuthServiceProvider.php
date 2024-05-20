<?php

namespace App\Providers;

use App\Models\Team;
use App\Models\User;
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
        Gate::define("for-middleware-can-read", function (User $user) {
            $allowedRoles = ["Owner", "Administrator", "Editor", "Reader"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // middleware gate for can create and update
        Gate::define("for-middleware-can-create-and-update", function (
            User $user
        ) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // middleware gate for can destroy
        Gate::define("for-middleware-can-destroy", function (User $user) {
            $allowedRoles = ["Owner", "Administrator"];

            if ($user->teamRole($user->currentTeam) !== null) {
                return in_array(
                    $user->teamRole($user->currentTeam)->name,
                    $allowedRoles
                );
            }
        });

        // gate for can create and update
        Gate::define("can-read", function (User $user, Team $team) {
            $allowedRoles = ["Owner", "Administrator", "Editor", "Reader"];

            if ($user->teamRole($team) !== null) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // gate for can create and update
        Gate::define("can-create-and-update", function (
            User $user,
            Team $team
        ) {
            $allowedRoles = ["Owner", "Administrator", "Editor"];

            if (
                $user->teamRole($team) !== null &&
                ($user->id === 1 || $user->id === 3 || $user->id === 4)
            ) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // gate for can destroy
        Gate::define("can-destroy", function (User $user, Team $team) {
            $allowedRoles = ["Owner", "Administrator"];

            if (
                $user->teamRole($team) !== null &&
                ($user->id === 1 || $user->id === 3 || $user->id === 4)
            ) {
                return in_array($user->teamRole($team)->name, $allowedRoles);
            }
        });

        // superadmin gates for CRUD
        // reader
        // editor
        // admin

        Gate::define("superadmin-can-read", function (User $user) {
            // Authorization logic for 'superadmin-can-read' permission
            return $user->superadmin !== null &&
                ($user->superadmin->role === "admin" ||
                    $user->superadmin->role === "editor" ||
                    $user->superadmin->role === "reader");
        });

        Gate::define("superadmin-can-create-and-update", function (User $user) {
            // Authorization logic for 'superadmin-can-create-and-update' permission
            return $user->superadmin !== null &&
                ($user->superadmin->role === "admin" ||
                    $user->superadmin->role === "editor");
        });

        Gate::define("superadmin-can-destroy", function (User $user) {
            // Authorization logic for 'superadmin-can-destroy' permission
            return $user->superadmin !== null &&
                $user->superadmin->role === "admin";
        });
    }
}
