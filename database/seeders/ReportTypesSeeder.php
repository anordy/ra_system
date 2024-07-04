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
        foreach (GeneralReportType::getConstants() as $type) {
            ReportType::updateOrCreate(['name' => $type]);
        }
    }
}