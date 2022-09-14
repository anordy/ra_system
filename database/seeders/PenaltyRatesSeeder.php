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
            ['financial_year_id'=> 1, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2],
            ['financial_year_id'=> 1, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000],
            ['financial_year_id'=> 1, 'code'=> 'PFMobilesTrans', 'name' => 'Penalty for Mobile Month Transfer and Electronic Money Transaction', 'rate' => 1000000],
            ['financial_year_id'=> 1, 'code'=> '20RM', 'name' => 'Debt Penalty 20% of the remaining amount for waiver/extension', 'rate' => 0.2],            
            ['financial_year_id'=> 1, 'code'=> '10RM', 'name' => 'Debt Interest 10% of the remaining amount for waiver/extension', 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> 'LeasePenaltyRate', 'name' => '10% of the unpaid balance for each month the rent remains unpaid', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2],
            ['financial_year_id'=> 2, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000],
            ['financial_year_id'=> 2, 'code'=> 'PFMobilesTrans', 'name' => 'Penalty for Mobile Month Transfer and Electronic Money Transaction', 'rate' => 1000000],
            ['financial_year_id'=> 2, 'code'=> '20RM', 'name' => 'Debt Penalty 20% of the remaining amount for waiver/extension', 'rate' => 0.2],            
            ['financial_year_id'=> 2, 'code'=> '10RM', 'name' => 'Debt Interest 10% of the remaining amount for waiver/extension', 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> 'LeasePenaltyRate', 'name' => '10% of the unpaid balance for each month the rent remains unpaid', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2],
            ['financial_year_id'=> 3, 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000],
            ['financial_year_id'=> 3, 'code'=> 'PFMobilesTrans', 'name' => 'Penalty for Mobile Month Transfer and Electronic Money Transaction', 'rate' => 1000000],
            ['financial_year_id'=> 3, 'code'=> '20RM', 'name' => 'Debt Penalty 20% of the remaining amount for waiver/extension', 'rate' => 0.2],            
            ['financial_year_id'=> 3, 'code'=> '10RM', 'name' => 'Debt Interest 10% of the remaining amount for waiver/extension', 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> 'LeasePenaltyRate', 'name' => '10% of the unpaid balance for each month the rent remains unpaid', 'rate' => 0.1],
        ];

        foreach($rates as $item){
            PenaltyRate::updateOrCreate($item);
        }
    }
}
