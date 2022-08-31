<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IDTypesTableSeeder::class);
        $this->call(SysModuleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AccountTypesSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(BusinessWorkflowSeeder::class);
        $this->call(BusinessActivitiesSeeder::class);
        $this->call(BusinessFileTypesSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(WardSeeder::class);
        $this->call(TaxTypesSeeder::class);
        $this->call(TaxpayersTableSeeder::class);
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(BusinessBranchWorkflow::class);
        $this->call(WorkflowBusinessClosureSeeder::class);
        $this->call(WorkflowBusinessDeregistrationSeeder::class);
        $this->call(WorkflowBusinessUpdateSeeder::class);
        $this->call(WorkflowBusinessTaxTypeChangeSeeder::class);
        $this->call(WorkflowTaxVerificationSeeder::class);
        $this->call(WorkflowTaxInvestigationSeeder::class);
        $this->call(WorkflowDebtWaiverSeeder::class);
        $this->call(WorkflowTaxAuditSeeder::class);
        $this->call(WorkflowDisputeSeeder::class);
        $this->call(WorkflowDrivingLicenseApplicationSeeder::class);
        $this->call(EducationLevelSeeder::class);
        $this->call(HotelReturnConfigSeeder::class);
        $this->call(PetroleumConfigSeeder::class);
        $this->call(PortConfigSeeder::class);
        $this->call(BFOConfigSeeder::class);
        $this->call(BFOConfigSeeder::class);
        $this->call(EmTransactionSeeder::class);
        $this->call(MmTransferSeeder::class);
        $this->call(FinancialYearSeeder::class);
        $this->call(VatReturnConfigSeeder::class);
        $this->call(ISIC1Seeder::class);
        $this->call(ISIC2Seeder::class);
        $this->call(ISIC3Seeder::class);
        $this->call(ISIC4Seeder::class);
        $this->call(MnoConfigSeeder::class);
        $this->call(ExchangeRateSeeder::class);
        $this->call(InterestRateSeeder::class);
        $this->call(PenaltyRatesSeeder::class);
        $this->call(StampDutyConfigSeeder::class);
        $this->call(BusinessSeeder::class);
        $this->call(WorkflowTaxClaimSeeder::class);
        $this->call(WorkflowExtensionSeeder::class);
        $this->call(WorkflowInstallmentSeeder::class);
        $this->call(WorkflowTaxClearenceSeeder::class);
        $this->call(TaxRegionsSeeder::class);
        $this->call(ReliefProjectSeeder::class);
        $this->call(DateConfigurationSeeder::class);
        $this->call(MvrRegistrationTypeCategoriesSeeder::class);
        $this->call(MvrRegistrationTypesSeeder::class);
        $this->call(MvrPlateNumberColorsTableSeeder::class);
        $this->call(MvrBodyTypeTableSeeder::class);
        $this->call(MvrClassesTableSeeder::class);
        $this->call(MvrFuelTypeTableSeeder::class);
        $this->call(MvrMakeTableSeeder::class);
        $this->call(MvrModelTableSeeder::class);
        $this->call(MvrFeeTypesTableSeeder::class);
        $this->call(MvrTransmissionTypeTableSeeder::class);
        $this->call(RecoveryMeasureCategoriesSeeder::class);
        $this->call(WorkflowRecoveryMeasureSeeder::class);
        $this->call(BloodGroupSeeder::class);
        $this->call(DLClassSeeder::class);
        $this->call(DLDurationSeeder::class);
        $this->call(DLFeeSeeder::class);
    }
}
