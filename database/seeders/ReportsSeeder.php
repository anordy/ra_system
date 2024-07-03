<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\Parameter;
use App\Models\Report;
use App\Models\ReportParameter;
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
            [
                'report_type' => GeneralReportType::INFRASTRUCTURE,
                'reports' => [
                    [
                        'name' => 'Infrastructure Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/infrastructure',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'VAT Number of Bed Nights',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/vat_bed_nights',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Hotel Number of Bed Nights',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/hotel_bed_nights',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Petroleum',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/petroleum',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                ]
            ],
            [
                'report_type' => GeneralReportType::PROPERTY_TAX,
                'reports' => [
                    [
                        'name' => 'Property Tax Tax Payers',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::PROPERTY_TAX,
                        'report_url' => '/reports/ZRA/PropertyTax/property_tax_taxpayers',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Property Tax Tax Payments',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::PROPERTY_TAX,
                        'report_url' => '/reports/ZRA/PropertyTax/property_tax_payments',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE, Parameter::REGION_NAME]
                    ],
                ]
            ],
            [
                'report_type' => GeneralReportType::BUSINESS,
                'reports' => [
                    [
                        'name' => 'Business without Z-Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/Business/business_without_z_number',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                ]
            ]
        ];


        foreach ($reports as $i => $report) {
            $reportType = ReportType::select('id')
                ->where('name', $report['report_type'])
                ->first();

            if (!$reportType) {
                throw new \Exception('Missing report type');
            }

            foreach ($report['reports'] as $j => $r) {
                $code = strtolower($r['name']);
                // Replace spaces with hyphens
                $code = str_replace(' ', '-', $code);
                // Add report
                Report::updateOrCreate(
                    [
                        'name' => $r['name']
                    ],
                    [
                        'name' => $r['name'],
                        'has_parameter' => $r['has_parameter'],
                        'report_type_id' => $reportType->id,
                        'code' => $code,
                        'report_url' => $r['report_url']
                    ]
                );

                $reportData = Report::where('name', $r['name'])->first();

                if (!$reportData) {
                    throw new \Exception('Missing report saved data');
                }

                if (count($r['parameters'] ?? [])) {
                    foreach ($r['parameters'] as $parameter) {
                        $parameter = Parameter::where('code', $parameter)->firstOrFail();
                        // Add report parameters
                        ReportParameter::updateOrCreate([
                            'report_id' => $reportData->id,
                            'parameter_id' => $parameter->id,
                        ]);
                    }
                }


            }

        }
    }
}