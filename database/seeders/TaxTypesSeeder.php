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
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'VAT', 'code'=> 'VAT']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Hotel Levy', 'code'=>'Hotel_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Restaurant Levy', 'code'=>'Restaurant_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Tour Operation Levy', 'code'=>'Tour_Operation_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Land Lease', 'code'=>'Land_Lease']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Public Services', 'code'=>'Public_Services']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Excise Duty MNO', 'code'=>'Excise_Duty_Mno']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Excise Duty BFO', 'code'=>'Excise_Duty_Bfo']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Petroleum Levy', 'code'=>'Petroleum_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Airport Service & Safety Fee', 'code'=>'Airport_Service_Safety_fee']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Sea Port Service & Transport Charge', 'code'=>'Sea_Service_Transport_Charge']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Tax Consultant Licences', 'code'=>'Tax_Consultant_Licences']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Stamp Duty', 'code' => TaxType::STAMP_DUTY]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Lumpsum Payments', 'code' => TaxType::LUMPSUM_PAYMENT]);
    }
}
