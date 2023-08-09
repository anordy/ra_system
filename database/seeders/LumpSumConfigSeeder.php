<?php

namespace Database\Seeders;

use App\Models\Returns\LumpSum\LumpSumConfig;
use Illuminate\Database\Seeder;

class LumpSumConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 5000000.0,
            'max_sales_per_year' => 7499999.0,
            'payments_per_year' => 100000.00,
            'payments_per_installment' => 25000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 7500000.0,
            'max_sales_per_year' => 9999999.0,
            'payments_per_year' => 200000.00,
            'payments_per_installment' => 50000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 10000000.0,
            'max_sales_per_year' => 12499999.0,
            'payments_per_year' => 300000.00,
            'payments_per_installment' => 75000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 12500000.0,
            'max_sales_per_year' => 14999999.0,
            'payments_per_year' => 400000.00,
            'payments_per_installment' => 100000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 15000000.0,
            'max_sales_per_year' => 24999999.0,
            'payments_per_year' => 700000.00,
            'payments_per_installment' => 175000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 25000000.0,
            'max_sales_per_year' => 34999999.0,
            'payments_per_year' => 1000000.00,
            'payments_per_installment' => 250000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 35000000.0,
            'max_sales_per_year' => 44999999.0,
            'payments_per_year' => 1300000.00,
            'payments_per_installment' => 325000.00,
        ]);
        
        LumpSumConfig::updateOrCreate([
            'min_sales_per_year' => 45000000.0,
            'max_sales_per_year' => 49999999.0,
            'payments_per_year' => 1600000.00,
            'payments_per_installment' => 400000.00,
        ]);
        
    }
}
