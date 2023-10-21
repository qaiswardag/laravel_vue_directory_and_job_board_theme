<?php

namespace App\Http\Controllers\Api\Internal\LoggedIn\Stripe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $stripeId = $user->stripe_id;

        $stripeCustomer = Cashier::findBillable($stripeId);

        $payments = [];

        if ($stripeCustomer) {
            $invoices = $stripeCustomer->invoices();

            $payments = [
                "invoices" => $invoices,
            ];
        }

        return [
            "payments" => $payments,
        ];
    }
}
