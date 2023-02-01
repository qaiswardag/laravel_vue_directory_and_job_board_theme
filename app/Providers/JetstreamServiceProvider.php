<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ignore default routes from vendor folder and use our own Jetstream routes
        Jetstream::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);

        $this->configureRoutes();
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(["read"]);

        Jetstream::role("admin", "Administrator", [
            "create",
            "read",
            "update",
            "delete",
        ])->description("Administrator users can perform any action.");

        Jetstream::role("editor", "Editor", [
            "read",
            "create",
            "update",
        ])->description(
            "Editor users have the ability to read, create, and update."
        );
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        Route::group(
            [
                "namespace" => "Laravel\Jetstream\Http\Controllers",
                "domain" => config("jetstream.domain", null),
                "prefix" => config(
                    "jetstream.prefix",
                    config("jetstream.path")
                ),
            ],
            function () {
                $this->loadRoutesFrom(base_path("routes/jetstream.php"));
            }
        );
    }
}
