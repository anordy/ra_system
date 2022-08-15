<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
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
                'mean' => '2304.6342	',
                'spot_buying' => '2293.1683',
                'spot_selling' => '2316.1',
                'exchange_date' => '2022-08-10',
            ],
            [
                'currency' => 'GBP',
                'mean' => '2792.9884',
                'spot_buying' => '2778.632',
                'spot_selling' => '2807.3448',
                'exchange_date' => '2022-08-10',
            ],
        ];

        foreach ($exchange_rates as $exchange_rate) {
            ExchangeRate::updateOrCreate($exchange_rate);
        }
    }
}
