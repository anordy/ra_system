<?php

namespace Database\Seeders;

use App\Enum\NonTaxResident\NtrBusinessType;
use App\Models\Ntr\NtrNatureOfBusiness;
use Illuminate\Database\Seeder;

class NtrNatureOfBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $natures = [
            [
                'name' => 'Tourism',
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Import/Export',
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Consultancy',
                'business_type' => NtrBusinessType::NON_RESIDENT
            ],
            [
                'name' => 'Goods',
                'business_type' => NtrBusinessType::ECOMMERCE
            ],
            [
                'name' => 'Digital Services',
                'business_type' => NtrBusinessType::ECOMMERCE
            ]
        ];

        foreach ($natures as $nature) {
            NtrNatureOfBusiness::updateOrCreate([
                'name' => $nature['name']
            ],
                [
                    'name' => $nature['name'],
                    'business_type' => $nature['business_type']
                ]);
        }


    }
}
