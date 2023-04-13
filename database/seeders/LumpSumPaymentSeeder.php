<?php

namespace Database\Seeders;

use App\Models\LumpSumPayment;
use Illuminate\Database\Seeder;

class LumpSumPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LumpSumPayment::updateOrCreate([
            'id' => 1,
            'filed_by_id' => 1,
            'BUSINESS_ID' => 1,
            'BUSINESS_LOCATION_ID' => 1,
            'ANNUAL_ESTIMATE' => 1000000,
            'PAYMENT_QUARTERS' => 4,
            'CURRENCY' => 'tzs',
        ]);

        LumpSumPayment::updateOrCreate([
            'id' => 2,
            'filed_by_id' => 1,
            'BUSINESS_ID' => 1,
            'BUSINESS_LOCATION_ID' => 2,
            'ANNUAL_ESTIMATE' => 250000,
            'PAYMENT_QUARTERS' => 4,
            'CURRENCY' => 'tzs',
        ]);

    }
}
