<?php

namespace App\Http\Requests\LoggedIn\Superadmin;

use App\Models\MediaLibrary\MediaLibrary;
use Illuminate\Foundation\Http\FormRequest;

class StorePageBuilderComponentRequest extends FormRequest
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
            "html_code" => ["required", "string", "min:2", "max:65535"],
            "title" => ["required", "string", "min:2", "max:255"],
            "published" => ["boolean"],
            "user_id" => ["required", "integer", "exists:users,id"],
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
        $maxCategories = 2;
        $maxCoverImages = 6;

        $validator->after(function ($validator) use (
            $maxCategories,
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
                count($this->cover_image) > $maxCoverImages
            ) {
                $validator
                    ->errors()
                    ->add(
                        "cover_image",
                        "The field is limited to a maximum of {$maxCoverImages} selection(s)."
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
    }
}
