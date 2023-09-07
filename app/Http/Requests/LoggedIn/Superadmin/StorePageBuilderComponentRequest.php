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
        return [
            "html_code" => ["required", "string", "min:2", "max:65535"],
            "title" => ["required", "string", "min:2", "max:255"],
            "cover_image_original" => ["required", "string", "max:255"],
        ];
    }
}
