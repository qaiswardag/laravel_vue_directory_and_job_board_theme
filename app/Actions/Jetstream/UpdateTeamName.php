<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, Team $team, array $input): void
    {
        sleep(1);
        Gate::forUser($user)->authorize("update", $team);

        Validator::make($input, [
            "name" => ["required", "string", "max:255"],
            "thumbnail" => ["required", "string", "max:255"],
            "logo" => ["required", "string", "max:255"],
            "public" => ["boolean"],
        ])->validateWithBag("updateTeam");

        $team
            ->forceFill([
                "name" => $input["name"],
                "thumbnail" => $input["thumbnail"],
                "logo" => $input["logo"],
                "public" => $input["public"],
            ])
            ->save();
    }
}
