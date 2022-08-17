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
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'VAT', 'code' => TaxType::VAT]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Hotel Levy', 'code' => TaxType::HOTEL]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Restaurant Levy', 'code' => TaxType::RESTAURANT]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Tour Operation Levy', 'code' => TaxType::TOUR_OPERATOR]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Land Lease', 'code' => TaxType::LAND_LEASE]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Public Services', 'code' => TaxType::PUBLIC_SERVICE]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Excise Duty MNO', 'code' => TaxType::EXCISE_DUTY_MNO]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Banks, Financial Institutions and Others', 'code' => TaxType::EXCISE_DUTY_BFO]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Petroleum Levy', 'code' => TaxType::PETROLEUM]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Airport Service & Safety Fee', 'code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Sea Port Service & Transport Charge', 'code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Tax Consultant Licences', 'code' => TaxType::TAX_CONSULTANT]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Stamp Duty', 'code' => TaxType::STAMP_DUTY]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Lumpsum Payments', 'code' => TaxType::LUMPSUM_PAYMENT]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Electronic Money Transaction', 'code' => TaxType::ELECTRONIC_MONEY_TRANSACTION]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Mobile Money Transfer', 'code' => TaxType::MOBILE_MONEY_TRANSFER]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Penalties', 'code' => TaxType::PENALTY, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Interests', 'code' => TaxType::INTEREST, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Infrastructure', 'code' => TaxType::INFRASTRUCTURE, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'RDF', 'code' => TaxType::RDF]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Road License Fee ', 'code' => TaxType::ROAD_LICENSE_FEE]);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Investigation', 'code' => TaxType::INVESTIGATION, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Audit', 'code' => TaxType::AUDIT, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Verification', 'code' => TaxType::VERIFICATION, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Disputes', 'code' => TaxType::DISPUTES, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'eGovernment Fee', 'code' => TaxType::GOVERNMENT_FEE, 'category' => 'other']);
        TaxType::updateOrCreate(['gfs_code' => '116101', 'name' => 'Debts', 'code' => TaxType::DEBTS, 'category' => 'other']);

    }
}
