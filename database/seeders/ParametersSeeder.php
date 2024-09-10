<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Parameter;
use App\Models\PaymentStatus;
use App\Models\Region;
use App\Models\Relief\ReliefProject;
use App\Models\ReportRegister\RgCategory;
use App\Models\ReportRegister\RgSubCategory;
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
                'parent_id' => Parameter::where('code', Parameter::REGION)->firstOrFail()->id
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
            ],
            [
                'name' => 'Dynamic Date',
                'code' => Parameter::DYNAMIC_DATE,
                'input_type' => 'dynamic',
                'model_name' => null,
                'display_name' => null,
            ],
            [
                'name' => 'Project',
                'code' => Parameter::PROJECT_ID,
                'input_type' => 'select',
                'model_name' => ReliefProject::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Rate',
                'code' => Parameter::RATE,
                'input_type' => 'text',
                'model_name' => null,
                'display_name' => null,
            ],
            [
                'name' => 'ZTN Number',
                'code' => Parameter::ZTN_NUMBER,
                'input_type' => 'text',
                'model_name' => null,
                'display_name' => null,
            ],
            [
                'name' => 'ZTN Location Number',
                'code' => Parameter::ZTN_LOCATION_NUMBER,
                'input_type' => 'text',
                'model_name' => null,
                'display_name' => null,
            ],
            [
                'name' => 'Department Name',
                'code' => Parameter::DEPARTMENT_NAME,
                'input_type' => 'select',
                'model_name' => 'SELECT DISTINCT LOCATION AS NAME, ID AS ID FROM TAX_REGIONS',
                'display_name' => 'name',
            ],
            [
                'name' => 'Tax Region Name',
                'code' => Parameter::TAX_REGION_NAME,
                'input_type' => 'select',
                'model_name' => 'SELECT DISTINCT NAME, ID FROM TAX_REGIONS WHERE LOCATION = \'DTD\'',
                'display_name' => 'name',
            ],
            [
                'name' => 'Report Register Category',
                'code' => Parameter::RG_CATEGORY_ID,
                'input_type' => 'select',
                'model_name' => TaxType::class,
                'display_name' => 'name',
            ],
    
                'name' => 'Category',
                'code' => Parameter::RG_CATEGORY_ID,
                'input_type' => 'select',
                'model_name' => RgCategory::class,
                'display_name' => 'name',
            ],
            [
                'name' => 'Sub Category',
                'code' => Parameter::RG_SUB_CATEGORY_ID,
                'input_type' => 'select',
                'model_name' => RgSubCategory::class,
                'display_name' => 'name',
                'parent_id' => Parameter::where('code', Parameter::RG_CATEGORY_ID)->firstOrFail()->id
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