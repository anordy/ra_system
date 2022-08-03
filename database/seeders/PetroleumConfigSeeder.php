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
                'rate' => 350,
                'rate_usd' => 0,
                'active' => true,
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
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'RDF',
                'name' => 'RDF',
                'row_type' => 'unremovable',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'value_formular' => '(70 * MSP)+(100 * GO)',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
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
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
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
                'order' => 10,
                'code' => 'EXP',
                'name' => 'Exempt (Local & Import) Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'LOP',
                'name' => 'Local Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'IMP',
                'name' => 'Import Purchases',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'rate_applicable' => false,
                'col_type' => 'normal',
                'rate_type' => 'fixed',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'TOTAL',
                'name' => 'Total levy amount due',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'PTL+IFT+RDF+RLF',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            PetroleumConfig::updateOrCreate($config);
        }

        $heading1 = PetroleumConfig::where('code', 'heading1')->first();
        
        $headings = [
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Purchases / Manunuzi',
                'colspan' => 1
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Value of Purchases / Manunuzi',
                'colspan' => 1
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Rate (Kiwango)',
                'colspan' => 1
            ],
            [
                'petroleum_config_id' => $heading1->id,
                'name' => 'Levy Amount (Kiasi cha Kodi)',
                'colspan' => 1
            ]
        ];

        foreach($headings as $heading){
            PetroleumConfigHead::create($heading);
        }
        
    }
}
