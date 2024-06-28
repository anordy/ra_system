<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $reports = [
            ['name' => 'Tax payer contributions', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 102],
            ['name' => 'GFS REVENUE', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 100],
            ['name' => 'Hotels Details', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 104],
            ['name' => 'Renting premisses reports', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 105],
            ['name' => 'Summary of all taxpayer', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 101],
            ['name' => 'Taxpayers (z-number, name, return for past 12 month)', 'has_parameter' => 1, 'report_type_id' => 1, 'code' => 103],
        ];

        Report::query()->truncate();

        foreach ($reports as $report) {
            Report::updateOrCreate($report);
        }
    }
}
