<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Actions\LoggedIn\Stripe\CreateNewStripeUser;
use App\Actions\LoggedIn\Stripe\CreateStripeUserTaxID;
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
    public function store(
        SingleChargeRequest $request,
        Team $team,
        UpdateUserLocallyPlusOnStripe $updateUserLocallyPlusOnStripe,
        CreateStripeUserTaxID $createStripeUserTaxID
    ) {

        $productId = $request->product_id; // single_job_prost

        $priceIdentifierStripe = $request->price_identifier_stripe;
        $chargeableAmountInteger = $request->chargeable_amount_integer;
        dd($chargeableAmountInteger);

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
        $vat_number = $request->vat_number ?? null;
        // TAX # end

        try {
            if ($vat_id && $vat_number) {
                $createStripeUserTaxID->create($stripeCustomer, $request);
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
        // TAX # end

        try {
            // $stripeCharge = $stripeCustomer->charge($chargeableAmountInteger, $cardId, [
            //     'default_tax_rates' => [],
            // ]);

            //
            //
            //
            //
            //
            $stripeCharge = $stripeCustomer->charge([
                'amount' => $chargeableAmountInteger, // or any other amount in cents
                'currency' => 'usd',
                'source' => $cardId,
                'description' => 'Charge for Your Product Name',
                'product' => $priceIdentifierStripe, // Use the product identifier here
                'quantity' => 1, // You can adjust the quantity if needed
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
