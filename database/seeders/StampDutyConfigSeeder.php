<?php

namespace Database\Seeders;

use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyConfigHead;
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
                'order' => 3,
                'code' => 'heading',
                'name' => 'Supplies / Mauzo yanayotozwa kodi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'heading',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 4,
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
                'order' => 5,
                'code' => 'LOCPUR',
                'name' => 'Local purchases / Manunuzi ya hapa nchini',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'is_summable' => false,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'IMPPUR',
                'name' => 'Import purchases / Manunuzi kutoka nje ya nchi',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'currency' => 'TZS',
                'is_summable' => false,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'WITHH',
                'name' => 'Withheld Tax / Kiasi cha kodi kilichozuiliwa',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'currency' => 'TZS',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
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

        foreach ($configs as $config) {
            StampDutyConfig::updateOrCreate($config);
        }

        $headingIndex = StampDutyConfig::where('code', 'heading')->first()->id;

        $headings = [
            [
                'stamp_duty_config_id' => $headingIndex,
                'name' => 'Purchase(Inputs) / Manunuzi',
                'colspan' => 1,
            ],
            [
                'stamp_duty_config_id' => $headingIndex,
                'name' => 'Value of Purchases / Manunuzi',
                'colspan' => 1,
            ],
            [
                'stamp_duty_config_id' => $headingIndex,
                'name' => 'Rate / Kiwango',
                'colspan' => 1,
            ],
            [
                'stamp_duty_config_id' => $headingIndex,
                'name' => ' ',
                'colspan' => 1,
            ]
        ];

        foreach ($headings as $heading){
            StampDutyConfigHead::updateOrCreate($heading);
        }
    }
}
