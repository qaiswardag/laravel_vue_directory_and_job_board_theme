<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Actions\LoggedIn\Stripe\CreateNewStripeUser;
use App\Actions\LoggedIn\Stripe\UpdateUserLocallyPlusOnStripe;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\SingleChargeRequest;
use App\Models\Job\Job;
use App\Models\Post\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;





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
                    "job" => $job,
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

        $productId = $request->product_id;

        $priceProductIdentifierStripe =
            $request->price_product_identifier_stripe;

        $cardId = $request->card_id;




        $updateUserLocallyPlusOnStripe->update($request);


        $user = Auth::user();

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);


        if (!$stripeCustomer) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }

        // TAX # start
        $vat_id = $request->vat_id ?? null;
        $tax_id = $request->tax_id ?? null;
        $vat_number = $request->vat_number ?? null;

        if ($vat_id && $vat_number) {
            try {
                // Retrieve the current tax IDs for the customer
                $currentTaxIds = $stripeCustomer->taxIds();

                // Find and delete the existing tax ID (if it exists)
                foreach ($currentTaxIds as $currentTaxId) {
                    $stripeCustomer->deleteTaxId($currentTaxId->id);
                }

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
            $stripeCharge = $stripeCustomer->charge($priceProductIdentifierStripe, $cardId, [
                'default_tax_rates' => [],
            ]);
        } catch (Exception $e) {
            Log::error("Something went wrong creating the subscription. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong creating the subscription." .
                    " " .
                    $e->getMessage(),
            ]);
        }


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
