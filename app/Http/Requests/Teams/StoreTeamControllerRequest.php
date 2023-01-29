<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

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
        sleep(1);
        $validator->after(function ($validator) {
            if (false) {
                $validator->errors()->add(
                    "name",
                    "Unique custom error for name. But I must explain to you how all this mistaken idea of
                    denouncing pleasure."
                );
            }
        });

        // if validator fails
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "There was an error with your submission. Please make sure all fields are filled in correctly."
                );
        }
    }
}
