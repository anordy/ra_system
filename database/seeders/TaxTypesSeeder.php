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
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'VAT', 'code' => TaxType::VAT]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Hotel Levy', 'code' => TaxType::HOTEL]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Restaurant Levy', 'code' => TaxType::RESTAURANT]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Tour Operation Levy', 'code' => TaxType::TOUR_OPERATOR]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Land Lease', 'code' => TaxType::LAND_LEASE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Public Services', 'code' => TaxType::PUBLIC_SERVICE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Mobile Network Operators Excise Duty', 'code' => TaxType::EXCISE_DUTY_MNO]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Banks, Financial Institutions and Others Excise Duty', 'code' => TaxType::EXCISE_DUTY_BFO]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Petroleum Levy', 'code' => TaxType::PETROLEUM]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Airport Service & Safety Fee', 'code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Airport Service charge', 'code' => TaxType::AIRPORT_SERVICE_CHARGE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Airport Safety Fee', 'code' => TaxType::AIRPORT_SAFETY_FEE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Sea Port Service & Transport Charge', 'code' => TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Sea Port Transport Charge', 'code' => TaxType::SEAPORT_TRANSPORT_CHARGE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Sea Port Service', 'code' => TaxType::SEAPORT_SERVICE_CHARGE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Tax Consultant Licences', 'code' => TaxType::TAX_CONSULTANT, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Stamp Duty Composition', 'code' => TaxType::STAMP_DUTY]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Stamp Duty Lumpsum', 'code' => TaxType::LUMPSUM_PAYMENT]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Electronic Money Transaction', 'code' => TaxType::ELECTRONIC_MONEY_TRANSACTION]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Mobile Money Transfer', 'code' => TaxType::MOBILE_MONEY_TRANSFER]);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Penalties', 'code' => TaxType::PENALTY, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Interests', 'code' => TaxType::INTEREST, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Infrastructure', 'code' => TaxType::INFRASTRUCTURE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'RDF', 'code' => TaxType::RDF, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Road License Fee ', 'code' => TaxType::ROAD_LICENSE_FEE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Investigation', 'code' => TaxType::INVESTIGATION, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Audit', 'code' => TaxType::AUDIT, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Verification', 'code' => TaxType::VERIFICATION, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Disputes', 'code' => TaxType::DISPUTES, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Waiver', 'code' => TaxType::WAIVER, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Objection', 'code' => TaxType::OBJECTION, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Waiver and Objection', 'code' => TaxType::WAIVER_OBJECTION, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'eGovernment Fee', 'code' => TaxType::GOVERNMENT_FEE, 'category' => 'other']);
        TaxType::updateOrCreate(['is_approved' => 1, 'gfs_code' => '104395', 'name' => 'Hotel Airbnb', 'code' => TaxType::AIRBNB]);
    }
}
