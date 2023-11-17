<?php

namespace App\Http\Controllers\LoggedIn\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoggedIn\User\StoreDestroyPaymentMethod;
use App\Http\Requests\LoggedIn\User\StorePaymentMethodsRequest;
use App\Http\Requests\LoggedIn\User\StoreUpdatePaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Facades\Validator;

class PaymentMethodsController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodsRequest $request)
    {
        $user = User::findOrFail($request->user_id);
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
    public function updateIfSinglePatmentMethod()
    {
        $user = Auth::user();

        $stripeId = $user->stripe_id;
        $stripeCustomer = Cashier::findBillable($stripeId);

        $paymentMethods = $stripeCustomer->paymentMethods();

        $stripeCustomer->updateDefaultPaymentMethod(
            $paymentMethods->first()->id
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePaymentMethod $request)
    {
        $user = Auth::user();

        $stripeId = $user->stripe_id;
        $stripeCustomer = Cashier::findBillable($stripeId);

        $stripeCustomer->updateDefaultPaymentMethod(
            $request->payment_method_id
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreDestroyPaymentMethod $request)
    {
        $paymentMethodId = $request->payment_method_id;

        $user = Auth::user();

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        $stripeCustomer->deletePaymentMethod($paymentMethodId);
    }
}
