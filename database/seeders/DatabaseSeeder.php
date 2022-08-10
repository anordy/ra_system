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
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(BusinessFileTypesSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(IDTypesTableSeeder::class);
        $this->call(TaxTypesSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(WardSeeder::class);
        $this->call(SysModuleSeeder::class);
        $this->call(TaxTypesSeeder::class);
        $this->call(TaxpayersTableSeeder::class);
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(AccountTypesSeeder::class);
        $this->call(BusinessBranchWorkflow::class);
        $this->call(WorkflowBusinessClosureSeeder::class);
        $this->call(WorkflowBusinessDeregistrationSeeder::class);
        $this->call(WorkflowBusinessUpdateSeeder::class);
        $this->call(WorkflowBusinessTaxTypeChangeSeeder::class);
        $this->call(WorkflowTaxVerificationSeeder::class);
        $this->call(WorkflowTaxInvestigationSeeder::class);
        $this->call(WorkflowTaxAuditSeeder::class);
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
        // $this->call(AssesmentSeeder::class);
        $this->call(WorkflowWaiverSeeder::class);
        $this->call(InterestRateSeeder::class);
        $this->call(PenaltyRatesSeeder::class);
        $this->call(StampDutyConfigSeeder::class);
    }
}
