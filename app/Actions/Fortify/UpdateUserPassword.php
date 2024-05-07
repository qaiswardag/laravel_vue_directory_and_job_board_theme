<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input)
    {
        //
        Validator::make(
            $input,
            [
                "current_password" => [
                    "required",
                    "string",
                    "current_password:web",
                ],
                "password" => $this->passwordRules(),
            ],
            [
                "current_password.current_password" => __(
                    "The provided password does not match your current password."
                ),
            ]
        )->validateWithBag("updatePassword");

        if ($user->id === 2 || $user->id === 3) {
            return;
        }

        $user
            ->forceFill([
                "password" => Hash::make($input["password"]),
            ])
            ->save();
    }
}
