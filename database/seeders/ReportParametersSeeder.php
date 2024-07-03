<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\District;
use App\Models\Parameter;
use App\Models\Region;
use App\Models\ReportParameter;
use Illuminate\Database\Seeder;

class ReportParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reportParameters = [
          [
              'report_name' => GeneralReportType::RETURNS
          ]
        ];

//        foreach ($parameters as $parameter) {
//            ReportParameter::updateOrCreate(
//                [
//                    'code' => $parameter['code']
//                ],
//                $parameter
//            );
//        }

    }
}