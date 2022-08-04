<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use App\Models\InterestRate;
use Illuminate\Database\Seeder;

class InterestRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            ['year'=>2016,'rate'=>0.0175],
            ['year'=>2017,'rate'=>0.0189],
            ['year'=>2018,'rate'=>0.0187],
            ['year'=>2019,'rate'=>0.0183],
            ['year'=>2020,'rate'=>0.0181],
            ['year'=>2021,'rate'=>0.0180],
            ['year'=>2022,'rate'=>0.0180],
        ];

        foreach($rates as $item){
            InterestRate::updateOrCreate([
                'rate' => $item['rate'],
                'year'=> $item['year'],
            ]);
        }
    }
}
