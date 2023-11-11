<?php

namespace App\Actions\LoggedIn\Stripe;

use Exception;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;




class UpdateUserLocallyPlusOnStripe
{
	/**
	 * Create a newly registered stripe user.
	 *
	 */
	public function update($request)
	{

		$user = Auth::user();

		try {
			$user->updateStripeCustomer([
				"name" => $user->first_name . " " . $user->last_name,
				"email" => $user->email,
				"phone" => $request->phone ?? null,
				"address" => [
					"country" => $request->country ?? null,
					"city" => $request->city ?? null,
					"state" => $request->state ?? null,
					"line1" => $request->line1 ?? null,
					"line2" => $request->line2 ?? null,
					"postal_code" => $request->postal_code ?? null,
				],
			]);

			$user
				->forceFill([
					"country" => $request->country ?? null,
					"city" => $request->city ?? null,
					"state" => $request->state ?? null,
					"line1" => $request->line1 ?? null,
					"line2" => $request->line2 ?? null,
					"postal_code" => $request->postal_code ?? null,
					"phone_code" => $request->phone_code ?? null,
					"phone" => $request->phone ?? null,

					"vat_id" => $request->vat_id ?? null,
					"tax_id" => $request->tax_id ?? null,
					"vat_number" => $request->vat_number ?? null,
				])
				->save();
		} catch (Exception $e) {
			Log::error(
				"Oops! Something went wrong. {$e->getMessage()}."
			);
			throw new Exception("Oops! Something went wrong.");
		}
	}
}
