<?php

namespace App\Http\Requests\LoggedIn\Team;

use App\Models\MediaLibrary\MediaLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTeamUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => ["required", "string", "max:255"],
            "public" => ["boolean"],
            "slug" => [
                "required",
                Rule::unique("teams")->ignore($this->team_id),
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
        ];

        return $rules;
    }
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $maxCoverImages = 6;
        $maxLogos = 1;

        $validator->after(function ($validator) use (
            $maxCoverImages,
            $maxLogos
        ) {
            if ($this->team === null) {
                $validator
                    ->errors()
                    ->add("team", "The team field is required.");
            }

            $this->validateProperties($validator);

            if (gettype($this->cover_image) !== "array") {
                $validator
                    ->errors()
                    ->add("cover_image", "cover image field must be an array.");
            }
            if (
                gettype($this->cover_image) === "array" &&
                count($this->cover_image) > $maxCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "Cover image field is limited to a maximum of {$maxCoverImages} selection(s)."
                    );
            }
            // Check if the "primary" key exists, or provide a default value of false
            if (!empty($this->cover_image)) {
                // Loop through the array and attach each category to the post
                foreach ($this->cover_image as $image) {
                    // Check if the "id" key exists in the $image array
                    if (array_key_exists("id", $image)) {
                        $imageId = $image["id"];

                        // Check if a media library record with this ID exists
                        $mediaLibrary = MediaLibrary::find($imageId);

                        if ($mediaLibrary === null) {
                            $validator
                                ->errors()
                                ->add(
                                    "cover_image",
                                    "At least one your attached images no longer exists in the Media Library. Delete the image and try again or click the 'Clear form' button and then try again."
                                );
                        }
                    }
                }
            }
            // validation for cover image # end

            // Additional validation to ensure only one image is marked as primary # start
            $primaryImages = is_array($this->cover_image)
                ? array_filter($this->cover_image, function ($image) {
                    return isset($image["pivot"]) &&
                        isset($image["pivot"]["primary"]) &&
                        $image["pivot"]["primary"];
                })
                : [];

            if (
                count($primaryImages) === 0 &&
                gettype($this->cover_image) === "array" &&
                count($this->cover_image) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "At least one image must be marked as primary."
                    );
            }

            if (
                count($primaryImages) > 1 &&
                gettype($this->cover_image) === "array" &&
                count($this->cover_image) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "Only one image can be marked as primary."
                    );
            }
            // Additional validation to ensure only one image is marked as primary # end
            //
            //
            //
            //
            // validation for logo # start
            if (
                $this->logo === null ||
                (gettype($this->logo) === "array" && count($this->logo) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("logo", "The logo field is required.");
            }

            if (gettype($this->logo) !== "array") {
                $validator
                    ->errors()
                    ->add("logo", "logo field must be an array.");
            }
            if (
                gettype($this->logo) === "array" &&
                count($this->logo) > $maxLogos
            ) {
                $validator
                    ->errors()
                    ->add(
                        "logo",
                        "Logo field is limited to a maximum of {$maxLogos} selection(s)."
                    );
            }
            // Check if the "primary" key exists, or provide a default value of false
            if (!empty($this->logo)) {
                // Loop through the array and attach each logo to the post
                foreach ($this->logo as $image) {
                    // Check if the "id" key exists in the array
                    if (array_key_exists("id", $image)) {
                        $imageId = $image["id"];

                        // Check if a media library record with this ID exists
                        $mediaLibrary = MediaLibrary::find($imageId);

                        if ($mediaLibrary === null) {
                            $validator
                                ->errors()
                                ->add(
                                    "logo",
                                    "At least one your attached images no longer exists in the Media Library. Delete the image and try again or click the 'Clear form' button and then try again."
                                );
                        }
                    }
                }
            }
            // validation for logo # end

            // Additional validation to ensure only one logo is marked as primary # start
            $primaryImages = is_array($this->logo)
                ? array_filter($this->logo, function ($image) {
                    return isset($image["pivot"]) &&
                        isset($image["pivot"]["primary"]) &&
                        $image["pivot"]["primary"];
                })
                : [];

            if (
                count($primaryImages) === 0 &&
                gettype($this->logo) === "array" &&
                count($this->logo) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "logo",
                        "At least one logo must be marked as primary."
                    );
            }

            if (
                count($primaryImages) > 1 &&
                gettype($this->logo) === "array" &&
                count($this->logo) > 1
            ) {
                $validator
                    ->errors()
                    ->add("logo", "Only one image can be marked as primary.");
            }
            // Additional validation to ensure only one logo is marked as primary # end
        });

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "Please complete all fields correctly to proceed."
            );
        }

        // end of withValidator
    }

    // validate propertiers
    private function validateProperties($validator)
    {
        // cover image
        if (gettype($this->cover_image) === "array") {
            foreach ($this->cover_image as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "cover_image",
                            "Image ID for the image is not present in the Media Library. Please clear your form and try again."
                        );
                }
                if (
                    isset($item["pivot"]) &&
                    !isset($item["pivot"]["primary"])
                ) {
                    $validator
                        ->errors()
                        ->add(
                            "cover_image",
                            "The 'primary' attribute is missing for this image, even though the image is set as primary.  To resolve this issue, please delete all images and then try again."
                        );
                }
            }
        }
        // logo
        if (gettype($this->logo) === "array") {
            foreach ($this->logo as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "logo",
                            "Image ID for the logo is not present in the Media Library. Please clear your form and try again."
                        );
                }
                if (
                    isset($item["pivot"]) &&
                    !isset($item["pivot"]["primary"])
                ) {
                    $validator
                        ->errors()
                        ->add(
                            "logo",
                            "The 'primary' attribute is missing for this logo, even though the image is set as primary.  To resolve this issue, please click the 'Clear form' button and then try again."
                        );
                }
            }
        }
    }
}
