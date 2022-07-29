<?php

namespace Database\Seeders;

use App\Models\Returns\StampDuty\StampDutyService;
use Illuminate\Database\Seeder;

class StampDutyServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = [
            [
                'financial_month_id' => 1,
                'financial_year_id' => 1,
                'name' => 'Supplies (Mauzo Yanayotozwa Kodi)',
                'code' => 'supplies',
                'rate' => 0.02
            ],
            [
                'financial_month_id' => 1,
                'financial_year_id' => 1,
                'name' => 'Exempt Import Purchases (Manunuzi kutoka nje ya nchi yaliyosamehewa VAT)',
                'code' => 'exempt-import-purchases',
                'rate' => 0.02
            ],
        ];

        foreach ($services as $service) {
            StampDutyService::create($service);
        }
    }
}
