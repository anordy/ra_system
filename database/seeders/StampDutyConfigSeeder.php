<?php

namespace Database\Seeders;

use App\Models\Returns\PetroleumConfig;
use Illuminate\Database\Seeder;

class StampDutyConfigSeeder extends Seeder
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
                'code' => 'SUP',
                'name' => 'Supplies / Mauzo yanayotozwa kodi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0.02,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'INST',
                'name' => 'Instrument / Hati Rasimi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'heading',
                'name' => 'Supplies / Mauzo yanayotozwa kodi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'heading',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'EXIMP',
                'name' => 'Exempt Import Purchases / Manunuzi kutoka nje ya nchi yaliyosamehewa VAT',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0.02,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'LOCPUR',
                'name' => 'Local purchases / Manunuzi ya hapa nchini',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'IMPPUR',
                'name' => 'Import purchases / Manunuzi kutoka nje ya nchi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0.02,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'WITHH',
                'name' => 'Withheld Tax / Kiasi cha kodi kilichozuiliwa',
                'row_type' => 'withheld',
                'value_calculated' => false,
                'col_type' => 'withheld',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'exempt-import',
                'name' => 'Total Duty(Amount due) / Kiasi kinachostahili kulipwa',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'total',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'formular' => '(SUP+INST+EXIMP)-WITHH',
                'rate' => 0.02,
                'rate_usd' => 0,
                'active' => true,
            ]
        ];

        $headings = [
            [
                'row_id' => 3,
                'name' => 'Purchase(Inputs) / Manunuzi',
                'colspan' => 1,
            ],
            [
                'row_id' => 3,
                'name' => 'Value of Purchases / Manunuzi',
                'colspan' => 1,
            ],
            [
                'row_id' => 3,
                'name' => 'Rate / Kiwango',
                'colspan' => 1,
            ],
            [
                'row_id' => 3,
                'name' => ' ',
                'colspan' => 1,
            ]
        ];

        foreach ($configs as $config) {
            PetroleumConfig::updateOrCreate($config);
        }
    }
}
