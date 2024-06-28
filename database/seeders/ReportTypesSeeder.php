<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        TODO: Add all modules Mang'erere
        $types = [
            ['name' => 'TAX PAYER REGISTRATION'],
            ['name' => 'RETURNS'],
        ];

        foreach ($types as $type) {
            ReportType::updateOrCreate($type);
        }
    }
}
