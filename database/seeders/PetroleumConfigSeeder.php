<?php

namespace Database\Seeders;

use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumConfigHead;
use Illuminate\Database\Seeder;

class PetroleumConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = [
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'MSP',
                'name' => 'MSP Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 300,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '02',
                'rate_label' => '03',
                'tax_label' => '04'
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'GO',
                'name' => 'GO Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 350,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '05',
                'rate_label' => '06',
                'tax_label' => '07'
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
                'code' => 'IK',
                'name' => 'IK Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 12.80,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '08',
                'rate_label' => '09',
                'tax_label' => '10'
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'JET',
                'name' => 'Jet A-1',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate' => 0,
                'rate_usd' => 1,
                'active' => true,
                'value_label' => '14',
                'rate_label' => '15',
                'tax_label' => '16'
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'PTL',
                'name' => 'Total Petroleum Levy',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'MSP+GO+IK+JET',
                'active' => true,
                'tax_label' => '17'
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'IFT',
                'name' => 'Infrastructure Tax',
                'row_type' => 'unremovable',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 0,
                'value_formular' => '(50 * MSP)+(50 * GO)',
                'active' => true,
                'value_label' => '18',
                'rate_label' => '29',
                'tax_label' => '20'
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'RSDFM',
                'name' => 'RSDF(MSP)',
                'row_type' => 'unremovable',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'value_formular' => '(70 * MSP)',
                'active' => true,
                'value_label' => '21',
                'rate_label' => '22',
                'tax_label' => '23'
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
                'code' => 'RSDFG',
                'name' => 'RSDF(GO)',
                'row_type' => 'unremovable',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'value_formular' => '(100 * GO)',
                'active' => true,
                'value_label' => '24',
                'rate_label' => '25',
                'tax_label' => '26'
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
                'code' => 'RLF',
                'name' => 'Road Licence Fee',
                'row_type' => 'unremovable',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'value_formular' => '(15 * MSP)+(15 * GO)',
                'active' => true,
                'value_label' => '27',
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'HEADING1',
                'name' => 'heading1',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'heading',
                'rate_applicable' => false,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'EXP',
                'name' => 'Exempt (Local & Import) Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
                'value_label' => '28',
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'LOP',
                'name' => 'Local Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
                'value_label' => '29',
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'IMP',
                'name' => 'Import Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
                'value_label' => '30',
            ],
            [
                'financia_year_id' => 1,
                'order' => 13,
                'code' => 'TOTAL',
                'name' => 'Total levy amount due',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'PTL+IFT+RSDFG+RSDFM+RLF',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            PetroleumConfig::updateOrCreate(['code' => $config['code']], $config);
        }

        $heading1 = PetroleumConfig::where('code', 'HEADING1')->first();
        
        $headings = [
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Purchases / Manunuzi',
                'colspan' => 2
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Value of Purchases / Manunuzi',
                'colspan' => 1
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Rate (Kiwango)',
                'colspan' => 2
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Levy Amount (Kiasi cha Kodi)',
                'colspan' => 2
            ]
        ];

        foreach($headings as $heading){
            PetroleumConfigHead::updateOrCreate(['name' => $heading['name']], $heading);
        }
        
    }
}
