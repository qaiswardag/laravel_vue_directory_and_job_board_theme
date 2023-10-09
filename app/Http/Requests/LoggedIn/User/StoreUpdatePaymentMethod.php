<?php

namespace App\Http\Requests\LoggedIn\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class StoreUpdatePaymentMethod extends FormRequest
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
        return [
            "payment_method_id" => ["required", "string", "min:2", "max:255"],
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
        $paymentMethodId = $this->payment_method_id;

        $user = Auth::user();
        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        $defaultPaymentMethodId =
            $stripeCustomer->defaultPaymentMethod()->id ?? null;

        $validator->after(function ($validator) use (
            $paymentMethodId,
            $defaultPaymentMethodId
        ) {
            if ($paymentMethodId === $defaultPaymentMethodId) {
                $validator
                    ->errors()
                    ->add(
                        "payment_method_id",
                        "This payment method is already set as the current default option for payments."
                    );
            }
        });

        // end of withValidator
    }
}
