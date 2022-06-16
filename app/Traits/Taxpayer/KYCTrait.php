<?php

namespace App\Traits\Taxpayer;

use App\Traits\ApiNIDA;
use App\Traits\ImmigrationApi;

trait KYCTrait {

    use ApiNIDA, ImmigrationApi;

    public function updateUser($kyc){
        if ($kyc->identification->name === 'NIDA'){
            $response = $this->getNidaDetails($kyc->id_number);

            if (!$response){
                return false;
            }

            // Update user Details

            return $response;
        } else if ($kyc->identification->name === 'PASSPORT'){
            $response = $this->getImmigrationDetails($kyc->id_number);

            if (!$response){
                return false;
            }

            // Update user Details

            return $response;
        } else {
            return false;
        }
    }
}