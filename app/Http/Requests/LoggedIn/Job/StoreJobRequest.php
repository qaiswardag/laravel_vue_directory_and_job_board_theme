<?php

namespace App\Http\Requests\LoggedIn\Job;

use App\Models\MediaLibrary\MediaLibrary;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StoreJobRequest extends FormRequest
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
        // dd($this->started_at);
        // dd(Carbon::parse($this->started_at)->diffForHumans());
        $rules = [
            "published" => ["boolean"],
            "is_filled" => ["boolean"],
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

            "tags" => ["nullable", "min:2", "string", "max:255"],

            "started_at" => ["required", "date_format:Y-m-d H:i:s"],
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
        $maxJobCountries = 2;
        $maxJobStates = 2;
        $maxCategories = 2;
        $maxJobTypes = 2;
        $minCoverImages = 1;
        $maxCoverImages = 6;

        $validator->after(function ($validator) use (
            $maxAuthors,
            $maxJobCountries,
            $maxJobStates,
            $maxCategories,
            $maxJobTypes,
            $minCoverImages,
            $maxCoverImages
        ) {
            if ($this->team === null) {
                $validator
                    ->errors()
                    ->add("team", "The team field is required.");
            }

            $this->validateProperties($validator);

            // The Started at date must be in the future
            if (
                $this->started_at &&
                Carbon::parse($this->started_at)->isValid() &&
                Carbon::parse($this->started_at)->isPast() &&
                Carbon::parse($this->started_at)->diffInDays(
                    Carbon::now()->subDay()
                ) > 0
            ) {
                $validator
                    ->errors()
                    ->add(
                        "started_at",
                        "The started at date must be in the future."
                    );
            }

            // The started at date must not be more than 1 year in the future.
            if (
                $this->started_at &&
                Carbon::parse($this->started_at)->isFuture() &&
                Carbon::parse($this->started_at)->diffInDays(Carbon::now()) >
                365
            ) {
                $validator
                    ->errors()
                    ->add(
                        "started_at",
                        "The started at date must not be more than 1 year in the future."
                    );
            }
            // started at date # end

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
                        "At least {$minCoverImages} images are necessary for a store listing."
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
                // Loop through the array and attach each category to the job
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

            // validation for countries # start
            if (
                $this->countries === null ||
                (gettype($this->countries) === "array" &&
                    count($this->countries) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("countries", "The countries field is required.");
            }

            if (gettype($this->countries) !== "array") {
                $validator
                    ->errors()
                    ->add("countries", "countries field must be an array.");
            }
            if (
                gettype($this->countries) === "array" &&
                count($this->countries) > $maxJobCountries
            ) {
                $validator
                    ->errors()
                    ->add(
                        "countries",
                        "Countries field is limited to a maximum of {$maxJobCountries} selection(s)."
                    );
            }
            // validation for countries # end

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
                count($this->states) > $maxJobStates
            ) {
                $validator
                    ->errors()
                    ->add(
                        "states",
                        "States field is limited to a maximum of {$maxJobStates} selection(s)."
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
                        "Categories field is limited to a maximum of {$maxCategories} selection(s)."
                    );
            }
            // validation for categories # end

            // validation for types # start
            if (
                $this->types === null ||
                (gettype($this->types) === "array" && count($this->types) === 0)
            ) {
                $validator
                    ->errors()
                    ->add("types", "The types field is required.");
            }

            if (gettype($this->types) !== "array") {
                $validator
                    ->errors()
                    ->add("types", "types field must be an array.");
            }
            if (
                gettype($this->types) === "array" &&
                count($this->types) > $maxJobTypes
            ) {
                $validator
                    ->errors()
                    ->add(
                        "types",
                        "Job types field is limited to a maximum of {$maxJobTypes} selection(s)."
                    );
            }
            // validation for types # end
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
        // countries
        if (gettype($this->countries) === "array") {
            foreach ($this->countries as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "countries",
                            "The 'ID' for at least one selected country is missing. To resolve this issue, please click the 'Clear form' button and then try again."
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
        // types
        if (gettype($this->types) === "array") {
            foreach ($this->types as $item) {
                if (isset($item["id"]) === false) {
                    $validator
                        ->errors()
                        ->add(
                            "types",
                            "The 'ID' for at least one selected job type is missing. To resolve this issue, please click the 'Clear form' button and then try again."
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
