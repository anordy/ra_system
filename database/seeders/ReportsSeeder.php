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

        $reports = [
            // Taxpayer Registration reports
            [
                'name' => 'Tax payer contributions',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => null
            ],
            [
                'name' => 'GFS REVENUE',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => null
            ],
            [
                'name' => 'Hotels Details',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => null
            ],
            [
                'name' => 'Renting premisses reports',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => null
            ],
            [
                'name' => 'Summary of all taxpayer',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => null
            ],
            [
                'name' => 'Taxpayers (z-number, name, return for past 12 month)',
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => '/reports/ZRA/business' // TODO: Delete this url
            ],
            [
                'name' => 'Permissions Report (To delete)', // TODO: Delete this
                'has_parameter' => 1,
                'report_type_name' => GeneralReportType::TAXPAYER_REGISTRATION,
                'report_url' => '/reports/ZRA/permissions'
            ],
        ];



        foreach ($reports as $i => $report) {
            $reportType = ReportType::select('id')
                ->where('name', $report['report_type_name'])
                ->first();

            if (!$reportType) {
                throw new \Exception('Missing report type');
            }

            Report::updateOrCreate(
                [
                    'name' => $report['name']
                ],
                [
                    'name' => $report['name'],
                    'has_parameter' => $report['has_parameter'],
                    'report_type_id' => $reportType->id,
                    'code' => $i+100,
                ]
            );
        }
    }
}
