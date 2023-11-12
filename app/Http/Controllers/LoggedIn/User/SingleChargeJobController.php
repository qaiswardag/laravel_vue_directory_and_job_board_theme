<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Actions\LoggedIn\Stripe\CreateNewStripeUser;
use App\Actions\LoggedIn\Stripe\UpdateUserLocallyPlusOnStripe;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\SingleChargeRequest;
use App\Models\Job\Job;
use App\Models\Post\Post;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;


class SingleChargeJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team, Job $job, CreateNewStripeUser $createNewStripeUser)
    {


        try {
            $stripeUserDetails = $createNewStripeUser->create();

            return Inertia::render(
                "Stripe/CreateSingleChargeJob/CreateSingleChargeJob",
                [
                    "teamId" => $team->id,
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
     */
    public function store(SingleChargeRequest $request, Team $team, UpdateUserLocallyPlusOnStripe $updateUserLocallyPlusOnStripe)
    {

        $updateUserLocallyPlusOnStripe->update($request);

        return redirect()->route($request->next_route_name, [
            "teamId" => $team->id
        ]);
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
