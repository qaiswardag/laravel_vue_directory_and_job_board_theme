<?php

namespace App\Http\Requests\LoggedIn\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

use Validator;

class StoreSubscriptionRequest extends FormRequest
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
            "product_id" => ["required", "string", "min:2", "max:255"],
            "country" => ["required", "string", "min:2", "max:255", "nullable"],
            "city" => ["required", "string", "min:2", "max:255", "nullable"],
            "postal_code" => [
                "required",
                "string",
                "min:2",
                "max:255",
                "nullable",
            ],
            "phone" => ["integer", "digits_between:4,16", "nullable"],
            "phone_code" => ["regex:/^\d{1,8}(-\d{1,8})?$/", "nullable"],

            "vat_id" => ["string", "min:2", "max:255", "nullable"],
            "vat_number" => ["string", "min:2", "max:255", "nullable"],
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
        $noDefaultPaymentMethodError =
            "Oops! No default payment method has been selected. Please select a default payment method.";

        $user = Auth::user();

        $stripeId = $user->stripe_id;
        $stripeCustomer = Cashier::findBillable($stripeId);

        $defaultPaymentMethodId =
            $stripeCustomer->defaultPaymentMethod()->id ?? null;

        $paymentMethods = $stripeCustomer->paymentMethods();

        $validator->after(function ($validator) use (
            $noDefaultPaymentMethodError,
            $defaultPaymentMethodId,
            $paymentMethods
        ) {
            if (!$defaultPaymentMethodId) {
                $validator
                    ->errors()
                    ->add("payment_method_id", $noDefaultPaymentMethodError);
            }
            //
            //
            if (!$paymentMethods) {
                $validator
                    ->errors()
                    ->add("payment_method_id", $noDefaultPaymentMethodError);
            }

            //
            //
            if ($paymentMethods) {
                $validPaymentMethod = false;

                foreach ($paymentMethods as $paymentMethod) {
                    if ($paymentMethod->id === $defaultPaymentMethodId) {
                        $validPaymentMethod = true;
                        break;
                    }
                }

                if (!$validPaymentMethod) {
                    $validator
                        ->errors()
                        ->add(
                            "payment_method_id",
                            $noDefaultPaymentMethodError
                        );
                }

                // logic for phone and phone country code # start
                if ($this->phone && !$this->phone_code) {
                    $validator->errors()->add(
                        "phone_code",
                        "Phone country code is required when phone number is set.
    
                    "
                    );
                }

                if ($this->phone_code && !$this->phone) {
                    $validator->errors()->add(
                        "phone",
                        "Phone number is required when phone_code country code is set.
    
                    "
                    );
                }
                // logic for phone and phone country code # end
                // logic for vat id and vat number # start
                if ($this->vat_id && !$this->vat_number) {
                    $validator->errors()->add(
                        "vat_number",
                        "Vat number is required when vat id is set.
    
                    "
                    );
                }

                if ($this->vat_number && !$this->vat_id) {
                    $validator
                        ->errors()
                        ->add(
                            "vat_id",
                            "Vat id is required when vat number is set."
                        );
                }
                // logic for vat id and vat number # end
            }
        });

        //
        //

        // if validator fails
        if ($validator->fails()) {
            return back()->with(
                "error",
                "Please complete all fields correctly to proceed."
            );
        }

        // end of withValidator
    }
}
