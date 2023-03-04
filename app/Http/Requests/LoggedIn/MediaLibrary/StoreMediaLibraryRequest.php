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
        // dd($this->team);
        $validator->after(function ($validator) {
            foreach ($this->images as $image) {
                // dd($image);
                if ($image["fileSizeKB"] >= 2000) {
                    $validator
                        ->errors()
                        ->add(
                            $image["imageUploadId"],
                            "File size must not be larger than."
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
