<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamControllerRequest extends FormRequest
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
        return [
            "name" => ["required", "min:2", "max:255"],
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
        // validate for custom validation
        $validator->after(function ($validator) {
            if (2 == "3") {
                $validator->errors()->add(
                    "name",
                    "Unique custom error for name. But I must explain to you how all this mistaken idea of
                    denouncing pleasure and praising pain was born and I will give you a complete account of the system,
                    and expound the actual teachings."
                );
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "There was an error with your submission. Please make sure all fields are filled in correctly."
            );
        }
    }
}
