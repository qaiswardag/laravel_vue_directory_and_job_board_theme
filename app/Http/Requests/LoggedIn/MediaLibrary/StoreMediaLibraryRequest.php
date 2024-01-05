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
        $maxImageSize = 2000; //kilobytes
        $maxImageUploads = 7;
        $validator->after(function ($validator) use (
            $maxImageUploads,
            $maxImageSize
        ) {
            if ($this->images === null) {
                $validator
                    ->errors()
                    ->add(
                        "images",
                        "Images is null. No images have been submitted."
                    );
                return;
            }

            if (count($this->images) >= $maxImageUploads) {
                $validator
                    ->errors()
                    ->add(
                        "images",
                        "Max files upload is {$maxImageUploads} per time."
                    );
                return;
            }

            foreach ($this->images as $image) {
                $imageId = $image["image_upload_id"];
                $image = $image["file"];

                if (
                    !$image->isValid() ||
                    !$image->getMimeType() ||
                    !$image->isReadable() ||
                    !$image->isFile() ||
                    !$image->getSize() ||
                    !$image->getClientMimeType() ||
                    !in_array($image->getClientOriginalExtension(), [
                        "jpg",
                        "jpeg",
                        "png",
                        "gif",
                        "webp",
                        // "avif", GD driver is only able to decode JPG, PNG, GIF, BMP or WebP files.
                    ]) ||
                    !$image->isValid()
                ) {
                    $validator
                        ->errors()
                        ->add(
                            $imageId,
                            "File type not allowed. Allowed file types: jpg, jpeg, png, gif, webp."
                        );
                }
                if (
                    $image->getSize() &&
                    $image->getSize() / 1024 >= $maxImageSize
                ) {
                    $validator
                        ->errors()
                        ->add(
                            $imageId,
                            "File size is too big. Maximum file size allowed is" .
                                " " .
                                round($maxImageSize, 0) .
                                " " .
                                "KB" .
                                " " .
                                "/" .
                                " " .
                                round($maxImageSize / 1024, 0) .
                                " " .
                                "MB."
                        );
                }
                if ($image->getSize() === false && $image) {
                    $validator
                        ->errors()
                        ->add(
                            $imageId,
                            "Our servers can not read the file size as it is to big."
                        );
                }
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "Oops! Something went wrong. Please try again."
            );
        }

        // end of withValidator
    }
}
