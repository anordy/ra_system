<?php

namespace App\Traits\Taxpayer;

use App\Models\Taxpayer;
use Carbon\Carbon;

trait KYCTrait {

    public function updateUser($kyc){
        $kyc->authorities_verified_at = Carbon::now()->toDateTimeString();
        $kyc->save();
        return $kyc;
    }

}