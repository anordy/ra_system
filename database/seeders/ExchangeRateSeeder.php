<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $exchange_rates = [
            [
                'currency' => 'USD',
                'mean' => '2350',
                'spot_buying' => '2293.1683',
                'spot_selling' => '2316.1',
                'is_approved' => 1,
                'exchange_date' => Carbon::now()->toDateString(),
            ],
            [
                'currency' => 'TZS',
                'mean' => '1',
                'spot_buying' => '0.5',
                'spot_selling' => '0.5',
                'is_approved' => 1,
                'exchange_date' => Carbon::now()->toDateString(),
            ],
        ];

        foreach ($exchange_rates as $exchange_rate) {
            ExchangeRate::updateOrCreate($exchange_rate);
        }
    }
}
