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
        TaxType::updateOrCreate(['name' => 'VAT', 'code'=> 'VAT']);
        TaxType::updateOrCreate(['name' => 'Hotel Levy', 'code'=>'Hotel_Levy']);
        TaxType::updateOrCreate(['name' => 'Restaurant Levy', 'code'=>'Restaurant_Levy']);
        TaxType::updateOrCreate(['name' => 'Tour Operation Levy', 'code'=>'Tour_Operation_Levy']);
        TaxType::updateOrCreate(['name' => 'Land Lease', 'code'=>'Land_Lease']);
        TaxType::updateOrCreate(['name' => 'Public Services', 'code'=>'Public_Services']);
        TaxType::updateOrCreate(['name' => 'Excise Duty', 'code'=>'Excise_Duty']);
        TaxType::updateOrCreate(['name' => 'Petroleum Levy', 'code'=>'Petroleum_Levy']);
        TaxType::updateOrCreate(['name' => 'Airport Service Charge', 'code'=>'Airport_Service_Charge']);
        TaxType::updateOrCreate(['name' => 'Airport Safety Fee', 'code'=>'Airport_Safety_Fee']);
        TaxType::updateOrCreate(['name' => 'Sea Port Service Charge', 'code'=>'Sea_Port_Service_Charge']);
        TaxType::updateOrCreate(['name' => 'Sea Port Transport Charges', 'code'=>'Sea_Port_Transport_Charges']);
        TaxType::updateOrCreate(['name' => 'Tax Consultant Licences', 'code'=>'Tax_Consultant_Licences']);
        TaxType::updateOrCreate(['name' => 'Lumpsum Payments', 'code'=>'lumpsum-payment']);
    }
}
