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
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users",
            ],
            "username" => [
                "required",
                "string",
                "min:5",
                "max:255",
                "unique:users",
            ],
            "public" => ["boolean"],
            "password" => $this->passwordRules(),
            "terms" => Jetstream::hasTermsAndPrivacyPolicyFeature()
                ? ["accepted", "required"]
                : "",
        ])->validateWithBag("createUser");

        return DB::transaction(function () use ($input) {
            return User::create(
                [
                    "first_name" => $input["first_name"],
                    "last_name" => $input["last_name"],
                    "email" => $input["email"],
                    "username" => $input["username"],
                    "public" => true,
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
                "name" => explode(" ", $user->first_name, 2)[0] . "'s Team",
                "personal_team" => true,
            ])
        );
    }
}
