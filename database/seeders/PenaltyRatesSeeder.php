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
            
        foreach (FinancialYear::whereIn('code', [2020, 2021, 2022, 2023, 2024, 2025])->get() as $financialYear) {
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::LATE_FILLING, 'name' => PenaltyRate::LATE_FILLING_NAME, 'rate' => 0.1, 'is_approved' => 1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::LATE_PAYMENT_BEFORE, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.2, 'is_approved' => 1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::LATE_PAYMENT_AFTER, 'name' => PenaltyRate::LATE_PAYMENT_AFTER_NAME, 'rate' => 0.1, 'is_approved' => 1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::WHICH_EVER_GREATER, 'name' => PenaltyRate::WHICH_EVER_GREATER_NAME, 'rate' => 100000, 'is_approved' => 1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::PENALTY_FOR_MM_TRANSACTION, 'name' => PenaltyRate::PENALTY_FOR_MM_TRANSACTION_NAME, 'rate' => 1000000, 'is_approved' => 1];
            $rates [] = ['financial_year_id'=> $financialYear['id'], 'code'=> PenaltyRate::LEASE_PENALTY, 'name' => PenaltyRate::LEASE_PENALTY_NAME, 'rate' => 0.1, 'is_approved' => 1];
            
        }
        
        foreach($rates as $item){
            PenaltyRate::updateOrCreate($item);
        }
    }
}
