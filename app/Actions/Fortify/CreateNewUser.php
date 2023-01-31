<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        sleep(1);
        Validator::make($input, [
            "firstName" => ["required", "string", "max:255"],
            "lastName" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "password" => $this->passwordRules(),
            "terms" => Jetstream::hasTermsAndPrivacyPolicyFeature()
                ? ["accepted", "required"]
                : "",
        ])->validate();

        return DB::transaction(function () use ($input) {
            return User::create(
                [
                    "firstName" => $input["firstName"],
                    "lastName" => $input["lastName"],
                    "email" => $input["email"],
                    "password" => Hash::make($input["password"]),
                ]
                // function (User $user) {
                //     $this->createTeam($user);
                // }
            );
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(
            Team::forceCreate([
                "user_id" => $user->id,
                "name" => explode(" ", $user->firstName, 2)[0] . "'s Team",
                "personal_team" => true,
            ])
        );
    }
}
