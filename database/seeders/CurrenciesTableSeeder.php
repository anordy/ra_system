<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::updateOrCreate([
            'iso' => 'TZS',
            'name' => 'Tanzanian Shillings',
            'symbol' => 'Sh'
        ]);

        Currency::updateOrCreate([
            'iso' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$'
        ]);
    }
}
