<?php

namespace App\Traits\Taxpayer;

use Illuminate\Support\Facades\Http;

trait ImmigrationApi {
    public function getImmigrationDetails($immigrationId){
        try {

        } catch (\Exception $exception){
            logger($exception->getMessage());
            return false;
        }
    }
}