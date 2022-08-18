<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

             # KYC Data
             ['name' => 'taxpayer_view', 'sys_module_id' => 1],
             ['name' => 'kyc_view', 'sys_module_id' => 1],
             ['name' => 'kyc_complete', 'sys_module_id' => 1],

            # Business Management
            ['name' => 'registration', 'sys_module_id' => 2],
            ['name' => 'branches-view', 'sys_module_id' => 2],
            ['name' => 'de-registration-view', 'sys_module_id' => 2],
            ['name' => 'temporary-closures-view', 'sys_module_id' => 2],
            ['name' => 'business-update-request-view', 'sys_module_id' => 2],
            ['name' => 'taxtype-change-request-view', 'sys_module_id' => 2],

            # Tax Consultant
            ['name' => 'registration-request-view', 'sys_module_id' => 3],
            ['name' => 'active-tax-consultant-view', 'sys_module_id' => 3],
            ['name' => 'renewal-requests-view', 'sys_module_id' => 3],
            ['name' => 'fee-configuration-view', 'sys_module_id' => 3],

            # Upgrade Tax Types
            ['name' => 'qualified-tax-types', 'sys_module_id' => 4],


            # Tax Returns
            ['name' => 'hotel-levy-view', 'sys_module_id' => 5],
            ['name' => 'tour-operation-view', 'sys_module_id' => 5],
            ['name' => 'hotel-levy-view', 'sys_module_id' => 5],
            ['name' => 'restaurant-levy-view', 'sys_module_id' => 5],
            ['name' => 'vat-return-view', 'sys_module_id' => 5],
            ['name' => 'port-return-view', 'sys_module_id' => 5],
            ['name' => 'stamp-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'bfo-excise-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'mno-excise-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'lump-sum-payment-return-view', 'sys_module_id' => 5],
            ['name' => 'mobile-money-transfer-view', 'sys_module_id' => 5],
            ['name' => 'electronic-money-transaction-return-view', 'sys_module_id' => 5],

            # Withholding Agent
            ['name' => 'withholding_agents_add', 'sys_module_id' => 6],
            ['name' => 'withholding_agents_edit', 'sys_module_id' => 6],
            ['name' => 'withholding_agents_view', 'sys_module_id' => 6],
            ['name' => 'withholding_agents_disable', 'sys_module_id' => 6],

            # Petroleum Management
            ['name' => 'quantity-of-certificate-view', 'sys_module_id' => 7],
            ['name' => 'petroleum-return-view', 'sys_module_id' => 7],

            # Return Verifications
            ['name' => 'verification-approval-view', 'sys_module_id' => 7],
            ['name' => 'verification-assessment-view', 'sys_module_id' => 7],
            ['name' => 'verification-approved-view', 'sys_module_id' => 7],


            # Tax Claim
            ['name' => 'tax-claim-view', 'sys_module_id' => 8],

            # Auditing
            ['name' => 'auditing-approval-view', 'sys_module_id' => 9],
            ['name' => 'auditing-assessment-view', 'sys_module_id' => 9],
            ['name' => 'auditing-approved-view', 'sys_module_id' => 9],

            # Investigation
            ['name' => 'investigation-approval-view', 'sys_module_id' => 10],
            ['name' => 'investigation-assessment-view', 'sys_module_id' => 10],
            ['name' => 'investigation-approved-view', 'sys_module_id' => 10],


            # Disputes Management
            ['name' => 'dispute-waiver-view', 'sys_module_id' => 11],
            ['name' => 'dispute-objection-view', 'sys_module_id' => 11],
            ['name' => 'dispute-waiver-objection-view', 'sys_module_id' => 11],

            # Relief Management
            ['name' => 'relief-ministries-view', 'sys_module_id' => 12],
            ['name' => 'relief-projects-view', 'sys_module_id' => 12],
            ['name' => 'relief-register-view', 'sys_module_id' => 12],
            ['name' => 'relief-generate-report-view', 'sys_module_id' => 12],

            # Tax Clearance Management
            ['name' => 'tax-clearance-view', 'sys_module_id' => 13],

            # Debt Management
            ['name' => 'debt-management-waiver-debt-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-assessment-debt-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-hotel-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-restaurant-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-tour-operation-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-petroleum-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-vat-return-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-stamp-duty-return-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-lump-sum-return-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-electronic-money-transaction-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-sea-services-transport-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-air-port-safety-fee-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-bfo-returns-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-mno-returns-view', 'sys_module_id' => 14],


            # Land Lease
            ['name' => 'land-lease-view', 'sys_module_id' => 15],
            ['name' => 'land-lease-generate-report', 'sys_module_id' => 15],

            # Manageria Report
            ['name' => 'managerial-report-view', 'sys_module_id' => 14],

            ['name' => 'mvr_initiate_registration', 'sys_module_id' => 15],
            ['name' => 'mvr_approve_registration', 'sys_module_id' => 15 ],
            ['name' => 'mvr_initiate_registration_change', 'sys_module_id' => 15 ],
            ['name' => 'mvr_approve_registration_change', 'sys_module_id' => 15 ],
            ['name' => 'receive_plate_number', 'sys_module_id' => 15 ],
            ['name' => 'print_plate_number', 'sys_module_id' => 15 ],
        ];
        foreach ($data as $row) {
            Permission::updateOrCreate($row);
        }
    }
}
