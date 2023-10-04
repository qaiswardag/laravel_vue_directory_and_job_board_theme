<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Stripe;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\Subscription; // Assuming your Subscription model is in the "Models" namespace inside the "app" directory.

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $subscriptionsActive = $user
            ->subscriptions()
            ->active()
            ->get();

        $subscriptionsIncomplete = $user
            ->subscriptions()
            ->incomplete()
            ->get();

        $subscriptionsEnded = $user
            ->subscriptions()
            ->ended()
            ->get();

        $subscriptionsCanceled = $user
            ->subscriptions()
            ->canceled()
            ->get();

        return [
            "subscriptionsActive" => $subscriptionsActive,
            "subscriptionsIncomplete" => $subscriptionsIncomplete,
            "subscriptionsEnded" => $subscriptionsEnded,
            "subscriptionsCanceled" => $subscriptionsCanceled,
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
