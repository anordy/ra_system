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
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'VAT', 'code'=> TaxType::VAT]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Hotel Levy', 'code'=> TaxType::HOTEL]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Restaurant Levy', 'code'=> TaxType::RESTAURANT]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Tour Operation Levy', 'code'=> TaxType::TOUR_OPERATOR]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Land Lease', 'code'=> TaxType::LAND_LEASE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Public Services', 'code'=> TaxType::PUBLIC_SERVICE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Excise Duty MNO', 'code'=> TaxType::EXCISE_DUTY_MNO]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Excise Duty BFO', 'code'=> TaxType::EXCISE_DUTY_BFO]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Petroleum Levy', 'code'=> TaxType::PETROLEUM]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Airport Service & Safety Fee', 'code'=> TaxType::AIRPORT_SERVICE_SAFETY_FEE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Sea Port Service & Transport Charge', 'code'=>TaxType::SEA_SERVICE_TRANSPORT_CHARGE]);
        TaxType::updateOrCreate(['gfs_code' => '112061', 'name' => 'Tax Consultant Licences', 'code'=> TaxType::TAX_CONSULTANT]);
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
