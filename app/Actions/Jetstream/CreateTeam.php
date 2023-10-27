<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Team
    {
        $team = Team::where("user_id", $user->id)->get();

        $slug = Str::lower(Str::slug($input["slug"], "_"));

        Gate::forUser($user)->authorize("create", Jetstream::newTeamModel());

        AddingTeam::dispatch($user);

        $user->switchTeam(
            $team = $user->ownedTeams()->create([
                "name" => $input["name"],
                "slug" => $slug,
                "thumbnail" => null,
                // if user do not own and user is creating thier first team make that team thier personal team
                // "personal_team" => $userTeams->count() ? false : true,
                "personal_team" => false,
                "public" => $input["public"],
            ])
        );

        return $team;
    }
}
