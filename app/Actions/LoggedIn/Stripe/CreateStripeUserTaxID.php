<?php

namespace App\Actions\LoggedIn\Stripe;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;



class CreateStripeUserTaxID
{
	/**
	 * Create Stripe user tax id
	 *
	 */
	public function create($stripeCustomer, $request)
	{
		// TAX # start
		$vat_id = $request->vat_id ?? null;
		$tax_id = $request->tax_id ?? null;
		$vat_number = $request->vat_number ?? null;

		try {
			// Retrieve the current tax IDs for the customer
			$currentTaxIds = $stripeCustomer->taxIds();

			if (count($currentTaxIds) !== 0) {
				// Find and delete the existing tax ID (if it exists)
				foreach ($currentTaxIds as $currentTaxId) {
					if ($currentTaxId->value !== $vat_id . $vat_number) {
						$stripeCustomer->deleteTaxId($currentTaxId->id);

						//
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
					}
				}
			}


			if (count($currentTaxIds) === 0) {
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
			}
		} catch (Exception $e) {
			Log::error(
				"Oops! Something went wrong. {$e->getMessage()}"
			);
			throw new Exception($e->getMessage());
		}
	}
}
