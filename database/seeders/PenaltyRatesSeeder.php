<?php

namespace Database\Seeders;

use App\Models\PenaltyRate;
use Illuminate\Database\Seeder;

class PenaltyRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = [
            ['financial_year_id'=> 1, 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000],
            ['financial_year_id'=> 2, 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2],
            ['financial_year_id'=> 2, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000],
            ['financial_year_id'=> 3, 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2],
            ['financial_year_id'=> 3, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000]
        ];

        foreach($rates as $item){
            PenaltyRate::updateOrCreate($item);
        }
    }
}
