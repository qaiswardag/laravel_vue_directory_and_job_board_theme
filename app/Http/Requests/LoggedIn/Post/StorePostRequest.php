<?php

namespace App\Http\Requests\LoggedIn\Post;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // dd($this);
        return [
            "published" => [""],
            "title" => ["required", "min:2", "max:255"],
            "slug" => ["required", "min:2", "max:255"],
            "user_id" => ["required"],
            "team" => ["required"],
            "content" => ["required", "min:2", "max:255"],
            "post_cover_image" => ["required"],
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
            if ($this->team === null) {
                $validator->errors()->add("team", "Team field is required.");
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "There was an error with your submission. Please make sure all fields are filled in correctly."
            );
        }

        // end of withValidator
    }
}
