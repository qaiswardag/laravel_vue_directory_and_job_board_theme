<?php

namespace App\Http\Requests\LoggedIn\Superadmin;

use Illuminate\Foundation\Http\FormRequest;

class StorePageBuilderComponentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        dd("er:", $this);
        $rules = [
            "html_code" => ["required", "string", "min:2", "max:65535"],
            "title" => ["required", "string", "min:2", "max:255"],
            "cover_image_original" => ["required", "string", "max:255"],
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
        $maxCategories = 5;

        $validator->after(function ($validator) use ($maxCategories) {
            if ($this->team === null) {
                $validator
                    ->errors()
                    ->add("team", "The team field is required.");
            }

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
}
