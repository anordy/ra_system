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
        TaxType::updateOrCreate(['name' => 'Excise Duty MNO', 'code'=>'Excise_Duty_Mno']);
        TaxType::updateOrCreate(['name' => 'Excise Duty BFO', 'code'=>'Excise_Duty_Bfo']);
        TaxType::updateOrCreate(['name' => 'Petroleum Levy', 'code'=>'Petroleum_Levy']);
        TaxType::updateOrCreate(['name' => 'Airport Service & Safety Fee', 'code'=>'Airport_Service_Safety_fee']);
        TaxType::updateOrCreate(['name' => 'Sea Port Service & Transport Charge', 'code'=>'Sea_Service_Transport_Charge']);
        TaxType::updateOrCreate(['name' => 'Tax Consultant Licences', 'code'=>'Tax_Consultant_Licences']);
        TaxType::updateOrCreate(['name' => 'Stamp Duty', 'code' => TaxType::STAMP_DUTY]);
        TaxType::updateOrCreate(['name' => 'Lumpsum Payments', 'code' => TaxType::LUMPSUM_PAYMENT]);
        TaxType::updateOrCreate(['name' => 'Electronic Money Transaction', 'code' => TaxType::ELECTRONIC_MONEY_TRANSACTION]);
        TaxType::updateOrCreate(['name' => 'Mobile Money Transfer', 'code' => TaxType::MOBILE_MONEY_TRANSFER]);
    }
}
