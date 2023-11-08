<?php

namespace App\Actions\LoggedIn\Stripe;

use Exception;
use ErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;




class CreateNewStripeUser
{

	/**
	 * Create a newly registered stripe user.
	 *
	 */
	public function create()
	{
		$user = Auth::user();

		$name = $user->first_name . " " . $user->last_name;
		$email = $user->email;
		$country = $user->country;
		$city = $user->city;

		$state = $user->state;
		$line1 = $user->line1;
		$line2 = $user->line2;

		$postalCode = $user->postal_code;
		$phoneCode = $user->phone_code;
		$phone = $user->phone;
		$publishableKey = config("services.stripe.key");

		$intent = null;
		$paymentMethods = null;

		$stripeId = $user->stripe_id;

		if (!$stripeId) {
			$user->createAsStripeCustomer();
		}

		$stripeCustomer = $user->asStripeCustomer();

		// if user is deleted at Stripe
		// if ($stripeCustomer && $stripeCustomer->isDeleted()) {
		// 	$user
		// 		->forceFill([
		// 			"stripe_id" => null,
		// 			"trial_ends_at" => null,
		// 			"pm_type" => null,
		// 			"pm_last_four" => null,
		// 		])
		// 		->save();

		// 	$user->createAsStripeCustomer();
		// }


		try {
			$intent = $user->createSetupIntent();
			$paymentMethods = $user->paymentMethods();

			$user->updateStripeCustomer([
				"name" => $name,
				"email" => $email,
				"phone" => $phone,
				"address" => [
					"country" => $country,
					"city" => $city,
					"state" => $state,
					"line1" => $line1,
					"line2" => $line2,
					"postal_code" => $postalCode,
				],
			]);


			return [
				'intent' => $intent,
				'paymentMethods' => $paymentMethods,
				'publishableKey' => config('services.stripe.key'),
			];
		} catch (Exception $e) {
			Log::error(
				"Oops! Something went wrong. {$e->getMessage()}."
			);
			throw new Exception("
			Unable to complete the setup process.");
		}
	}
}
