<?php

namespace Database\Seeders;

use App\Models\PublicService\PublicServicePaymentInterval;
use Illuminate\Database\Seeder;

class PublicServicePaymentsIntervalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PublicServicePaymentInterval::updateOrCreate(['value' => 3, 'type' => 'months']);
        PublicServicePaymentInterval::updateOrCreate(['value' => 6, 'type' => 'months']);
        PublicServicePaymentInterval::updateOrCreate(['value' => 12, 'type' => 'months']);
    }
}
