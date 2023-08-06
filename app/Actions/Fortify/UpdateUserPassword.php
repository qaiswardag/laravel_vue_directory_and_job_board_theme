<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        )->after(function ($validator) {
            // TODO: Remove when not in Demo mode
            $validator->errors()->add('current_password', 'You are not able to reset the password in the demo mode.');
        })->validateWithBag("updatePassword");

        $user
            ->forceFill([
                "password" => Hash::make($input["password"]),
            ])
            ->save();
    }
}
