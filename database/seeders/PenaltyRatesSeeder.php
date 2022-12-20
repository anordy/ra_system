<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
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
        foreach (FinancialYear::all('id') as $financialYear) {
            
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'LF', 'name' => 'Late Filling', 'rate' => 0.1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'LPB', 'name' => 'Late Payment Before', 'rate' => 0.2];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'LPA', 'name' => 'Late Payment After', 'rate' => 0.1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'WEG', 'name' => 'Which Ever Greater', 'rate' => 100000];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'PFMobilesTrans', 'name' => 'Penalty for Mobile Month Transfer and Electronic Money Transaction', 'rate' => 1000000];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> '20RM', 'name' => 'Debt Penalty 20% of the remaining amount for waiver/extension', 'rate' => 0.2];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> '10RM', 'name' => 'Debt Interest 10% of the remaining amount for waiver/extension', 'rate' => 0.1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> 'LeasePenaltyRate', 'name' => '10% of the unpaid balance for each month the rent remains unpaid', 'rate' => 0.1];
            
        }
        foreach($rates as $item){
            PenaltyRate::updateOrCreate($item);
        }
    }
}
