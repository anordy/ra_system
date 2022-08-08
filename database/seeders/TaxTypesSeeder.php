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
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'VAT', 'code'=> 'VAT']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Hotel Levy', 'code'=>'Hotel_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Restaurant Levy', 'code'=>'Restaurant_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Tour Operation Levy', 'code'=>'Tour_Operation_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Land Lease', 'code'=>'Land_Lease']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Public Services', 'code'=>'Public_Services']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Excise Duty MNO', 'code'=>'Excise_Duty_Mno']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Excise Duty BFO', 'code'=>'Excise_Duty_Bfo']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Petroleum Levy', 'code'=>'Petroleum_Levy']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Airport Service & Safety Fee', 'code'=>'Airport_Service_Safety_fee']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Sea Port Service & Transport Charge', 'code'=>'Sea_Service_Transport_Charge']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Tax Consultant Licences', 'code'=>'Tax_Consultant_Licences']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Stamp Duty', 'code' => TaxType::STAMP_DUTY]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Lumpsum Payments', 'code' => TaxType::LUMPSUM_PAYMENT]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Electronic Money Transaction', 'code' => TaxType::ELECTRONIC_MONEY_TRANSACTION]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Mobile Money Transfer', 'code' => TaxType::MOBILE_MONEY_TRANSFER]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Penalties', 'code' => TaxType::PENALTY]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Interests', 'code' => TaxType::INTEREST]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Infrastructure', 'code' => TaxType::INFRASTRUCTURE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'RDF', 'code' => TaxType::RDF]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Road License Fee ', 'code' => TaxType::ROAD_LICENSE_FEE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Investigation', 'code' => TaxType::INVESTIGATION, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Audit', 'code' => TaxType::AUDIT, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Verification', 'code' => TaxType::VERIFICATION, 'category' => 'other']);
    }
}
