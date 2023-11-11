<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Actions\LoggedIn\Stripe\CreateNewStripeUser;
use App\Actions\LoggedIn\Stripe\UpdateUserLocallyPlusOnStripe;
use Exception;
use ErrorException;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\StoreSubscriptionRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Subscription;
use Stripe\Customer;
use Stripe\Error as Stripe;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return Inertia::render("Profile/Subscriptions/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateNewStripeUser $createNewStripeUser)
    {
        try {
            $stripeUserDetails = $createNewStripeUser->create();

            return Inertia::render(
                "Stripe/CreateStoreSubscription/CreateStoreSubscription",
                [
                    "intent" => $stripeUserDetails['intent'],
                    "paymentMethods" => $stripeUserDetails['paymentMethods'],
                    "publishableKey" => $stripeUserDetails['publishableKey'],
                ]
            );
        } catch (Exception $e) {

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_SOMETHING_WENT_WRONG . " " . $e->getMessage(),
                "status" => 422,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request, UpdateUserLocallyPlusOnStripe $updateUserLocallyPlusOnStripe)
    {
        $user = User::findOrFail($request->user_id);

        $productId = $request->product_id;
        $priceProductIdentifierStripe =
            $request->price_product_identifier_stripe;

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        $phoneCodePlusPhone = null;
        $phoneCode = $request->phone_code ?? null;
        $phone = $request->phone ?? null;

        if ($phoneCode && $phone) {
            $phoneCodePlusPhone = $phoneCode . $phone;
        }

        //
        $defaultPaymentMethodId =
            $stripeCustomer->defaultPaymentMethod()->id ?? "";

        //
        if (!$stripeCustomer) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }

        $vat_id = $request->vat_id ?? null;
        $tax_id = $request->tax_id ?? null;
        $vat_number = $request->vat_number ?? null;

        // TAX # start
        if ($vat_id && $vat_number) {
            try {
                // tax id is not null
                if ($tax_id) {
                    $stripeCustomer->createTaxId(
                        $tax_id,
                        $tax_id === "eu_vat"
                            ? $vat_id . $vat_number
                            : $vat_number
                    );
                }

                // tax id null
                if (!$tax_id) {
                    $stripeCustomer->createTaxId($vat_id, $vat_number);
                }
            } catch (Exception $e) {
                throw ValidationException::withMessages([
                    "vat_id" =>
                    "Oops! We were unable to process the VAT number." .
                        " " .
                        $e->getMessage(),
                    "vat_number" =>
                    "Oops! We were unable to process the VAT number." .
                        " " .
                        $e->getMessage(),
                ]);
            }
        }

        // TAX # end
        try {

            $updateUserLocallyPlusOnStripe->update($request);

            $stripeCustomer
                // The first argument passed to the newSubscription method
                // should be the internal name of the subscription.
                // This subscription name is only for internal application usage
                // and is not meant to be shown to users. In addition,
                // it should not contain spaces and it should never be changed
                // after creating the subscription.

                // The second argument is the specific price the user is subscribing to.
                // This value should correspond to the price's identifier in Stripe.

                // STRIPE:
                // Price for Single Store
                // Every 3 months / US$20.00 / Tax behaviour Inclusive

                ->newSubscription($productId, $priceProductIdentifierStripe)
                ->quantity(1)
                ->create($defaultPaymentMethodId);
        } catch (Exception $e) {
            Log::error("Something went wrong creating the subscription. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong creating the subscription." .
                    " " .
                    $e->getMessage(),
            ]);
        }

        return redirect()->route("stripe.payment.subscription.index", [
            "user" => $user->id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($subscriptionId)
    {
        $user = Auth::user();

        $subscription = Subscription::findOrFail($subscriptionId);
        $publishableKey = config("services.stripe.key");

        $intent = null;
        $paymentMethods = null;

        $stripeId = $user->stripe_id;

        if (!$stripeId) {
            $user->createAsStripeCustomer();
        }

        $stripeCustomer = $user->asStripeCustomer();

        // if user is deleted at Stripe
        if ($stripeCustomer && $stripeCustomer->isDeleted()) {
            $user
                ->forceFill([
                    "stripe_id" => null,
                    "trial_ends_at" => null,
                    "pm_type" => null,
                    "pm_last_four" => null,
                ])
                ->save();

            $user->createAsStripeCustomer();
        }

        try {
            $intent = $user->createSetupIntent();
            $paymentMethods = $user->paymentMethods();
        } catch (Exception $exception) {
            Log::error(
                "Something went wrong loading the subscription form. {$exception}"
            );

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_LOAD_FORM_ERROR,
                "status" => 422,
            ]);
        }

        return Inertia::render(
            "Stripe/UpdateStoreSubscription/UpdateStoreSubscription",
            [
                "intent" => $intent,
                "paymentMethods" => $paymentMethods,
                "publishableKey" => $publishableKey,
                "post" => $subscription,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubscriptionRequest $request, $subscriptionId, UpdateUserLocallyPlusOnStripe $updateUserLocallyPlusOnStripe)
    {
        $user = Auth::user();
        $newProductId = $request->product_id;

        $newPriceProductIdentifierStripe =
            $request->price_product_identifier_stripe;

        $subscription = Subscription::findOrFail($subscriptionId);

        try {
            $updateUserLocallyPlusOnStripe->update($request);

            $subscription->swap($newPriceProductIdentifierStripe);

            // Update the product name, this won't affect Stripe
            // it's only for internal database records
            $subscription->update([
                "updated_subscription_name" => $newProductId,
            ]);
        } catch (Exception $e) {
            Log::error("Something went wrong updating the subscription. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong updating the subscription." .
                    " " .
                    $e->getMessage(),
            ]);
        }

        return redirect()->route("stripe.payment.subscription.index", [
            "user" => $user->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function resume($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);

        $stripeSubscription = $subscription->asStripeSubscription();

        $subscription = Subscription::findOrFail($subscriptionId);

        try {
            $subscription->resume();
        } catch (Exception $e) {
            Log::error("Something went wrong. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong." . " " . $e->getMessage(),
            ]);
        }

        return back()->with([
            "message" => "The form has been submitted successfully.",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($subscriptionId)
    {
        $user = Auth::user();

        $subscription = Subscription::findOrFail($subscriptionId);

        $stripeSubscription = $subscription->asStripeSubscription();

        $subscription = Subscription::findOrFail($subscriptionId);

        try {
            $subscription->cancel();
            // $subscription->cancelAt(now()->addMinutes(3));
        } catch (Exception $e) {
            Log::error("Something went wrong. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong." . " " . $e->getMessage(),
            ]);
        }

        return back()->with([
            "message" => "The form has been submitted successfully.",
        ]);
    }
}
