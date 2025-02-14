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
        $this->call(BusinessActivitiesSeeder::class);
        $this->call(BusinessFileTypesSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(ZRBBankAccountSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(WardSeeder::class);
        $this->call(TaxTypesSeeder::class);
        $this->call(SubVatSeeder::class);
        $this->call(TaxpayersTableSeeder::class);
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(EducationLevelSeeder::class);
        $this->call(HotelReturnConfigSeeder::class);
        $this->call(PetroleumConfigSeeder::class);
        $this->call(PortConfigSeeder::class);
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
        $this->call(SystemSettingsSeeder::class);
        $this->call(StampDutyConfigSeeder::class);
        $this->call(BusinessSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(TaxRegionsSeeder::class);
        $this->call(ReliefProjectSeeder::class);
        $this->call(DateConfigurationSeeder::class);
        $this->call(MvrBodyTypeTableSeeder::class);
        $this->call(MvrFuelTypeTableSeeder::class);
        $this->call(MvrMakeTableSeeder::class);
        $this->call(MvrModelTableSeeder::class);
        $this->call(MvrFeeTypesTableSeeder::class);
        $this->call(MvrTransmissionTypeTableSeeder::class);
        $this->call(MvrTransferReasonTableSeeder::class);
        $this->call(DeRegistrationReasonsStatusSeeder::class);
        $this->call(RecoveryMeasureCategoriesSeeder::class);
        $this->call(BloodGroupSeeder::class);
        $this->call(DLFeeSeeder::class);
        $this->call(DlClassAdditionFeeTableSeeder::class);
        $this->call(DlClassAdditionFeeTableSeeder::class);
        $this->call(DlClassAdditionFeeTableSeeder::class);
        $this->call(DlRestrictionsSeeder::class);
        $this->call(SequencesTableSeeder::class);
        $this->call(MainRegionSeeder::class);
        $this->call(TaxTypePrefixSeeder::class);
        $this->call(WorkflowTaxClaimSeeder::class);
        $this->call(WorkflowExtensionSeeder::class);
        $this->call(WorkflowInstallmentSeeder::class);
        $this->call(WorkflowTaxClearenceSeeder::class);
        $this->call(WorkflowRecoveryMeasureSeeder::class);
        $this->call(BusinessWorkflowSeeder::class);
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
        $this->call(WorkflowTaxConsultantSeeder::class);
        $this->call(WorkflowRenewTaxConsultantSeeder::class);
        $this->call(ApprovalLevelsSeeder::class);
        $this->call(TransactionFeesTableSeeder::class);
        $this->call(WorkflowTaxpayerDetailsAmendmentSeeder::class);
        $this->call(WorkflowKYCDetailsAmendmentSeeder::class);
        $this->call(WorkflowTaxReturnVettingSeeder::class);
        $this->call(WorkflowCertificateOfQuantitySeeder::class);
        $this->call(StreetTableSeeder::class);
        $this->call(SubSysModuleSeeder::class);
        $this->call(ApiUserTableSeeder::class);
        $this->call(LumpSumPaymentSeeder::class);
        $this->call(LumpSumConfigSeeder::class);
        $this->call(Vat18ReturnConfigSeeder::class);
        $this->call(SecurityQuestionsSeeder::class);
        $this->call(HotelStarsSeeder::class);
        $this->call(WorkflowInternalBusinessInfoChangeSeeder::class);
        $this->call(WorkflowPropertyTaxRegistrationSeeder::class);
        $this->call(PropertyTaxHotelStarsSeeder::class);
        $this->call(WorkflowPropertyTaxExtensionApprovalSeeder::class);
        $this->call(PropertyOwnershipTypeSeeder::class);
        $this->call(WorkflowUpgradeTaxTypeSeeder::class);
        $this->call(WorkflowWithholdingAgentSeeder::class);
        $this->call(WorkflowPublicServiceDeRegistrationSeeder::class);
        $this->call(PublicServicePaymentCategorySeeder::class);
        $this->call(PublicServicePaymentsIntervalSeeder::class);
        $this->call(RiskIndicatorsSeeder::class);
        $this->call(WorkflowPublicServiceTemporaryClosureSeeder::class);
        $this->call(WorkflowPublicServiceDeRegistrationSeeder::class);
        $this->call(WorkflowPublicServiceRegistrationSeeder::class);
        $this->call(WorkflowMvrRegistrationStatusChangeSeeder::class);
        $this->call(WorkflowMvrRegistrationParticularChangeSeeder::class);
        $this->call(WorkflowMvrTransferOwnershipSeeder::class);
        $this->call(WorkflowMvrDeRegistrationSeeder::class);
        $this->call(WorkflowMvrRoadLicenseSeeder::class);
        $this->call(WorkflowMvrTemporaryTransportSeeder::class);
        $this->call(CharteredConfigSeeder::class);
        $this->call(HotelNatureSeeder::class);
        $this->call(CountriesPhoneCodeTableSeeder::class);
        $this->call(NtrSocialAccountsSeeder::class);
        $this->call(NtrBusinessCategorySeeder::class);
        $this->call(NtrBusinessAttachmentTypeSeeder::class);
        $this->call(TraSeeder::class);
        $this->call(NtrSeeders::class);
        $this->call(ReportsSeeder::class);
        $this->call(MvrSeeder::class);
        $this->call(DesignationsSeeder::class);
        $this->call(MvrPlateNumberTypeSeeder::class);
        $this->call(WorkflowMvrReorderPlateNumberSeeder::class);
        $this->call(NtrSeeders::class);
        $this->call(ReportsSeeder::class);
        $this->call(MvrSeeder::class);
    }
}
