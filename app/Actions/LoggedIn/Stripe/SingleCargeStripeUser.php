<?php

namespace App\Actions\LoggedIn\Stripe;

use Exception;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;




class SingleCargeStripeUser
{

	/**
	 * Single carge Stripe user
	 *
	 */
	public function createSingleCharge($teamId)
	{
		$user = Auth::user();

		return true;
	}
}
