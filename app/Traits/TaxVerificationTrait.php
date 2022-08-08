<?php

namespace App\Traits;

use Exception;
use App\Models\Verification\TaxVerification;
use Illuminate\Support\Facades\Log;

trait TaxVerificationTrait
{

    /**
     * Trigger audit.
     *
     * @param  $modal_class ie. User::class, Business::class
     * @param  $event ie. created, updated, deleted
     * @param  $tags ie. Password
     * @param  $auditable_id ie. 1 id of the operated process
     * 
     * @return array
     */
    public function triggerTaxVerifications($taxReturn, $authenticatedUser)
    {
        if ($taxReturn == null || $authenticatedUser == null) {
            throw new Exception('Return Object or Authenticated User Object is null');
        } else {

            $data = [
                'tax_return_id' => $taxReturn->id ?? '',
                'tax_return_type' => get_class($taxReturn),
                'business_id' => $taxReturn->business_id,
                'location_id' => $taxReturn->location_id,
                'tax_type_id' => $taxReturn->tax_type_id,
                'created_by_id' => $authenticatedUser->id ?? null,
                'created_by_type' => get_class($authenticatedUser),
            ];

            try {
                TaxVerification::create($data);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }
}
