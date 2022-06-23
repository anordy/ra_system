<?php

namespace App\Traits\Taxpayer;

use Illuminate\Support\Facades\Http;

trait ApiNIDA {
    public function getNidaDetails($nidaId){
        try {

        } catch (\Exception $exception){
            logger($exception->getMessage());
            return false;
        }
    }
}