<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     * @var \Illuminate\Validation\Validator $validator
     */
    public function update(User $user, array $input)
    {
        $validator = Validator::make($input, [
            "first_name" => ["required", "string", "max:255"],
            "last_name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "email",
                "max:255",
                Rule::unique("users")->ignore($user->id),
            ],

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
            "username" => [
                "required",
                Rule::unique("users")->ignore($user->id),
                "string",
                "min:4",
                "max:60",
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
            "photo" => ["nullable", "mimes:jpg,jpeg,png", "max:2048"],

            "country" => ["string", "min:2", "max:255", "nullable"],
            "city" => ["string", "min:2", "max:255", "nullable"],
            "state" => ["string", "min:2", "max:255", "nullable"],
            "line1" => ["string", "min:2", "max:255", "nullable"],
            "line2" => ["string", "min:2", "max:255", "nullable"],
            "postal_code" => [
                "string",
                "min:2",
                "max:255",
                "nullable",
            ],
            "phone" => ["integer", "digits_between:4,16", "nullable"],
            "phone_code" => ["regex:/^\d{1,8}(-\d{1,8})?$/", "nullable"],
            "job_title" => ["required", "string", "max:255", "nullable"],
        ]);

        $validator->after(function ($validator) use ($input) {
            if (isset($input["phone"]) && !isset($input["phone_code"])) {
                $validator->errors()->add(
                    "phone_code",
                    "Phone country code is required when phone number is set.

                "
                );
            }

            if (isset($input["phone_code"]) && !isset($input["phone"])) {
                $validator->errors()->add(
                    "phone",
                    "Phone number is required when phone_code country code is set.

                "
                );
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return back()
                ->withErrors($validator, "updateProfileInformation") // Pass validation errors to the session
                ->with(
                    "error",
                    "Please complete all fields correctly to proceed."
                );
        }

        if (isset($input["photo"])) {
            $user->updateProfilePhoto($input["photo"]);
        }

        if (
            $input["email"] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user
                ->forceFill([
                    "first_name" => $input["first_name"],
                    "last_name" => $input["last_name"],
                    "username" => $input["username"],
                    "email" => $input["email"],
                    "public" => $input["public"],

                    "country" => $input["country"] ?? null,
                    "city" => $input["city"] ?? null,
                    "state" => $input["state"] ?? null,
                    "line1" => $input["line1"] ?? null,
                    "line2" => $input["line2"] ?? null,
                    "postal_code" => $input["postal_code"] ?? null,
                    "phone" => $input["phone"] ?? null,
                    "phone_code" => $input["phone_code"] ?? null,
                    "job_title" => $input["job_title"] ?? null,
                ])
                ->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user
            ->forceFill([
                "first_name" => $input["first_name"],
                "last_name" => $input["last_name"],
                "useranme" => $input["username"],
                "email" => $input["email"],
                "public" => $input["public"],

                "country" => $input["country"] ?? null,
                "city" => $input["city"] ?? null,
                "state" => $input["state"] ?? null,
                "line1" => $input["line1"] ?? null,
                "line2" => $input["line2"] ?? null,
                "postal_code" => $input["postal_code"] ?? null,
                "phone" => $input["phone"] ?? null,
                "phone_code" => $input["phone_code"] ?? null,
                "job_title" => $input["job_title"] ?? null,

                "email_verified_at" => null,
            ])
            ->save();

        $user->sendEmailVerificationNotification();
    }
}
