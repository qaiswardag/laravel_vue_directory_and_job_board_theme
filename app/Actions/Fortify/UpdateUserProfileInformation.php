<?php

namespace App\Actions\Fortify;

use App\Models\MediaLibraryUser;
use App\Models\User;
use App\Models\UserPhotoRelationship;
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
            "photo" => ["nullable", "min:2", "max:255"],

            "country" => ["string", "min:2", "max:255", "nullable"],
            "city" => ["string", "min:2", "max:255", "nullable"],
            "state" => ["string", "min:2", "max:255", "nullable"],
            "line1" => ["string", "min:2", "max:255", "nullable"],
            "line2" => ["string", "min:2", "max:255", "nullable"],
            "postal_code" => ["string", "min:2", "max:255", "nullable"],
            "phone" => ["integer", "digits_between:4,16", "nullable"],
            "phone_code" => ["regex:/^\d{1,8}(-\d{1,8})?$/", "nullable"],
            "job_title" => ["required", "string", "max:255", "nullable"],

            // In MySQL, the LONGTEXT data type can store up to 4GB of data,
            // while the TEXT data type can store
            // up to 65535 bytes (or characters) of data.

            // If you want to allow the full capacity of the longText column
            // (which is up to 4GB), you don't need to set a maximum length in
            // the validation rule. You can simply use the required and string
            // rules to validate that the input is not empty and is a string
            "content" => ["required", "string", "min:2", "max:65535"],
        ]);

        $validator->after(function ($validator) use ($input) {
            $minCoverImages = 0;
            $maxCoverImages = 2;

            // Validation for profile photo # start
            // Validation for profile photo # start

            if (
                isset($input["user_image"]) &&
                gettype($input["user_image"]) !== "array"
            ) {
                $validator
                    ->errors()
                    ->add("user_image", "cover image field must be an array.");
            }

            if (
                isset($input["user_image"]) &&
                is_array($input["user_image"]) &&
                count($input["user_image"]) < $minCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "user_image",
                        "At least {$minCoverImages} images are necessary."
                    );
            }

            if (
                isset($input["user_image"]) &&
                gettype($input["user_image"]) === "array" &&
                count($input["user_image"]) > $maxCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "user_image",
                        "User image field is limited to a maximum of {$maxCoverImages} selection(s)."
                    );
            }

            // Check if the "primary" key exists, or provide a default value of false
            if (
                isset($input["user_image"]) &&
                !empty($input["user_image"]) &&
                gettype($input["user_image"]) === "array"
            ) {
                // Loop through the array and attach each category to the job
                foreach ($input["user_image"] as $image) {
                    // Check if the "id" key exists in the $image array
                    if (array_key_exists("id", $image)) {
                        $imageId = $image["id"];

                        // Check if a media library record with this ID exists
                        $mediaLibrary = MediaLibraryUser::find($imageId);

                        if ($mediaLibrary === null) {
                            $validator
                                ->errors()
                                ->add(
                                    "user_image",
                                    "At least one of your attached images no longer exists in the Media Library. Delete the image and try again or click the 'Clear form' button and then try again."
                                );
                        }
                    }
                }
            }
            // validation for User image # end

            // Additional validation to ensure only one image is marked as primary # start
            $primaryImages =
                isset($input["user_image"]) && is_array($input["user_image"])
                    ? array_filter($input["user_image"], function ($image) {
                        return isset($image["pivot"]) &&
                            isset($image["pivot"]["primary"]) &&
                            $image["pivot"]["primary"];
                    })
                    : [];

            if (
                isset($input["user_image"]) &&
                count($primaryImages) === 0 &&
                gettype($input["user_image"]) === "array" &&
                count($input["user_image"]) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "user_image",
                        "At least one image must be marked as primary."
                    );
            }
            // Additional validation to ensure only one image is marked as primary # end
            // Validation for profile photo # end

            // validation phone # start
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
            // validation phone # end
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

        // dd("username er:", $input["username"]);

        if (
            $input["email"] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
            $this->updateUserPhoto($user, $input);
        } else {
            $user
                ->forceFill([
                    "first_name" => $input["first_name"],
                    "last_name" => $input["last_name"],
                    "username" => $input["username"],
                    "email" => $input["email"],
                    "public" => $input["public"],

                    "content" => $input["content"],

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

            $this->updateUserPhoto($user, $input);
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

                "content" => $input["content"],

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

    protected function updateUserPhoto(User $user, array $input): void
    {
        // Retrieve the existing cover image relationships for the post
        $existingCoverImages = UserPhotoRelationship::where(
            "user_id",
            $user->id
        )->get();

        // Create an array to store the IDs of existing cover images
        $existingCoverImageIds = $existingCoverImages
            ->pluck("media_library_id")
            ->toArray();

        // Loop through the request's cover images and update or create cover image relationships
        // Check if $input["user_image"] is not null and is an array
        if (
            $input["user_image"] !== null &&
            gettype($input["user_image"]) === "array" &&
            count($input["user_image"]) !== 0
        ) {
            foreach ($input["user_image"] as $coverImage) {
                $imageId = $coverImage["id"];
                $isPrimary = isset($coverImage["pivot"]["primary"])
                    ? $coverImage["pivot"]["primary"]
                    : false;

                // Update or create cover image relationship
                UserPhotoRelationship::updateOrCreate(
                    [
                        "media_library_id" => $imageId,
                        "user_id" => $user->id,
                    ],
                    ["primary" => $isPrimary]
                );

                // Remove the image ID from the existingCoverImageIds array
                $key = array_search($imageId, $existingCoverImageIds);
                if ($key !== false) {
                    unset($existingCoverImageIds[$key]);
                }
            }
        }

        // Delete any remaining cover image relationships that are no longer needed
        UserPhotoRelationship::where("user_id", $user->id)
            ->whereIn("media_library_id", $existingCoverImageIds)
            ->delete();

        // Update cover images # end
    }
}
