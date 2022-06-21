<?php

namespace App\Traits\Taxpayer;

use Illuminate\Support\Facades\Http;

trait ApiNIDA {
    public function getNidaDetails($nidaId){
        try {
            $response = Http::get('https://jsonplaceholder.typicode.com/users/1');
            return $response->json();

        } catch (\Exception $exception){
            logger($exception->getMessage());
            return false;
        }
    }
}