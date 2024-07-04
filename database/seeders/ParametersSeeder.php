<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Parameter;
use App\Models\PaymentStatus;
use App\Models\Region;
use App\Models\TaxDepartment;
use App\Models\TaxRegion;
use App\Models\TaxType;
use Illuminate\Database\Seeder;

class ParametersSeeder extends Seeder
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
                'code' => Parameter::START_DATE,
                'input_type' => 'date',
                'model_name' => '',
                'display_name' => '',
            ],
            [
                'name' => 'End Date',
                'code' => Parameter::END_DATE,
                'input_type' => 'date',
                'model_name' => '',
                'display_name' => '',
            ],
            [
                'name' => 'Region',
                'code' => Parameter::REGION,
                'input_type' => 'select',
                'model_name' => Region::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'District',
                'code' => Parameter::DISTRICT,
                'input_type' => 'select',
                'model_name' => District::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Tax Department',
                'code' => Parameter::DEPARTMENT,
                'input_type' => 'select',
                'model_name' => TaxDepartment::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Financial Year',
                'code' => Parameter::FINANCIAL_YEAR,
                'input_type' => 'select',
                'model_name' => FinancialYear::class,
                'display_name' => 'code',
            ],
            [
                'name' => 'Financial Month',
                'code' => Parameter::FINANCIAL_MONTH,
                'input_type' => 'select',
                'model_name' => FinancialMonth::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Tax Region',
                'code' => Parameter::TAX_REGION,
                'input_type' => 'select',
                'model_name' => TaxRegion::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Tax Type',
                'code' => Parameter::TAX_TYPE,
                'input_type' => 'select',
                'model_name' => TaxType::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Region Name',
                'code' => Parameter::REGION_NAME,
                'input_type' => 'select',
                'model_name' => 'SELECT DISTINCT REGION_ID AS NAME, REGION_ID AS ID FROM PROPERTIES',
                'display_name' => 'name',
            ],
            [
                'name' => 'Code',
                'code' => Parameter::CODE,
                'input_type' => 'input',
                'model_name' => '',
                'display_name' => '',
            ],
            [
                'name' => 'Payment Status',
                'code' => Parameter::PAYMENT_STATUS,
                'input_type' => 'select',
                'model_name' => PaymentStatus::class,
                'display_name' => 'name',
            ]
        ];

        foreach ($parameters as $parameter) {
            Parameter::updateOrCreate(
                [
                    'code' => $parameter['code']
                ],
                $parameter
            );
        }

    }
}