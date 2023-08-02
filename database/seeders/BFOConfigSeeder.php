<?php

namespace Database\Seeders;

use App\Models\Returns\BFO\BfoConfig;
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
                'value_label' => '01',
                'rate_label' => '02',
                'tax_label' => '03'
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
                'value_label' => '04',
                'rate_label' => '05',
                'tax_label' => '06'
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
                'value_label' => '07',
                'rate_label' => '08',
                'tax_label' => '09'
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
                'value_label' => '10',
                'rate_label' => '11',
                'tax_label' => '12'
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
                'value_label' => '13',
                'rate_label' => '14',
                'tax_label' => '15'
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
                'value_label' => '16',
                'rate_label' => '17',
                'tax_label' => '18'
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
                'value_label' => '19',
                'rate_label' => '20',
                'tax_label' => '21'
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
                'value_label' => '22',
                'rate_label' => '23',
                'tax_label' => '24'
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
                'value_label' => '25',
                'rate_label' => '26',
                'tax_label' => '27'
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
                'value_label' => '28',
                'rate_label' => '29',
                'tax_label' => '30'
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
                'value_label' => '31',
                'rate_label' => '32',
                'tax_label' => '33'
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'IS',
                'name' => 'Imported Services',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 12,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '34',
                'rate_label' => '35',
                'tax_label' => '36'
            ],
            [ 
                'financia_year_id' => 1,
                'order' => 13,
                'code' => 'TotalFBO',
                'name' => 'Total Excise Duty Payable',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'CWC+EMTC+MLPF+MBTC+SPCF+ODFLC+ComR+RSF+PVTS+ASF+FOTHER+IS',
                'active' => true,
                'value_label' => '37',
                'rate_label' => '38',
                'tax_label' => '39'
            ],
        ];

        foreach ($configs as $config) {
            BFOConfig::updateOrCreate(['code' => $config['code']], $config);
        }
    }
}
