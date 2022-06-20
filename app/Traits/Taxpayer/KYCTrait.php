<?php

namespace App\Traits\Taxpayer;

use App\Models\TaxPayer;
use Carbon\Carbon;

trait KYCTrait {

    use ApiNIDA, ImmigrationApi;

    public function updateUser($kyc){
        if ($kyc->identification->name === 'NIDA'){
            $response = $this->getNidaDetails($kyc->id_number);

            if (!$response){
                return false;
            }

            // Check these values

            // Update user Details
            $kyc->authorities_verified_at = Carbon::now()->toDateTimeString();
            $kyc->save();

            return $response;
        } else if ($kyc->identification->name === 'PASSPORT'){
            $response = $this->getImmigrationDetails($kyc->id_number);

            if (!$response){
                return false;
            }

            // Check returned values

            // Update user details
            $kyc->authorities_verified_at = Carbon::now()->toDateTimeString();
            $kyc->save();

            return $response;
        } else {
            return false;
        }
    }

}