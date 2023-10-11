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
            "phone" => ["required", "string", "min:2", "max:255", "nullable"],
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
