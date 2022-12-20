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
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::LATE_FILLING, 'name' => PenaltyRate::LATE_FILLING_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::LATE_PAYMENT_BEFORE, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.2],
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::LATE_PAYMENT_AFTER, 'name' => PenaltyRate::LATE_PAYMENT_AFTER_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::WHICH_EVER_GREATER, 'name' => PenaltyRate::WHICH_EVER_GREATER_NAME, 'rate' => 100000],
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::PENALTY_FOR_MM_TRANSACTION, 'name' => PenaltyRate::PENALTY_FOR_MM_TRANSACTION_NAME, 'rate' => 1000000],
            ['financial_year_id'=> 1, 'code'=> PenaltyRate::LEASE_PENALTY, 'name' => PenaltyRate::LEASE_PENALTY_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::LATE_FILLING, 'name' => PenaltyRate::LATE_FILLING_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::LATE_PAYMENT_BEFORE, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.2],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::LATE_PAYMENT_AFTER, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::WHICH_EVER_GREATER, 'name' => PenaltyRate::WHICH_EVER_GREATER_NAME, 'rate' => 100000],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::PENALTY_FOR_MM_TRANSACTION, 'name' => PenaltyRate::PENALTY_FOR_MM_TRANSACTION_NAME, 'rate' => 1000000],
            ['financial_year_id'=> 2, 'code'=> PenaltyRate::LEASE_PENALTY, 'name' => PenaltyRate::LEASE_PENALTY_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::LATE_FILLING, 'name' => PenaltyRate::LATE_FILLING_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::LATE_PAYMENT_BEFORE, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.2],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::LATE_PAYMENT_AFTER, 'name' => PenaltyRate::LATE_PAYMENT_BEFORE_NAME, 'rate' => 0.1],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::WHICH_EVER_GREATER, 'name' => PenaltyRate::WHICH_EVER_GREATER_NAME, 'rate' => 100000],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::PENALTY_FOR_MM_TRANSACTION, 'name' => PenaltyRate::PENALTY_FOR_MM_TRANSACTION_NAME, 'rate' => 1000000],
            ['financial_year_id'=> 3, 'code'=> PenaltyRate::LEASE_PENALTY, 'name' => PenaltyRate::LEASE_PENALTY_NAME, 'rate' => 0.1],
        ];

        foreach($rates as $item){
            PenaltyRate::updateOrCreate($item);
        }
    }
}
