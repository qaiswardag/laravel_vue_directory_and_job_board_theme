<?php

namespace App\Http\Requests\LoggedIn\User;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodsRequest extends FormRequest
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
            "name" => ["required", "string", "min:2", "max:255"],
            "email" => ["required", "email", "max:255"],
            "country" => ["string", "max:255", "nullable"],
            "city" => ["string", "max:255", "nullable"],
            "postal_code" => ["string", "max:255", "nullable"],
        ];

        return $rules;
    }
}
