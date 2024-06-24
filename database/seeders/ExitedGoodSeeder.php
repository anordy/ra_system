<?php

namespace Database\Seeders;

use App\Models\Tra\ExitedGood;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExitedGoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {

            $exclusiveTax =  (int) (1000 * ceil(random_int(5000,1000000) / 1000));

            ExitedGood::create([
                'good_id' => random_int(1,999999999),
                'supplier_tin_number' => random_int(111111111,999999999),
                'owner_tin_number' => 111111111,
                'tansad_number' => random_int(100000000, 999999999),
                'tansad_date' => Carbon::now()->subMonths(2),
//                'verification_code' => random_int(100000000, 999999999),
                'value_excluding_tax' => $exclusiveTax,
                'tax_amount' => $exclusiveTax / 0.18,
                'custom_declaration_types' => 'IM4',
                'status' => 0,
            ]);
        }
    }
}
