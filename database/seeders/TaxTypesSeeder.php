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
        TaxType::create(['name' => 'VAT']);
        TaxType::create(['name' => 'Hotel Levy']);
        TaxType::create(['name' => 'Restaurant Levy']);
        TaxType::create(['name' => 'Tour Operation Levy']);
        TaxType::create(['name' => 'Land Lease']);
        TaxType::create(['name' => 'Public Services']);
        TaxType::create(['name' => 'Excercise Duty']);
        TaxType::create(['name' => 'Petroleum Levy']);
        TaxType::create(['name' => 'Airport Service Charge']);
        TaxType::create(['name' => 'Airport Safety Fee']);
        TaxType::create(['name' => 'Sea Port Service Charge']);
        TaxType::create(['name' => 'Sea Port Transport Charges']);
        TaxType::create(['name' => 'Tax Consultant Licences']);
    }
}
