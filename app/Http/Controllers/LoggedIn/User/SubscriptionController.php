<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\StoreSubscriptionRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;

class SubscriptionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user, Team $team)
    {
        $this->authorize("can-create-and-update", $team);

        $paymentMethods = $user->paymentMethods();

        return Inertia::render("Profile/CreatePayment/CreatePayment", [
            "team" => $team->load("owner"),
            "intent" => $user->createSetupIntent(),
            "paymentMethods" => $paymentMethods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request)
    {
        dd("request er", $request);
        //
        //
        //
        //
        //
        $paymentMethod = $request->payment_method;
        sleep(1);
        $team = Team::findOrFail($request->team["id"]);
        $user = User::findOrFail($request->user_id);

        $productId = $request->product_id;

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        if (!$stripeId) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }

        $user->updateStripeCustomer([
            "name" => $user->first_name . " " . $user->last_name,
            // "address" => [
            //     "postal_code" => "2650",
            //     "country" => "DK",
            // ],
        ]);

        $this->authorize("can-create-and-update", $team);

        $user
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
            ->create($paymentMethod);
    }
}
