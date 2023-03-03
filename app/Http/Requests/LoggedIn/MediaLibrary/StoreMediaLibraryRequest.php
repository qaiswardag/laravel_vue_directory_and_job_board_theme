<?php

namespace App\Http\Requests\LoggedIn\MediaLibrary;

use Illuminate\Foundation\Http\FormRequest;

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
            if ($this->team === null) {
                $validator->errors()->add("team", "Team field is required.");
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
