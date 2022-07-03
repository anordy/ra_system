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
        TaxType::updateOrCreate(['name' => 'VAT']);
        TaxType::updateOrCreate(['name' => 'Hotel Levy']);
        TaxType::updateOrCreate(['name' => 'Restaurant Levy']);
        TaxType::updateOrCreate(['name' => 'Tour Operation Levy']);
        TaxType::updateOrCreate(['name' => 'Land Lease']);
        TaxType::updateOrCreate(['name' => 'Public Services']);
        TaxType::updateOrCreate(['name' => 'Excercise Duty']);
        TaxType::updateOrCreate(['name' => 'Petroleum Levy']);
        TaxType::updateOrCreate(['name' => 'Airport Service Charge']);
        TaxType::updateOrCreate(['name' => 'Airport Safety Fee']);
        TaxType::updateOrCreate(['name' => 'Sea Port Service Charge']);
        TaxType::updateOrCreate(['name' => 'Sea Port Transport Charges']);
        TaxType::updateOrCreate(['name' => 'Tax Consultant Licences']);
    }
}
