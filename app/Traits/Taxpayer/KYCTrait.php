<?php

namespace App\Traits\Taxpayer;

use App\Models\Taxpayer;
use Carbon\Carbon;

trait KYCTrait {

    public function updateUser($kyc){
        if ($kyc->identification->name === 'NIDA'){
            // API Call

            // Check these values

            // Update user Details
            $kyc->authorities_verified_at = Carbon::now()->toDateTimeString();
            $kyc->save();

            return $kyc;
        } else if ($kyc->identification->name === 'PASSPORT'){
            // API Call

            // Check returned values

            // Update user details
            $kyc->authorities_verified_at = Carbon::now()->toDateTimeString();
            $kyc->save();

            return $kyc;
        } else {
            return false;
        }
    }

}