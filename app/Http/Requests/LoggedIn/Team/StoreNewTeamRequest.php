<?php

namespace App\Http\Requests\LoggedIn\Team;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewTeamRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "name" => ["required", "string", "min:2", "max:255"],
            "public" => ["boolean"],

            "slug" => ["required", "string", "min:2", "max:255"],
            // "slug" => [
            //     "required",
            //     Rule::unique("teams"),
            //     "string",
            //     "min:4",
            //     "max:60",
            //     "regex:/^[a-zA-Z0-9_]+$/u",

            //     // regular expression `/^[a-zA-Z0-9_]+$/u`
            //     // will only allow usernames that contain only alphanumeric characters
            //     // (letters A-Z, numbers 0-9) and underscores. The + sign after the character
            //     // set [] means that one or more of these characters can be present
            //     // in the string, and the /u flag at the end indicates that the regular
            //     // expression is "unicode-safe," meaning it can handle non-ASCII characters.

            //     // By using above regular expression, you're effectively preventing
            //     // any special characters like !, @, ., or even the Danish characters

            //     // twitter rules
            //     // Twitter states that usernames can only contain alphanumeric characters
            //     // (letters A-Z, numbers 0-9) and underscores, and must be between
            //     // 4-20 characters in length.

            //     // These characters can be used in various attack techniques, including SQL injection, cross-site scripting (XSS), and command injection attacks, among others. It's important to properly validate and sanitize user input to prevent these types of attacks.
            //     // ; (semicolon)
            //     // ' (single quote)
            //     // " (double quote)
            //     // < (less than)
            //     // > (greater than)
            //     // / (slash)
            //     // % (percent)
            //     // ` (backtick)
            //     // & (ampersand)
            //     // | (pipe)
            //     // $ (dollar sign)
            // ],
            //
            //
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
        if ($validator->fails()) {
            return back()->with(
                "error",
                "Please complete all fields correctly to proceed."
            );
        }

        // end of withValidator
    }
}
