<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskIndicator;

class RiskIndicatorsSeeder extends Seeder
{
    /**
     * Run the seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'risk_indicator' => 'Nil Return for three consecutive months',
                'risk_level' => 'H',
                'slug' => 'nil_return_3m',
            ],
            [
                'risk_indicator' => 'All Credit returns',
                'risk_level' => 'M',
                'slug' => 'all_credit_return',
            ],
            [
                'risk_indicator' => 'Taxpayer who didn\'t declare purchases for three consecutive months',
                'risk_level' => 'H',
                'slug' => 'no_purchase_3m',
            ],
            [
                'risk_indicator' => 'VAT/hotel Returns for Hotel business whose Purchases/expenses exceed 1/3 of the Sales related to return',
                'risk_level' => 'H',
                'slug' => 'hotel_purchase_exceed',
            ],
            [
                'risk_indicator' => 'Taxpayer who appeared not tally with comparison reports',
                'risk_level' => 'M',
                'slug' => 'comparison_mismatch',
            ],
            [
                'risk_indicator' => 'Non-Filer for three Consecutive months',
                'risk_level' => 'H',
                'slug' => 'non_filer_3m',
            ],
            [
                'risk_indicator' => 'Trends of tax paid for the month and other month differ by less than or equal to 10%',
                'risk_level' => 'L',
                'slug' => 'tax_trend_diff_10',
            ],
            [
                'risk_indicator' => 'Sales vs purchases difference is less than or equal to 10 %',
                'risk_level' => 'L',
                'slug' => 'sale_purchase_diff_10',
            ],
        ];

        foreach ($data as $item) {
            RiskIndicator::create($item);
        }
    }
}
