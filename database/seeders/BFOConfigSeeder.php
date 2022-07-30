<?php

namespace Database\Seeders;

use App\Models\BFOConfig;
use Illuminate\Database\Seeder;

class BFOConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $configs = [
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'CWC',
                'name' => 'Cash Withdrawal Charges/Fees',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'EMTC',
                'name' => 'Electronic Money Transfer Charges',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
                'code' => 'MLPF',
                'name' => 'Mobile Loan Processing Fees',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 4,
                'code' => 'MBTC',
                'name' => 'Mobile/Bank Transfer Charges',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'SPCF',
                'name' => 'Salary Processing Charges/Fees',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'ODFLC',
                'name' => 'Over Draft Facilities/Lending Charges',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'ComR',
                'name' => 'Commissions Received',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'RSF',
                'name' => 'Radio Services Fees',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
                'code' => 'PVTS',
                'name' => 'Pay To View Televission Services',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 5,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
                'code' => 'ASF',
                'name' => 'Advertisement Services Fees',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'FOTHER',
                'name' => 'Fees On Any Other Service Charges',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],
            [ 
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'TotalFBO',
                'name' => 'Total Excise Duty Payable',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'CWC+EMTC+MLPF+MBTC+SPCF+ODFLC+ComR+RSF+PVTS+ASF+FOTHER',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            BFOConfig::updateOrCreate($config);
        }
    }
}
