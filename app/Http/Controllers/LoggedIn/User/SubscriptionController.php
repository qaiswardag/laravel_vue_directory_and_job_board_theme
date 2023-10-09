<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\StoreSubscriptionRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Customer;

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
    public function create()
    {
        $user = Auth::user();

        $name = $user->first_name . " " . $user->last_name;
        $email = $user->email;
        $publishableKey = config("services.stripe.key");

        $intent = null;
        $paymentMethods = null;

        $stripeId = $user->stripe_id;

        if (!$stripeId) {
            $user->createAsStripeCustomer();
        }

        $stripeCustomer = $user->asStripeCustomer();

        // if user is deleted at Stripe
        if ($stripeCustomer->isDeleted()) {
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

            $user->updateStripeCustomer([
                "name" => $name,
                "email" => $email,
            ]);
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
            "Stripe/CreateStoreSubscription/CreateStoreSubscription",
            [
                "intent" => $intent,
                "paymentMethods" => $paymentMethods,
                "publishableKey" => $publishableKey,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $user = User::findOrFail($request->user_id);

        $productId = $request->product_id;
        $name = $request->first_name . " " . $request->last_name;

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        //
        $defaultPaymentMethodId =
            $stripeCustomer->defaultPaymentMethod()->id ?? "";
        //
        //
        //
        if (!$stripeCustomer) {
            // dd("IS NOT CUSTOMER", $stripeCustomer);
            $stripeCustomer = $user->createAsStripeCustomer();
        }

        try {
            $user->updateStripeCustomer([
                "name" => $name,
                "email" => $request->email,
            ]);

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
                // API ID: price_1NwSQ7EuESfVmAWoiDjlkbRQ

                ->newSubscription($productId, "price_1NwSQ7EuESfVmAWoiDjlkbRQ") // signle store
                ->quantity(1)
                ->create($defaultPaymentMethodId);
        } catch (Exception $exception) {
            Log::error(
                "Something went wrong creating the subscription. {$exception}"
            );

            return Inertia::render("Error", [
                "customError" => self::TRY_CATCH_FORM_SUBMISSION_ERROR,
                "status" => 422,
            ]);
        }

        return redirect()->route("stripe.payment.subscription.index", [
            "user" => $user->id,
        ]);
    }
}
