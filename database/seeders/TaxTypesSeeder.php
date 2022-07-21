<?php

namespace Database\Seeders;

use App\Models\TaxType;
use Illuminate\Database\Seeder;

class TaxTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxType::updateOrCreate(['name' => 'VAT', 'code' => TaxType::VAT]);
        TaxType::updateOrCreate(['name' => 'Hotel Levy', 'code' => TaxType::HOTEL]);
        TaxType::updateOrCreate(['name' => 'Restaurant Levy', 'code' => TaxType::RESTAURANT]);
        TaxType::updateOrCreate(['name' => 'Tour Operation Levy', 'code' =>TaxType::TOUR]);
        TaxType::updateOrCreate(['name' => 'Land Lease', 'code' => TaxType::LAND]);
        TaxType::updateOrCreate(['name' => 'Public Services', 'code' => TaxType::PUBLIC_SERVICE]);
        TaxType::updateOrCreate(['name' => 'Excise Duty', 'code' => TaxType::EXCISE_DUTY]);
        TaxType::updateOrCreate(['name' => 'Petroleum Levy', 'code' => TaxType::PETROLEUM]);
        TaxType::updateOrCreate(['name' => 'Airport Service Charge', 'code' => TaxType::AIRPORT_SERVICE]);
        TaxType::updateOrCreate(['name' => 'Airport Safety Fee', 'code' => TaxType::AIRPORT_SAFETY]);
        TaxType::updateOrCreate(['name' => 'Sea Port Service Charge', 'code' => TaxType::SEAPORT_SERVICE]);
        TaxType::updateOrCreate(['name' => 'Sea Port Transport Charges', 'code' => TaxType::SEAPORT_TRANSPORT]);
        TaxType::updateOrCreate(['name' => 'Tax Consultant Licences', 'code' => TaxType::TAX_CONSULTANT]);
    }
}
