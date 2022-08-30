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
            ['name' => 'business-registration-view', 'sys_module_id' => 2],
            ['name' => 'business-certificate-view', 'sys_module_id' => 2],
            ['name' => 'business-branches-view', 'sys_module_id' => 2],
            ['name' => 'de-registration-view', 'sys_module_id' => 2],
            ['name' => 'temporary-closures-view', 'sys_module_id' => 2],
            ['name' => 'business-update-request-view', 'sys_module_id' => 2],
            ['name' => 'taxtype-change-request-view', 'sys_module_id' => 2],
            ['name' => 'qualified-tax-types-upgrade-view', 'sys_module_id' => 2],
            ['name' => 'qualified-tax-types-upgrade-add', 'sys_module_id' => 2],

            # Tax Consultant
            ['name' => 'tax-consultant-registration-view', 'sys_module_id' => 3],
            ['name' => 'active-tax-consultant-view', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-renewal-requests-view', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-fee-configuration-view', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-fee-configuration-add', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-registration-verify', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-registration-reject-first', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-registration-approve', 'sys_module_id' => 3],
            ['name' => 'tax-consultant-registration-reject-last', 'sys_module_id' => 3],

            # Tax Returns
            ['name' => 'return-hotel-levy-view', 'sys_module_id' => 5],
            ['name' => 'return-tour-operation-view', 'sys_module_id' => 5],
            ['name' => 'return-restaurant-levy-view', 'sys_module_id' => 5],
            ['name' => 'return-vat-return-view', 'sys_module_id' => 5],
            ['name' => 'return-port-return-view', 'sys_module_id' => 5],
            ['name' => 'return-stamp-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'return-bfo-excise-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'return-mno-excise-duty-return-view', 'sys_module_id' => 5],
            ['name' => 'return-lump-sum-payment-return-view', 'sys_module_id' => 5],
            ['name' => 'return-mobile-money-transfer-view', 'sys_module_id' => 5],
            ['name' => 'return-electronic-money-transaction-return-view', 'sys_module_id' => 5],

            # Withholding Agent
            ['name' => 'withholding-agents-registration', 'sys_module_id' => 6],
            ['name' => 'withholding-agents-view', 'sys_module_id' => 6],
            ['name' => 'withholding_agents_disable', 'sys_module_id' => 6],

            # Petroleum Management
            ['name' => 'certificate-of-quantity-view', 'sys_module_id' => 7],
            ['name' => 'certificate-of-quantity-create', 'sys_module_id' => 7],
            ['name' => 'return-petroleum-return-view', 'sys_module_id' => 7],

            # Return Verifications
            ['name' => 'verification-approval-view', 'sys_module_id' => 8],
            ['name' => 'verification-assessment-view', 'sys_module_id' => 8],
            ['name' => 'verification-approved-view', 'sys_module_id' => 8],

            # Tax Claim
            ['name' => 'tax-claim-view', 'sys_module_id' => 9],
            ['name' => 'tax-credit-view', 'sys_module_id' => 9],

            # Auditing
            ['name' => 'tax-auditing-approval-view', 'sys_module_id' => 10],
            ['name' => 'tax-auditing-assessment-view', 'sys_module_id' => 10],
            ['name' => 'tax-auditing-approved-view', 'sys_module_id' => 10],

            # Investigation
            ['name' => 'tax-investigation-approval-view', 'sys_module_id' => 11],
            ['name' => 'tax-investigation-assessment-view', 'sys_module_id' => 11],
            ['name' => 'tax-investigation-approved-view', 'sys_module_id' => 11],

            # Disputes Management
            ['name' => 'dispute-waiver-view', 'sys_module_id' => 12],
            ['name' => 'dispute-objection-view', 'sys_module_id' => 12],
            ['name' => 'dispute-waiver-objection-view', 'sys_module_id' => 12],

            # Tax Clearance Management
            ['name' => 'tax-clearance-view', 'sys_module_id' => 13],

            # Debt Management
            ['name' => 'debt-management-debts-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-debts-overdue-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-waiver-debt-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-assessment-debt-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-hotel-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-restaurant-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-tour-operator-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-petroleum-levy-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-vat-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-stamp-duty-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-lumpsum-payment-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-electronic-money-transaction-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-sea-service-transport-charge-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-airport-service-safety-fee-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-excise-duty-bfo-view', 'sys_module_id' => 14],
            ['name' => 'debt-management-excise-duty-mno-view', 'sys_module_id' => 14],

            ['name' => 'mvr_initiate_registration', 'sys_module_id' => 15],
            ['name' => 'mvr_approve_registration', 'sys_module_id' => 15],
            ['name' => 'mvr_initiate_registration_change', 'sys_module_id' => 15],
            ['name' => 'mvr_approve_registration_change', 'sys_module_id' => 15],
            ['name' => 'receive_plate_number', 'sys_module_id' => 15],
            ['name' => 'print_plate_number', 'sys_module_id' => 15],
            ['name' => 'mvr_register_agent', 'sys_module_id' => 15],
            ['name' => 'mvr_initiate_transfer', 'sys_module_id' => 15],
            ['name' => 'mvr_approve_transfer', 'sys_module_id' => 15],
            ['name' => 'mvr_initiate_de_registration', 'sys_module_id' => 15],
            ['name' => 'mvr_approve_de_registration', 'sys_module_id' => 15],

            # Land Lease
            ['name' => 'land-lease-create', 'sys_module_id' => 16],
            ['name' => 'land-lease-view', 'sys_module_id' => 16],
            ['name' => 'land-lease-edit', 'sys_module_id' => 16],
            ['name' => 'land-lease-delete', 'sys_module_id' => 16],
            ['name' => 'land-lease-view-own', 'sys_module_id' => 16],

            ['name' => 'land-lease-generate-report', 'sys_module_id' => 16],
            ['name' => 'land-lease-agent-view', 'sys_module_id' => 16],
            ['name' => 'land-lease-register-agent', 'sys_module_id' => 16],
            ['name' => 'land-lease-change-agent-status', 'sys_module_id' => 16],
            ['name' => 'land-lease-generate-control-number', 'sys_module_id' => 16],


            # Manage Payments
            ['name' => 'manage-payments-view', 'sys_module_id' => 17],

            # Setting
            ['name' => 'setting-user-view', 'sys_module_id' => 18],
            ['name' => 'setting-user-add', 'sys_module_id' => 18],
            ['name' => 'setting-user-edit', 'sys_module_id' => 18],
            ['name' => 'setting-user-delete', 'sys_module_id' => 18],
            ['name' => 'setting-role-view', 'sys_module_id' => 18],
            ['name' => 'setting-role-add', 'sys_module_id' => 18],
            ['name' => 'setting-role-edit', 'sys_module_id' => 18],
            ['name' => 'setting-role-delete', 'sys_module_id' => 18],
            ['name' => 'setting-country-view', 'sys_module_id' => 18],
            ['name' => 'setting-country-add', 'sys_module_id' => 18],
            ['name' => 'setting-country-edit', 'sys_module_id' => 18],
            ['name' => 'setting-country-delete', 'sys_module_id' => 18],
            ['name' => 'setting-region-view', 'sys_module_id' => 18],
            ['name' => 'setting-region-add', 'sys_module_id' => 18],
            ['name' => 'setting-region-edit', 'sys_module_id' => 18],
            ['name' => 'setting-region-delete', 'sys_module_id' => 18],
            ['name' => 'setting-district-view', 'sys_module_id' => 18],
            ['name' => 'setting-district-add', 'sys_module_id' => 18],
            ['name' => 'setting-district-edit', 'sys_module_id' => 18],
            ['name' => 'setting-district-delete', 'sys_module_id' => 18],
            ['name' => 'setting-ward-view', 'sys_module_id' => 18],
            ['name' => 'setting-ward-add', 'sys_module_id' => 18],
            ['name' => 'setting-ward-edit', 'sys_module_id' => 18],
            ['name' => 'setting-ward-delete', 'sys_module_id' => 18],
            ['name' => 'setting-bank-view', 'sys_module_id' => 18],
            ['name' => 'setting-bank-add', 'sys_module_id' => 18],
            ['name' => 'setting-bank-edit', 'sys_module_id' => 18],
            ['name' => 'setting-bank-delete', 'sys_module_id' => 18],
            ['name' => 'setting-exchange-rate-view', 'sys_module_id' => 18],
            ['name' => 'setting-exchange-rate-add', 'sys_module_id' => 18],
            ['name' => 'setting-exchange-rate-edit', 'sys_module_id' => 18],
            ['name' => 'setting-exchange-rate-delete', 'sys_module_id' => 18],
            ['name' => 'setting-education-level-view', 'sys_module_id' => 18],
            ['name' => 'setting-education-level-add', 'sys_module_id' => 18],
            ['name' => 'setting-education-level-edit', 'sys_module_id' => 18],
            ['name' => 'setting-education-level-delete', 'sys_module_id' => 18],
            ['name' => 'setting-business-category-view', 'sys_module_id' => 18],
            ['name' => 'setting-business-category-add', 'sys_module_id' => 18],
            ['name' => 'setting-business-category-edit', 'sys_module_id' => 18],
            ['name' => 'setting-business-category-delete', 'sys_module_id' => 18],
            ['name' => 'setting-tax-type-view', 'sys_module_id' => 18],
            ['name' => 'setting-tax-type-add', 'sys_module_id' => 18],
            ['name' => 'setting-tax-type-edit', 'sys_module_id' => 18],
            ['name' => 'setting-tax-type-delete', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-one-view', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-one-add', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-one-edit', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-one-delete', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-two-view', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-two-add', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-two-edit', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-two-delete', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-three-view', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-three-add', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-three-edit', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-three-delete', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-four-view', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-four-add', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-four-edit', 'sys_module_id' => 18],
            ['name' => 'setting-isic-level-four-delete', 'sys_module_id' => 18],
            ['name' => 'setting-business-file-view', 'sys_module_id' => 18],
            ['name' => 'setting-business-file-add', 'sys_module_id' => 18],
            ['name' => 'setting-business-file-edit', 'sys_module_id' => 18],
            ['name' => 'setting-business-file-delete', 'sys_module_id' => 18],
            ['name' => 'setting-tax-region-view', 'sys_module_id' => 18],
            ['name' => 'setting-tax-region-add', 'sys_module_id' => 18],
            ['name' => 'setting-tax-region-edit', 'sys_module_id' => 18],
            ['name' => 'setting-tax-region-delete', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-make-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-model-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-transmission-type-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-fuel-type-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-class-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-color-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-body-type-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-plate-size-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-fee-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-deregistration-reason-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-ownership-transfer-reason-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-transfer-category-view', 'sys_module_id' => 18],
            ['name' => 'setting-mvr-transfer-fee-view', 'sys_module_id' => 18],

            # System
            ['name' => 'system-audit-trail-view', 'sys_module_id' => 19],
            ['name' => 'system-workflow-view', 'sys_module_id' => 19],
            ['name' => 'system-workflow-configure', 'sys_module_id' => 19],
            ['name' => 'system-all-pdfs-view', 'sys_module_id' => 19],

            # Managerial Report
            ['name' => 'managerial-report-view', 'sys_module_id' => 20],

            # Extenstion
            ['name' => 'payment-extension-view', 'sys_module_id' => 21],

            # Installment
            ['name' => 'payment-installment-view', 'sys_module_id' => 22],
            ['name' => 'payment-installment-request-view', 'sys_module_id' => 22],

            # Relief Management
            ['name' => 'relief-ministries-view', 'sys_module_id' => 23],
            ['name' => 'relief-ministries-create', 'sys_module_id' => 23],
            ['name' => 'relief-ministries-edit', 'sys_module_id' => 23],
            ['name' => 'relief-ministries-delete', 'sys_module_id' => 23],

            ['name' => 'relief-projects-view', 'sys_module_id' => 23],
            ['name' => 'relief-projects-create', 'sys_module_id' => 23],
            ['name' => 'relief-projects-edit', 'sys_module_id' => 23],
            ['name' => 'relief-projects-delete', 'sys_module_id' => 23],
            ['name' => 'relief-projects-configure', 'sys_module_id' => 23],

            ['name' => 'relief-project-list-view', 'sys_module_id' => 23],
            ['name' => 'relief-projects-list-create', 'sys_module_id' => 23],
            ['name' => 'relief-projects-list-edit', 'sys_module_id' => 23],
            ['name' => 'relief-projects-list-delete', 'sys_module_id' => 23],

            ['name' => 'relief-registration-view', 'sys_module_id' => 23],
            ['name' => 'relief-registration-create', 'sys_module_id' => 23],

            ['name' => 'relief-applications-view', 'sys_module_id' => 23],
            ['name' => 'relief-applications-edit', 'sys_module_id' => 23],
            ['name' => 'relief-applications-delete', 'sys_module_id' => 23],

            ['name' => 'relief-generate-report-view', 'sys_module_id' => 23],
            ['name' => 'relief-generate-report', 'sys_module_id' => 23],

        ];

        foreach ($data as $row) {
            Permission::updateOrCreate($row);
        }
    }
}
