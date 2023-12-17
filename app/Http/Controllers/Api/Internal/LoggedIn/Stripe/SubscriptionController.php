<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Stripe;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\Subscription;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $subscriptionsActive = null;
        $subscriptionsIncomplete = null;
        $subscriptionsEnded = null;
        $subscriptionsCanceled = null;

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        $subscriptions = [];

        if ($stripeCustomer) {
            $subscriptionsActive = $stripeCustomer
                ->subscriptions()
                ->active()
                ->get();

            // Iterate through subscriptions and find team for each
            foreach ($subscriptionsActive as $subscription) {
                $teamId = $subscription->team_id;

                // Use find() to retrieve the team based on team_id
                $team = Team::find($teamId);

                // Attach the found team to the subscription
                $subscription->team = $team;
            }

            $subscriptionsIncomplete = $stripeCustomer
                ->subscriptions()
                ->incomplete()
                ->get();

            $subscriptionsEnded = $stripeCustomer
                ->subscriptions()
                ->ended()
                ->get();

            $subscriptionsCanceled = $stripeCustomer
                ->subscriptions()
                ->canceled()
                ->get();

            $subscriptions = [
                "subscriptionsActive" => $subscriptionsActive,
                "subscriptionsIncomplete" => $subscriptionsIncomplete,
                "subscriptionsEnded" => $subscriptionsEnded,
                "subscriptionsCanceled" => $subscriptionsCanceled,
            ];
        }

        return [
            "subscriptions" => $subscriptions,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
