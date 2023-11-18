<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Actions\LoggedIn\Stripe\CreateOrGetStripeCustomer;
use App\Actions\LoggedIn\Stripe\CreateStripeUserTaxID;
use App\Actions\LoggedIn\Stripe\UpdateUserLocallyPlusOnStripe;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\StoreChargeFormRequest;
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
use Illuminate\Support\Carbon;






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
    public function create(Job $job, CreateOrGetStripeCustomer $createOrGetStripeCustomer)
    {
        try {
            $stripeUserDetails = $createOrGetStripeCustomer->create();

            return Inertia::render(
                "Stripe/CreateSingleChargeJob/CreateSingleChargeJob",
                [
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
        StoreChargeFormRequest $request,
        Job $job,
        UpdateUserLocallyPlusOnStripe $updateUserLocallyPlusOnStripe,
        CreateStripeUserTaxID $createStripeUserTaxID
    ) {

        $productId = $request->product_id; // single_job_prost

        $priceIdentifierStripe = $request->price_identifier_stripe;

        $updateUserLocallyPlusOnStripe->update($request);

        $user = Auth::user();

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        if (!$stripeCustomer) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }

        // TAX # start
        $vat_id = $request->vat_id ?? null;

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

        // charge # start
        try {
            $stripeCustomer->invoicePrice($priceIdentifierStripe);

            $job->update([
                "is_paid" => true,
                "paid_at" => Carbon::now(),
                "published" => $request->published,
            ]);

            // Return the current team that the user is on, rather than the team that the user is storing the post for.
            $currentTeam = Auth::user()->currentTeam;

            return redirect()->route($request->next_route_name, [
                "teamId" => $currentTeam->id
            ]);
        } catch (Exception $e) {
            Log::error("Something went wrong creating the payment. {$e}");

            throw ValidationException::withMessages([
                "error" =>
                "Oops! Something went wrong creating the payment." .
                    " " .
                    $e->getMessage(),
            ]);
            // charge end
        }
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
