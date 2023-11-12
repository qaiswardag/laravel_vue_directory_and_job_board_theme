<?php

namespace App\Actions\LoggedIn\Stripe;

use App\Models\Team;
use Exception;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;




class singleChargeStripeUser
{

	/**
	 * Single carge Stripe user
	 *
	 */
	public function createSingleCharge($team)
	{

		$user = Auth::user();

		return true;
	}
}
