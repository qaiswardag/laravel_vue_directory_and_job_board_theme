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
        // Allowing special characters like \, !, @, and . in a username
        // could be potentially dangerous, as they could be used in various
        // attack techniques like SQL injection, cross-site scripting (XSS),
        // and command injection attacks. Therefore, it is generally recommended to
        // avoid allowing special characters in usernames, or at least properly
        // validate and sanitize the input to prevent potential attacks.

        // Additionally, usernames containing non-ASCII characters,
        // such as the Danish characters in your example Æ, ø, and l,
        // can also cause issues with URL encoding and decoding, as different
        // browsers and systems may encode and decode them differently.
        // Therefore, it's important to properly handle these characters to prevent
        // issues with accessing user profiles.
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
                "unique:users",
                "string",
                "min:4",
                "max:20",
                "regex:/^[a-zA-Z0-9_]+$/u",
                // regular expression `/^[a-zA-Z0-9_]+$/u`
                // will only allow usernames that contain only alphanumeric characters
                // (letters A-Z, numbers 0-9) and underscores. The + sign after the character
                // set [] means that one or more of these characters can be present
                // in the string, and the /u flag at the end indicates that the regular
                // expression is "unicode-safe," meaning it can handle non-ASCII characters.

                // By using above regular expression, you're effectively preventing
                // any special characters like !, @, ., or even the Danish characters

                // twitter rules
                // Twitter states that usernames can only contain alphanumeric characters
                // (letters A-Z, numbers 0-9) and underscores, and must be between
                // 4-20 characters in length.

                // These characters can be used in various attack techniques, including SQL injection, cross-site scripting (XSS), and command injection attacks, among others. It's important to properly validate and sanitize user input to prevent these types of attacks.
                // ; (semicolon)
                // ' (single quote)
                // " (double quote)
                // < (less than)
                // > (greater than)
                // / (slash)
                // % (percent)
                // ` (backtick)
                // & (ampersand)
                // | (pipe)
                // $ (dollar sign)
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
