<?php

namespace App\Http\Requests\LoggedIn\MediaLibrary;

use Illuminate\Foundation\Http\FormRequest;
use Intervention\Image\ImageManagerStatic as Image;

class StoreMediaLibraryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "images" => ["required"],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (count($this->images) >= 6) {
                $validator->errors()->add("images", "Max files upload is 6.");
                return;
            }

            foreach ($this->images as $image) {
                $imageId = $image["image_upload_id"];
                $image = $image["file"];

                if (
                    $image->getSize() !== false &&
                    $image->getSize() / 1024 >= 2000
                ) {
                    $validator->errors()->add($imageId, "File size is to big.");
                }
                if ($image->getSize() === false) {
                    $validator
                        ->errors()
                        ->add(
                            $imageId,
                            "Our application can not read the file size as it is to big."
                        );
                }
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "There was an error with your submission."
            );
        }

        // end of withValidator
    }
}
