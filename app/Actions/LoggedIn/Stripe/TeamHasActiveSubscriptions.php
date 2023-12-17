<?php

namespace App\Actions\LoggedIn\Stripe;

use Exception;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Laravel\Cashier\Cashier;

class TeamHasActiveSubscriptions
{
    /**
     * Create a newly registered stripe user.
     *
     */
    public function show($team)
    {
        $teamUsers = $team->allUsers();

        $totalsSubscriptionsByUser = [];
        $sumTotalsSubscriptionsByUser = null;

        foreach ($teamUsers as $user) {
            $stripeId = $user->stripe_id;
            $numbersOfActiveSubscriptions = 0;

            $stripeCustomer = Cashier::findBillable($stripeId);

            if ($stripeCustomer) {
                // First, set the current team for subscriptions without a team ID
                $subscriptionsWithoutTeam = $stripeCustomer
                    ->subscriptions()
                    ->whereNull("team_id")
                    ->active()
                    ->get();

                $subscriptionsWithoutTeam->each(function (
                    $subscriptionWithoutTeam
                ) use ($team) {
                    // Update the team_id for subscriptions without a team ID
                    $subscriptionWithoutTeam->team_id = $team->id;
                    // Save the changes to the database
                    $subscriptionWithoutTeam->save();
                });

                $subscriptionsActive = $stripeCustomer
                    ->subscriptions()
                    ->where("team_id", $team->id)
                    ->active()
                    ->get();

                foreach ($subscriptionsActive as $activeSubscription) {
                    // When the subscription is not assigned to any team (for example, when team_id is null), set the team id to the current team.
                    if (!$activeSubscription->team_id) {
                        $activeSubscription->team_id = $team->id;
                        $activeSubscription->save(); // Save the changes to the database
                    }
                    $numbersOfActiveSubscriptions +=
                        $activeSubscription->quantity;
                }
            }

            // Store the total subscriptions for each user
            $totalsSubscriptionsByUser[
                $user->id
            ] = $numbersOfActiveSubscriptions;
        }

        $sumTotalsSubscriptionsByUser = array_sum($totalsSubscriptionsByUser);

        return $sumTotalsSubscriptionsByUser;
    }
}
