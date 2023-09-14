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
     * @var \Illuminate\Validation\Validator $validator
     */
    public function update(User $user, Team $team, array $input)
    {
        Gate::forUser($user)->authorize("update", $team);

        $validator = Validator::make($input, [
            "name" => ["required", "string", "max:255"],
            "public" => ["boolean"],
        ]);

        // if validator fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, "updateTeam") // Pass validation errors to the session
                ->with(
                    "error",
                    "Please complete all fields correctly to proceed."
                );
        }

        $team
            ->forceFill([
                "name" => $input["name"],
                "public" => $input["public"],
            ])
            ->save();
    }
}
