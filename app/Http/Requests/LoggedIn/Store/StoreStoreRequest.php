<?php

namespace App\Http\Requests\LoggedIn\Store;

use App\Models\MediaLibrary\MediaLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StoreStoreRequest extends FormRequest
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
            "published" => ["boolean"],
            "featured" => ["boolean", "nullable"],
            "show_author" => ["boolean"],
            // If you do not include the string validation rule for a text input like title.
            // it may allow non-string values to be submitted and saved in the database.
            // This could potentially cause security issues or errors in your application,
            // depending on how the input is used.

            // For example, if the title input is used in a SQL query without
            // being properly sanitized or escaped, non-string values could cause
            // SQL injection vulnerabilities, allowing an attacker to execute malicious
            // SQL code on your database.

            "title" => ["required", "string", "min:2", "max:255"],
            "slug" => ["required", "string", "min:2", "max:255"],
            "address" => ["required", "string", "min:2", "max:255"],
            "floor" => ["integer", "digits_between:1,4", "nullable"],
            "contact_page_url" => [
                "required",
                "min:2",
                "max:255",
                "url:https",
                "nullable",
            ],
            "brand_website_url" => [
                "required",
                "min:2",
                "max:255",
                "url:https",
                "nullable",
            ],

            // If user_id is a foreign key column that references the id column of the users table,
            // you can use the exists validation rule to ensure that the value of user_id exists in
            // the id column of the users table.

            // Note that the foreignId method in Laravel automatically creates a foreign key
            // constraint in the database, which ensures that the value of user_id references
            // an existing user in the users table. However, the exists validation rule
            // provides an additional layer of validation to ensure that the foreign key reference
            // is valid at the application level as well.
            "user_id" => ["required", "integer", "exists:users,id"],

            "team" => ["required"],

            // In MySQL, the LONGTEXT data type can store up to 4GB of data,
            // while the TEXT data type can store
            // up to 65535 bytes (or characters) of data.

            // If you want to allow the full capacity of the longText column
            // (which is up to 4GB), you don't need to set a maximum length in
            // the validation rule. You can simply use the required and string
            // rules to validate that the input is not empty and is a string
            "content" => ["required", "string", "min:2", "max:65535"],

            "tags" => ["string", "max:255", "nullable"],
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
        $maxAuthors = 18;
        $maxCategories = 8;
        $maxStoreStates = 1;
        $minBrandLogos = 1;
        $maxBrandLogos = 1;

        $minCoverImages = 1;
        $maxCoverImages = 6;

        $validator->after(function ($validator) use (
            $maxAuthors,
            $maxCategories,
            $maxStoreStates,
            $minBrandLogos,
            $maxBrandLogos,
            $minCoverImages,
            $maxCoverImages
        ) {
            if ($this->team === null) {
                $validator
                    ->errors()
                    ->add("team", "The team field is required.");
            }

            $this->validateProperties($validator);

            // validation for cover image # start
            if (
                $this->cover_image === null ||
                (gettype($this->cover_image) === "array" &&
                    count($this->cover_image) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("cover_image", "The cover image field is required.");
            }

            if (gettype($this->cover_image) !== "array") {
                $validator
                    ->errors()
                    ->add("cover_image", "cover image field must be an array.");
            }

            if (
                gettype($this->cover_image) === "array" &&
                count($this->cover_image) < $minCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "At least {$minCoverImages} images are necessary."
                    );
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

            // validation for brand logo # start
            if (
                $this->brand_logo === null ||
                (gettype($this->brand_logo) === "array" &&
                    count($this->brand_logo) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("brand_logo", "The brand logo field is required.");
            }

            if (gettype($this->brand_logo) !== "array") {
                $validator
                    ->errors()
                    ->add("brand_logo", "Brand logo field must be an array.");
            }

            if (
                gettype($this->brand_logo) === "array" &&
                count($this->brand_logo) < $minBrandLogos
            ) {
                $validator
                    ->errors()
                    ->add(
                        "brand_logo",
                        "At least {$minBrandLogos} images are necessary."
                    );
            }

            if (
                gettype($this->brand_logo) === "array" &&
                count($this->brand_logo) > $maxBrandLogos
            ) {
                $validator
                    ->errors()
                    ->add(
                        "brand_logo",
                        "Brand logo image field is limited to a maximum of {$maxBrandLogos} selection(s)."
                    );
            }
            // Check if the "primary" key exists, or provide a default value of false
            if (!empty($this->brand_logo)) {
                // Loop through the array and attach each category to the post
                foreach ($this->brand_logo as $image) {
                    // Check if the "id" key exists in the $image array
                    if (array_key_exists("id", $image)) {
                        $imageId = $image["id"];

                        // Check if a media library record with this ID exists
                        $mediaLibrary = MediaLibrary::find($imageId);

                        if ($mediaLibrary === null) {
                            $validator
                                ->errors()
                                ->add(
                                    "brand_logo",
                                    "At least one your attached images no longer exists in the Media Library. Delete the image and try again or click the 'Clear form' button and then try again."
                                );
                        }
                    }
                }
            }
            // validation for brand logo # end

            // Additional cover image validation to ensure only one image is marked as primary # start
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
                        "At least one cover image must be marked as primary."
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
            // Additional cover image validation to ensure only one image is marked as primary # end

            // Additional brand logos validation to ensure only one image is marked as primary # start
            $primaryImagesBrandLogos = is_array($this->brand_logo)
                ? array_filter($this->brand_logo, function ($image) {
                    return isset($image["pivot"]) &&
                        isset($image["pivot"]["primary"]) &&
                        $image["pivot"]["primary"];
                })
                : [];

            if (
                count($primaryImagesBrandLogos) === 0 &&
                gettype($this->brand_logo) === "array" &&
                count($this->brand_logo) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "brand_logo",
                        "At least one brand logo image must be marked as primary."
                    );
            }

            if (
                count($primaryImagesBrandLogos) > 1 &&
                gettype($this->brand_logo) === "array" &&
                count($this->brand_logo) > 1
            ) {
                $validator
                    ->errors()
                    ->add(
                        "brand_logo",
                        "Only one image can be marked as primary."
                    );
            }
            // Additional brand logos validation to ensure only one image is marked as primary # end

            // validation for categories # start
            if (
                $this->categories === null ||
                (gettype($this->categories) === "array" &&
                    count($this->categories) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("categories", "The categories field is required.");
            }

            if (gettype($this->categories) !== "array") {
                $validator
                    ->errors()
                    ->add("categories", "categories field must be an array.");
            }
            if (
                gettype($this->categories) === "array" &&
                count($this->categories) > $maxCategories
            ) {
                $validator
                    ->errors()
                    ->add(
                        "categories",
                        "Categories field is limited to a maximum of {$maxCategories} categories."
                    );
            }
            // validation for categories # end

            // validation for states # start
            if (
                $this->states === null ||
                (gettype($this->states) === "array" &&
                    count($this->states) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("states", "The states field is required.");
            }

            if (gettype($this->states) !== "array") {
                $validator
                    ->errors()
                    ->add("states", "states field must be an array.");
            }
            if (
                gettype($this->states) === "array" &&
                count($this->states) > $maxStoreStates
            ) {
                $validator
                    ->errors()
                    ->add(
                        "states",
                        "State field is limited to a maximum of {$maxStoreStates} selection(s)."
                    );
            }
            // validation for states # end

            // validation for author # start
            if (
                ($this->show_author === true && $this->author === null) ||
                ($this->show_author === true &&
                    gettype($this->author) === "array" &&
                    count($this->author) === 0)
            ) {
                $validator
                    ->errors()
                    ->add(
                        "author",
                        "The author field is required, when show author is set to true."
                    );
            }

            if ($this->author !== null && gettype($this->author) !== "array") {
                $validator
                    ->errors()
                    ->add("author", "Author field must be an array.");
            }
            if (
                $this->author !== null &&
                gettype($this->author) === "array" &&
                count($this->author) > $maxAuthors
            ) {
                $validator
                    ->errors()
                    ->add(
                        "author",
                        "Author field is limited to a maximum of {$maxAuthors} selection(s)."
                    );
            }

            // validation for author # end
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
        // cover images
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
        // states
        if (gettype($this->states) === "array") {
            foreach ($this->states as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "states",
                            "The 'ID' for at least one selected state is missing. To resolve this issue, please click the 'Clear form' button and then try again."
                        );
                }
            }
        }
        // categories
        if (gettype($this->categories) === "array") {
            foreach ($this->categories as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "categories",
                            "The 'ID' for at least one selected category is missing. To resolve this issue, please click the 'Clear form' button and then try again."
                        );
                }
            }
        }

        // author
        if ($this->show_author && gettype($this->author) === "array") {
            foreach ($this->author as $item) {
                // $this->show_author === true && $this->author === null
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "author",
                            "The 'ID' for at least one selected person is missing. To resolve this issue, please click the 'Clear form' button and then try again."
                        );
                }
            }
        }
    }
}
