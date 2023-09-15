<?php

namespace App\Http\Requests\LoggedIn\Job;

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
        dd("se om denne text bliver vist!");
        $rules = [
            "published" => ["boolean"],
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

            "tags" => ["required", "string", "max:255"],
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
        $maxJobCountries = 1;
        $maxJobStates = 2;
        $maxCategories = 4;
        $maxJobTypes = 4;
        $maxCoverImages = 3;

        $validator->after(function ($validator) use (
            $maxAuthors,
            $maxJobCountries,
            $maxJobStates,
            $maxCategories,
            $maxJobTypes,
            $maxCoverImages
        ) {
            if ($this->team === null) {
                $validator
                    ->errors()
                    ->add("team", "The team field is required.");
            }

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
                count($this->cover_image) > $maxCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "Limited to a maximum of {$maxCoverImages} cover images."
                    );
            }
            // validation for cover image # end

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
                        "Limited to a maximum of {$maxJobCountries} countries."
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
                        "Limited to a maximum of {$maxJobStates} states."
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
                        "      Limited to a maximum of {$maxAuthors} people."
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
                        "Limited to a maximum of {$maxCategories} categories."
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
                        "Limited to a maximum of {$maxJobTypes} types."
                    );
            }
            // validation for types # end

            // if validator fails
            if ($validator->fails()) {
                return back()->with(
                    "error",
                    "Please complete all fields correctly to proceed."
                );
            }
        });

        // end of withValidator
    }
}
