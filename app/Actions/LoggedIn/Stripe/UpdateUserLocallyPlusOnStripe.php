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
				"phone" => $request->phone ?? $user->phone,
				"address" => [
					"country" => $request->country ?? $user->country,
					"city" => $request->city ?? $user->city,
					"state" => $request->state ?? $user->state,
					"line1" => $request->line1 ?? $user->line1,
					"line2" => $request->line2 ?? $user->line2,
					"postal_code" => $request->postal_code ?? $user->postal_code,
				],
			]);

			$user
				->forceFill([
					"country" => $request->country ?? $user->country,
					"city" => $request->city ?? $user->city,
					"state" => $request->state ?? $user->state,
					"line1" => $request->line1 ?? $user->line1,
					"line2" => $request->line2 ?? $user->line2,
					"postal_code" => $request->postal_code ?? $user->postal_code,
					"phone_code" => $request->phone_code ?? $user->phone_code,
					"phone" => $request->phone ?? $user->phone,

					"vat_id" => $request->vat_id ?? $user->vat_id,
					"tax_id" => $request->tax_id ?? $user->tax_id,
					"vat_number" => $request->vat_number ?? $user->vat_number,
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
