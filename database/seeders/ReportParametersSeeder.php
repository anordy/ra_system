<?php

namespace Database\Seeders;

use App\Models\District;
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
        $parameters = [
            [
                'name' => 'Start Date',
                'code' => 'start_date',
                'input_type' => 'date',
                'model_name' => '',
                'display_name' => '',
            ],
            [
                'name' => 'End Date',
                'code' => 'end_date',
                'input_type' => 'date',
                'model_name' => '',
                'display_name' => '',
            ]
        ];

        foreach ($parameters as $parameter) {
            ReportParameter::updateOrCreate(
                [
                    'code' => $parameter['code']
                ],
                $parameter
            );
        }

    }
}
