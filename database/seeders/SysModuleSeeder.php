<?php

namespace Database\Seeders;

use App\Models\SysModule;
use Illuminate\Database\Seeder;

class SysModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'code' => 'taxpayer-management', 'name' => 'Taxpayer Management'],
            ['id' => 2, 'code' => 'business-management', 'name' => 'Business Management'],
            ['id' => 3, 'code' => 'tax-consultant', 'name' => 'Tax Consultant'],
            ['id' => 4, 'code' => 'upgrade-tax-types', 'name' => 'Upgrade Tax Types'],
            ['id' => 5, 'code' => 'tax-return', 'name' => 'Tax Return'],
            ['id' => 6, 'code' => 'withholding-agent', 'name' => 'Withholding Agent'],
            ['id' => 7, 'code' => 'petroleum-management', 'name' => 'Petroleum Management'],
            ['id' => 8, 'code' => 'return-verification', 'name' => 'Return Verification'],
            ['id' => 9, 'code' => 'tax-claim', 'name' => 'Tax Claim'],
            ['id' => 10, 'code' => 'tax-auditing', 'name' => 'Auditing'],
            ['id' => 11, 'code' => 'tax-investigation', 'name' => 'Investigation'],
            ['id' => 12, 'code' => 'dispute-management', 'name' => 'Dispute Management'],
            ['id' => 13, 'code' => 'tax-clearance-management', 'name' => 'Tax Clearance Management'],
            ['id' => 14, 'code' => 'debt-management', 'name' => 'Debt Management'],
            ['id' => 15, 'code' => 'mvr', 'name' => 'Motor Vehicle Registration'],
            ['id' => 16, 'code' => 'land-lease-management', 'name' => 'Land Lease'],
            ['id' => 17, 'code' => 'manage-payment-management', 'name' => 'Manage Payment'],
            ['id' => 18, 'code' => 'setting', 'name' => 'Setting'],
            ['id' => 19, 'code' => 'system', 'name' => 'System'],
            ['id' => 20, 'code' => 'managerial-report', 'name' => 'Managerial Report'],
            ['id' => 21, 'code' => 'payment-extension', 'name' => 'Payment Extension'],
            ['id' => 22, 'code' => 'payment-installment', 'name' => 'Payment By Installment'],
            ['id' => 23, 'code' => 'relief-managements', 'name' => 'Relief Management'],
            ['id' => 24, 'code' => 'finance-management', 'name' => 'Manage Finance'],
            ['id' => 25, 'code' => 'driver-licence-management', 'name' => 'Manage Driver licencing'],
            ['id' => 26, 'code' => 'motor-vehicles-management', 'name' => 'Manage Motor Vehicles'],
            ['id' => 27, 'code' => 'tax-returns-vetting', 'name' => 'Tax Returns Vetting'],
            ['id' => 28, 'code' => 'road-inspection-offence', 'name' => 'Road Inspection Offence'],
            ['id' => 29, 'code' => 'tra-information', 'name' => 'Tra Information'],
            ['id' => 30, 'code' => 'legal-cases', 'name' => 'Legal Cases'],
            ['id' => 31, 'code' => 'property-tax', 'name' => 'Property Tax'],

            ['id' => 31, 'code' => 'vfm-integration', 'name' => 'VFMS Integration'],


        ];
        foreach ($data as $row) {
            SysModule::updateOrCreate($row);
        }
    }
}
