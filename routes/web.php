<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\AllPdfController;
use App\Http\Controllers\Assesments\DisputeController;
use App\Http\Controllers\Assesments\ObjectionController;
use App\Http\Controllers\Assesments\WaiverController;
use App\Http\Controllers\Assesments\WaiverObjectionController;
use App\Http\Controllers\Audit\TaxAuditApprovalController;
use App\Http\Controllers\Audit\TaxAuditAssessmentController;
use App\Http\Controllers\Audit\TaxAuditFilesController;
use App\Http\Controllers\Audit\TaxAuditVerifiedController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\Business\BranchController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Business\BusinessFileController;
use App\Http\Controllers\Business\BusinessUpdateFileController;
use App\Http\Controllers\Business\RegistrationController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Cases\CasesController;
use App\Http\Controllers\Claims\ClaimFilesController;
use App\Http\Controllers\Claims\ClaimsController;
use App\Http\Controllers\Claims\CreditsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Debt\AssessmentDebtController;
use App\Http\Controllers\Debt\ReturnDebtController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DriversLicense\LicenseApplicationsController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\Extension\ExtensionController;
use App\Http\Controllers\Finances\FinanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Installment\InstallmentController;
use App\Http\Controllers\Installment\InstallmentRequestController;
use App\Http\Controllers\Investigation\TaxInvestigationApprovalController;
use App\Http\Controllers\Investigation\TaxInvestigationAssessmentController;
use App\Http\Controllers\Investigation\TaxInvestigationFilesController;
use App\Http\Controllers\Investigation\TaxInvestigationVerifiedController;
use App\Http\Controllers\ISIC1Controller;
use App\Http\Controllers\ISIC2Controller;
use App\Http\Controllers\ISIC3Controller;
use App\Http\Controllers\ISIC4Controller;
use App\Http\Controllers\LandLease\LandLeaseController;
use App\Http\Controllers\MVR\AgentsController;
use App\Http\Controllers\MVR\DeRegistrationController;
use App\Http\Controllers\MVR\MotorVehicleRegistrationController;
use App\Http\Controllers\MVR\MvrGenericSettingController;
use App\Http\Controllers\MVR\OwnershipTransferController;
use App\Http\Controllers\MVR\RegistrationChangeController;
use App\Http\Controllers\MVR\TRAChassisSearchController;
use App\Http\Controllers\MVR\WrittenOffVehiclesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Payments\PaymentsController;
use App\Http\Controllers\QRCodeGeneratorController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Relief\ReliefApplicationsController;
use App\Http\Controllers\Relief\ReliefGenerateReportController;
use App\Http\Controllers\Relief\ReliefMinistriestController;
use App\Http\Controllers\Relief\ReliefProjectController;
use App\Http\Controllers\Relief\ReliefRegistrationController;
use App\Http\Controllers\Relief\ReliefSponsorController;
use App\Http\Controllers\Reports\Assessment\AssessmentReportController;
use App\Http\Controllers\Reports\Business\BusinessRegReportController;
use App\Http\Controllers\Reports\Claims\ClaimReportController;
use App\Http\Controllers\Reports\Debts\DebtReportController;
use App\Http\Controllers\Reports\Dispute\DisputeReportController;
use App\Http\Controllers\Reports\Returns\ReturnReportController;
use App\Http\Controllers\Returns\BfoExciseDuty\BfoExciseDutyController;
use App\Http\Controllers\Returns\EmTransaction\EmTransactionController;
use App\Http\Controllers\Returns\ExciseDuty\MnoReturnController;
use App\Http\Controllers\Returns\ExciseDuty\MobileMoneyTransferController;
use App\Http\Controllers\Returns\FinancialMonths\FinancialMonthsController;
use App\Http\Controllers\Returns\FinancialYears\FinancialYearsController;
use App\Http\Controllers\Returns\Hotel\HotelReturnController;
use App\Http\Controllers\Returns\LumpSum\LumpSumReturnController;
use App\Http\Controllers\Returns\Petroleum\PetroleumReturnController;
use App\Http\Controllers\Returns\Petroleum\QuantityCertificateController;
use App\Http\Controllers\Returns\Port\PortReturnController;
use App\Http\Controllers\Returns\PrintController;
use App\Http\Controllers\Returns\Queries\AllCreditReturnsController;
use App\Http\Controllers\Returns\Queries\NilReturnsController;
use App\Http\Controllers\Returns\Queries\NonFilersController;
use App\Http\Controllers\Returns\Queries\SalesPurchasesController;
use App\Http\Controllers\Returns\ReturnController;
use App\Http\Controllers\Returns\ReturnsController;
use App\Http\Controllers\Returns\SettingController;
use App\Http\Controllers\Returns\StampDuty\StampDutyReturnController;
use App\Http\Controllers\Returns\Vat\VatReturnController;
use App\Http\Controllers\RoadInspectionOffence\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\ExchangeRateController;
use App\Http\Controllers\Setting\InterestRateController;
use App\Http\Controllers\Setting\TaxRegionController;
use App\Http\Controllers\TaxAgents\TaxAgentController;
use App\Http\Controllers\TaxAgents\TaxAgentFileController;
use App\Http\Controllers\TaxClearance\TaxClearanceController;
use App\Http\Controllers\Taxpayers\RegistrationsController;
use App\Http\Controllers\Taxpayers\TaxpayersController;
use App\Http\Controllers\TaxTypeController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\UpgradeTaxType\QualifiedTaxTypeController;
use App\Http\Controllers\UpgradeTaxType\UpgradedTaxTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Verification\TaxVerificationApprovalController;
use App\Http\Controllers\Verification\TaxVerificationAssessmentController;
use App\Http\Controllers\Verification\TaxVerificationFilesController;
use App\Http\Controllers\Verification\TaxVerificationVerifiedController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\WithholdingAgentController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\ZanMalipoController;

//use App\Http\Controllers\Returns\HotelLevyReturnController;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);

Route::get('/pay', [ZanMalipoController::class, 'pay']); // TODO: remove on production

Route::get('checkCaptcha', [CaptchaController::class, 'reload'])->name('captcha.reload');

Route::middleware('auth')->group(function () {
    Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
    Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
    Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend');
    Route::get('/kill', [TwoFactorAuthController::class, 'kill'])->name('session.kill');

    Route::get('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'updatePassword'])->name('password.store');

});

Route::middleware(['firstLogin', '2fa', 'auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/account', [AccountController::class, 'show'])->name('account');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('/users', UserController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/country', CountryController::class);
        Route::resource('/region', RegionController::class);
        Route::resource('/district', DistrictController::class);
        Route::resource('/ward', WardController::class);
        Route::resource('/education-level', EducationLevelController::class);
        Route::resource('/banks', BankController::class);
        Route::resource('/business-categories', BusinessCategoryController::class);
        Route::resource('/taxtypes', TaxTypeController::class);
        Route::resource('/isic1', ISIC1Controller::class);
        Route::resource('/isic2', ISIC2Controller::class);
        Route::resource('/isic3', ISIC3Controller::class);
        Route::resource('/isic4', ISIC4Controller::class);
        Route::resource('/business-files', BusinessFileController::class);
        Route::resource('/exchange-rate', ExchangeRateController::class);
        Route::resource('/interest-rates', InterestRateController::class);
        Route::resource('/tax-regions', TaxRegionController::class);
        Route::get('financial-years', [FinancialYearsController::class, 'index'])->name('financial-years');
        Route::get('financial-months', [FinancialMonthsController::class, 'index'])->name('financial-months');
        Route::name('mvr-generic.')->prefix('mvr-generic')->group(function () {
            Route::get('/{model}', [MvrGenericSettingController::class, 'index'])
                ->name('index')
                ->where('model', 'CourtLevel|CaseDecision|CaseStage|CaseOutcome|CaseStage|DlFee|DlBloodGroup|DlLicenseClass|DlLicenseDuration|MvrTransferFee|MvrOwnershipTransferReason|MvrTransferCategory|MvrDeRegistrationReason|MvrFee|MvrBodyType|MvrClass|MvrFuelType|MvrMake|MvrModel|MvrMotorVehicle|MvrTransmissionType|MvrColor|MvrPlateSize');
        });
        Route::name('return-config.')->prefix('return-config')->group(function () {
            Route::get('/', [ReturnController::class, 'taxTypes'])->name('index');
            Route::get('/edit/{id}', [ReturnController::class, 'editTaxType'])->name('edit-tax-type');
            Route::get('/show/{id}', [ReturnController::class, 'showReturnConfigs'])->name('show');
            Route::get('/create/{id}/{code}', [ReturnController::class, 'create'])->name('create');
            Route::get('/edit/{id}/{code}/{config_id}', [ReturnController::class, 'edit'])->name('edit');
        });

        Route::get('vat-configuration/create', [VatReturnController::class, 'configCreate'])->name('vat-configuration-create');
    });

    Route::get('/bill_invoice/pdf/{id}', [QRCodeGeneratorController::class, 'invoice'])->name('bill.invoice');
    Route::get('bill_transfer/pdf/{id}', [QRCodeGeneratorController::class, 'transfer'])->name('bill.transfer');
    Route::get('bill_receipt/pdf/{id}', [QRCodeGeneratorController::class, 'receipt'])->name('bill.receipt');

    Route::name('returns.')->prefix('returns')->group(function () {
        Route::get('/stamp-duty', [SettingController::class, 'getStampDutySettings'])->name('stamp-duty');

        Route::name('returns.')->prefix('returns')->group(function () {
            Route::name('returns.')->prefix('returns')->group(function () {
                Route::get('/', [ReturnsController::class, 'index'])->name('index');
            });
        });
    });
    Route::name('verification.')->prefix('verification')->group(function () {
        Route::get('tin/{business}', [VerificationController::class, 'tin'])->name('tin');
    });

    Route::prefix('system')->name('system.')->group(function () {
        Route::resource('audits', AuditController::class);
        Route::resource('workflow', WorkflowController::class);
    });

    Route::prefix('taxpayers')->as('taxpayers.')->group(function () {
        Route::resource('/registrations', RegistrationsController::class); // KYC
        Route::get('registrations/enroll-fingerprint/{kyc_id}', [RegistrationsController::class, 'enrollFingerprint'])->name('enroll-fingerprint');
        Route::get('registrations/verify-user/{kyc_id}', [RegistrationsController::class, 'verifyUser'])->name('verify-user');
        Route::resource('taxpayer', TaxpayersController::class);
    });

    Route::resource('taxpayers', TaxpayersController::class);

    Route::prefix('withholdingAgents')->as('withholdingAgents.')->group(function () {
        Route::get('register', [WithholdingAgentController::class, 'registration'])->name('register');
        Route::get('list', [WithholdingAgentController::class, 'index'])->name('list');
        Route::get('view/{id}', [WithholdingAgentController::class, 'view'])->name('view');
        Route::get('certificate/{id}', [WithholdingAgentController::class, 'certificate'])->name('certificate');
    });

    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('all', [AllPdfController::class, 'index'])->name('all');
        Route::get('all/{file}', [AllPdfController::class, 'demandNotice'])->name('demand-notice');
    });

    Route::prefix('business')->as('business.')->group(function () {
        Route::get('/registrationsApproval/{id}', [RegistrationController::class, 'approval'])->name('registrations.approval'); // KYC
        Route::resource('registrations', RegistrationController::class);
        Route::get('/closure', [BusinessController::class, 'closure'])->name('closure');
        Route::get('/closure/{id}', [BusinessController::class, 'viewClosure'])->name('viewClosure');
        Route::get('/deregistration/{id}', [BusinessController::class, 'viewDeregistration'])->name('viewDeregistration');
        Route::get('/deregistrations', [BusinessController::class, 'deregistrations'])->name('deregistrations');
        Route::get('/change-taxtype', [BusinessController::class, 'taxTypeRequests'])->name('taxTypeRequests');
        Route::get('/change-taxtype/{id}', [BusinessController::class, 'viewTaxTypeRequest'])->name('viewTaxTypeRequest');

        Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
        Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');

        Route::get('/updates', [BusinessController::class, 'updatesRequests'])->name('updatesRequests');
        Route::get('/updates/{id}', [BusinessController::class, 'showRequest'])->name('showRequest');
        Route::get('/updates/{updateId}/file', [BusinessUpdateFileController::class, 'getContractFile'])->name('contract.file');

        Route::get('/business-file/{file}', [BusinessFileController::class, 'getBusinessFile'])->name('file');
        Route::get('/tin-file/{file}', [BusinessFileController::class, 'getTinFile'])->name('tin.file');
        Route::get('/business-certificate/{location}/taxType/{type}', [BusinessFileController::class, 'getCertificate'])->name('certificate');

        Route::get('/qualified-tax-types/', [QualifiedTaxTypeController::class, 'index'])->name('qualified-tax-types.index');
        Route::get('/qualified-tax-types/show/{id}/{tax_type_id}/{sales}', [QualifiedTaxTypeController::class, 'show'])->name('qualified-tax-types.show');

        Route::get('/upgraded-tax-types/', [UpgradedTaxTypeController::class, 'index'])->name('upgraded-tax-types.index');
        Route::get('/upgraded-tax-types/show/{id}', [UpgradedTaxTypeController::class, 'show'])->name('upgraded-tax-types.show');
    });

    // assesments
    Route::name('assesments.')->prefix('assesments')->group(function () {
        //objection
        Route::get('/objection/index', [ObjectionController::class, 'index'])->name('objection.index');
        Route::get('/objection/show/{objection_id}', [ObjectionController::class, 'show'])->name('objection.show');
        Route::get('/objection/files/{objection_id}', [ObjectionController::class, 'files'])->name('objection.files');
        Route::get('/objection/approval/{objection_id}', [ObjectionController::class, 'approval'])->name('objection.approval');
        //waiver
        Route::get('/waiver/index', [WaiverController::class, 'index'])->name('waiver.index');
        Route::get('/waiver/approval/{waiver_id}', [WaiverController::class, 'approval'])->name('waiver.approval');
        Route::get('/waiver/files/{waiver_id}', [WaiverController::class, 'files'])->name('waiver.files');
        Route::get('/waiver/show/{waiver_id}', [WaiverController::class, 'show'])->name('waiver.show');
        // both waiver objection
        Route::get('/waiverobjection/index', [WaiverObjectionController::class, 'index'])->name('waiverobjection.index');
        Route::get('/waiverobjection/show/{waiver_id}', [WaiverObjectionController::class, 'approval'])->name('waiverobjection.approval');
        Route::get('/objection/approval/{objection_id}', [ObjectionController::class, 'approval'])->name('objection.approval');
        Route::get('/waiverobjection/create/location/{location_id}/tax/{tax_type_id}', [WaiverObjectionController::class, 'create'])->name('waiverobjection.create');

        //Dispute
        Route::get('/dispute/index', [DisputeController::class, 'index'])->name('dispute.index');
        Route::get('/dispute/approval/{waiver_id}', [DisputeController::class, 'approval'])->name('dispute.approval');
        Route::get('/dispute/files/{waiver_id}', [DisputeController::class, 'files'])->name('dispute.files');
        Route::get('/dispute/show/{waiver_id}', [DisputeController::class, 'show'])->name('dispute.show');
    });

    Route::name('taxagents.')->prefix('taxagents')->group(function () {
        Route::get('/requests', [TaxAgentController::class, 'index'])->name('requests');
        Route::get('/request-show/{id}', [TaxAgentController::class, 'showAgentRequest'])->name('request-show');
        Route::get('/active', [TaxAgentController::class, 'activeAgents'])->name('active');
        Route::get('/show/{id}', [TaxAgentController::class, 'showActiveAgent'])->name('active-show');
        Route::get('/renew', [TaxAgentController::class, 'renewal'])->name('renew');
        Route::get('/renew/show/{id}', [TaxAgentController::class, 'renewalShow'])->name('renew-show');
        Route::get('/fee', [TaxAgentController::class, 'fee'])->name('fee');
        Route::get('/certificate/{id}', [TaxAgentController::class, 'certificate'])->name('certificate');
        Route::get('/requests-for-verification/{id}', [TaxAgentController::class, 'showVerificationAgentRequest'])->name('verification-show');
    });

    Route::name('returns.')->prefix('/e-filling')->group(function () {
        Route::resource('/petroleum', PetroleumReturnController::class);
        // airport
        Route::get('/airport/index', [PortReturnController::class, 'airport'])->name('airport.index');

        // seaport
        Route::get('/seaport/index', [PortReturnController::class, 'seaport'])->name('seaport.index');
        Route::get('/port/show/{return_id}', [PortReturnController::class, 'show'])->name('port.show');
        Route::get('/port/edit/{return_id}', [PortReturnController::class, 'edit'])->name('port.edit');

        Route::name('stamp-duty.')->group(function () {
            Route::get('/stamp-duty', [StampDutyReturnController::class, 'index'])->name('index');
            Route::get('/stamp-duty/{returnId}', [StampDutyReturnController::class, 'show'])->name('show');
        });

        Route::name('em-transaction.')->prefix('em-transaction')->group(function () {
            Route::get('/em-transactions', [EmTransactionController::class, 'index'])->name('index');
            Route::get('/view/{return_id}', [EmTransactionController::class, 'show'])->name('show');
        });

        Route::name('vat-return.')->prefix('vat-return')->group(function () {
            Route::get('/index', [VatReturnController::class, 'index'])->name('index');
            Route::get('/show/{id}', [VatReturnController::class, 'show'])->name('show');
        });

        Route::name('bfo-excise-duty.')->prefix('bfo-excise-duty')->group(function () {
            Route::get('/', [BfoExciseDutyController::class, 'index'])->name('index');
            Route::get('/show/{return_id}', [BfoExciseDutyController::class, 'show'])->name('show');
        });

        Route::name('mobile-money-transfer.')->prefix('mobile-money-transfer')->group(function () {
            Route::get('/', [MobileMoneyTransferController::class, 'index'])->name('index');
            Route::get('/show/{return_id}', [MobileMoneyTransferController::class, 'show'])->name('show');
        });

        Route::get('/hotel', [HotelReturnController::class, 'index'])->name('hotel.index');
        Route::get('/tour', [HotelReturnController::class, 'tour'])->name('tour.index');
        Route::get('/restaurant', [HotelReturnController::class, 'restaurant'])->name('restaurant.index');

        Route::get('/hotel/view/{return_id}', [HotelReturnController::class, 'show'])->name('hotel.show');
        Route::get('/hotel/adjust/{return_id}', [HotelReturnController::class, 'adjust'])->name('hotel.adjust');

        Route::name('excise-duty.')->prefix('excise-duty')->group(function () {
            Route::get('/mno', [MnoReturnController::class, 'index'])->name('mno');
            Route::get('/mno/{return_id}', [MnoReturnController::class, 'show'])->name('mno.show');
        });

        Route::get('/lump-sum/index', [LumpSumReturnController::class, 'index'])->name('lump-sum.index');
        Route::get('/lump-sum/view/{id}', [LumpSumReturnController::class, 'view'])->name('lump-sum.show');
        Route::get('/lump-sum/history/{filters}', [LumpSumReturnController::class, 'history'])->name('lump-sum.history');

        // Print Returns
        Route::get('/print/{tax_return_id}', [PrintController::class, 'print'])->name('print');
    });

    Route::name('petroleum.')->prefix('petroleum')->group(function () {
        Route::resource('/filling', PetroleumReturnController::class);
        Route::resource('/certificateOfQuantity', QuantityCertificateController::class);
        Route::get('/certificateOfQuantityFile/{id}', [QuantityCertificateController::class, 'certificate'])->name('certificateOfQuantity.certificate');
    });

    Route::name('queries.')->prefix('queries')->group(function () {
        Route::get('/sales-purchases', [SalesPurchasesController::class, 'index'])->name('sales-purchases');
        Route::get('/sales-purchases/show/{id}', [SalesPurchasesController::class, 'show'])->name('sales-purchases.show');
        Route::get('/all-credit-returns', [AllCreditReturnsController::class, 'index'])->name('all-credit-returns');
        Route::get('/all-credit-returns/show/{id}/{return_id}/{sales}', [AllCreditReturnsController::class, 'show'])->name('all-credit-returns.show');
        Route::get('/non-filers', [NonFilersController::class, 'index'])->name('non-filers');
        Route::get('/non-filers/show/{id}', [NonFilersController::class, 'show'])->name('non-filers.show');
        Route::get('/nil-returns', [NilReturnsController::class, 'index'])->name('nil-returns');
        Route::get('/nil-returns/show/{id}', [NilReturnsController::class, 'show'])->name('nil-returns.show');
    });

    Route::name('reliefs.')->prefix('reliefs')->group(function () {
        Route::resource('/ministries', ReliefMinistriestController::class);
        Route::resource('/sponsors', ReliefSponsorController::class);
        Route::resource('/registrations', ReliefRegistrationController::class);
        Route::resource('/projects', ReliefProjectController::class);
        Route::resource('/applications', ReliefApplicationsController::class);
        Route::get('/get-attachment/{path}', [ReliefApplicationsController::class, 'getAttachment'])->name('get.attachment');
        Route::get('/generate-report', [ReliefGenerateReportController::class, 'index'])->name('generate.report');
        Route::get('/download-report-pdf/{payload}', [ReliefGenerateReportController::class, 'downloadReliefReportPdf'])->name('download.report.pdf');
        Route::get('/generate-report/report-preview/ceiling/{payload}', [ReliefGenerateReportController::class, 'ceilingReport'])->name('report.ceiling.preview');
        Route::get('/generate-report/report-preview/{payload}', [ReliefGenerateReportController::class, 'reportPreview'])->name('report.preview');
    });

    Route::name('tax_verifications.')->prefix('tax_verifications')->group(function () {
        Route::resource('/approvals', TaxVerificationApprovalController::class);
        Route::resource('/assessments', TaxVerificationAssessmentController::class);
        Route::resource('/verified', TaxVerificationVerifiedController::class);
        Route::resource('/files', TaxVerificationFilesController::class);
    });

    Route::name('tax_auditing.')->prefix('tax_auditing')->group(function () {
        Route::resource('/approvals', TaxAuditApprovalController::class);
        Route::resource('/assessments', TaxAuditAssessmentController::class);
        Route::resource('/verified', TaxAuditVerifiedController::class);
        Route::resource('/files', TaxVerificationFilesController::class);
    });

    Route::resource('/files', TaxAuditFilesController::class);

    //Managerial Reports
    Route::name('reports.')->prefix('reports')->group(function () {
        Route::get('/returns', [ReturnReportController::class, 'index'])->name('returns');
        Route::get('/returns/preview/{parameters}', [ReturnReportController::class, 'preview'])->name('returns.preview');
        Route::get('/returns/download-report-pdf/{data}', [ReturnReportController::class, 'exportReturnReportPdf'])->name('returns.download.pdf');

        Route::get('/business', [BusinessRegReportController::class, 'init'])->name('business.init');
        Route::get('/business/preview/{parameters}', [BusinessRegReportController::class, 'preview'])->name('business.preview');
        Route::get('/business/download-report-pdf/{data}', [BusinessRegReportController::class, 'exportBusinessesReportPdf'])->name('business.download.pdf');

        //  Assesment Report
        Route::get('/assesments', [AssessmentReportController::class, 'index'])->name('assesments');
        Route::get('/assessments/download-report-pdf/{data}', [AssessmentReportController::class, 'exportAssessmentReportPdf'])->name('assessments.download.pdf');
        Route::get('/assessments/preview/{parameters}', [AssessmentReportController::class, 'preview'])->name('assessments.preview');

        //  Disputes Report
        Route::get('/disputes', [DisputeReportController::class, 'index'])->name('disputes');
        Route::get('/disputes/download-report-pdf/{data}', [DisputeReportController::class, 'exportDisputeReportPdf'])->name('disputes.download.pdf');
        Route::get('/disputes/preview/{parameters}', [DisputeReportController::class, 'preview'])->name('disputes.preview');

        //Claim Report
        Route::get('/claims', [ClaimReportController::class, 'init'])->name('claims.init');
        Route::get('/claims/preview/{parameters}', [ClaimReportController::class, 'preview'])->name('claims.preview');
        Route::get('/claims/download-report-pdf/{data}', [ClaimReportController::class, 'exportClaimReportPdf'])->name('claim.download.pdf');

        // Debt Reports
        Route::get('/debts', [DebtReportController::class, 'index'])->name('debts');
        Route::get('/debts/preview/{parameters}', [DebtReportController::class, 'preview'])->name('debts.preview');
        Route::get('/debts/download-report-pdf/{data}', [DebtReportController::class, 'exportDebtReportPdf'])->name('debts.download.pdf');
    });

    Route::name('claims.')->prefix('/tax-claims')->group(function () {
        Route::get('/', [ClaimsController::class, 'index'])->name('index');
        Route::get('/{claim}', [ClaimsController::class, 'show'])->name('show');
        Route::get('/{claim}/approve', [ClaimsController::class, 'approve'])->name('approve');
        Route::get('/files/{file}', [ClaimFilesController::class, 'show'])->name('files.show');
    });

    Route::name('credits.')->prefix('/tax-credits')->group(function () {
        Route::get('/', [CreditsController::class, 'index'])->name('index');
        Route::get('/{credit}', [CreditsController::class, 'show'])->name('show');
    });

    Route::name('extension.')->prefix('/extensions-e-filling')->group(function () {
        Route::get('/', [ExtensionController::class, 'index'])->name('index');
        Route::get('show/{debtId}', [ExtensionController::class, 'show'])->name('show');
        Route::get('file/{file}', [ExtensionController::class, 'file'])->name('file');
    });

    Route::name('installment.')->prefix('/installments-e-filling')->group(function () {
        Route::get('/', [InstallmentController::class, 'index'])->name('index');
        Route::get('/show/{installmentId}', [InstallmentController::class, 'show'])->name('show');

        Route::prefix('/requests')->as('requests.')->group(function () {
            Route::get('/', [InstallmentRequestController::class, 'index'])->name('index');
            Route::get('create/{debtId}', [InstallmentRequestController::class, 'create'])->name('create');
            Route::get('show/{debtId}', [InstallmentRequestController::class, 'show'])->name('show');
            Route::get('file/{file}', [InstallmentRequestController::class, 'file'])->name('file');
        });
    });

    Route::name('debts.')->prefix('/debts')->group(function () {
        // Return debts
        Route::get('/returns', [ReturnDebtController::class, 'index'])->name('returns.index');
        Route::get('/returns/recovery-measure/{debtId}', [ReturnDebtController::class, 'recovery'])->name('debt.recovery');
        Route::get('/returns/show/{debtId}', [ReturnDebtController::class, 'show'])->name('return.show');
        Route::get('/returns/overdue/show/{debtId}', [ReturnDebtController::class, 'showOverdue'])->name('return.showOverdue');
        Route::get('/demand-notice/send/{debtId}', [ReturnDebtController::class, 'sendDemandNotice'])->name('return.sendDemandNotice');

        Route::get('/waivers', [ReturnDebtController::class, 'waivers'])->name('waivers.index');
        Route::get('/returns/waiver/show/{waiverId}', [ReturnDebtController::class, 'approval'])->name('returns.waivers.approval');

        Route::get('/assessments', [AssessmentDebtController::class, 'index'])->name('assessments.index');
        Route::get('/assesments/show/{assessment_id}', [AssessmentDebtController::class, 'show'])->name('assessment.show');
        Route::get('/assessment/waiver/show/{assessment_id}', [AssessmentDebtController::class, 'showWaiver'])->name('assessment.waiver.show');
        Route::get('/assessments/waiver/show/{waiverId}', [AssessmentDebtController::class, 'approval'])->name('assessments.waivers.approval');
    });

    Route::name('tax_investigation.')->prefix('tax_investigation')->group(function () {
        Route::resource('/approvals', TaxInvestigationApprovalController::class);
        Route::resource('/assessments', TaxInvestigationAssessmentController::class);
        Route::resource('/verified', TaxInvestigationVerifiedController::class);
        Route::resource('/files', TaxInvestigationFilesController::class);
    });

    Route::get('agent-file/{file}/{type}', [TaxAgentFileController::class, 'getAgentFile'])->name('agent.file');
    Route::get('agent-academics-file/{file}/{type}', [TaxAgentFileController::class, 'getAgentAcademicFile'])->name('agent.academics-file');
    Route::get('agent-professionals-file/{file}/{type}', [TaxAgentFileController::class, 'getAgentProfessionalFile'])->name('agent.professionals-file');
    Route::get('agent-trainings-file/{file}/{type}', [TaxAgentFileController::class, 'getAgentTrainingFile'])->name('agent.trainings-file');

    Route::name('land-lease.')->prefix('land-lease')->group(function () {
        Route::get('/list', [LandLeaseController::class, 'index'])->name('list');
        Route::get('/view/{id}', [LandLeaseController::class, 'view'])->name('view');
        Route::get('/view/lease/payment/{id}', [LandLeaseController::class, 'viewLeasePayment'])->name('view.lease.payment');
        Route::get('/agreement-doc/{path}', [LandLeaseController::class, 'getAgreementDocument'])->name('get.lease.document');
        Route::get('/generate-report', [LandLeaseController::class, 'generateReport'])->name('generate.report');
        Route::get('/payment-report', [LandLeaseController::class, 'paymentReport'])->name('payment.report');
        Route::get('/agents', [LandLeaseController::class, 'agentsList'])->name('agents');
        Route::get('/agent/status-change/{payload}', [LandLeaseController::class, 'agentStatusChange'])->name('agent.status.change');
        Route::get('/agent/create', [LandLeaseController::class, 'createAgent'])->name('agent.create');
        Route::get('/download-report-pdf/{dates}', [LandLeaseController::class, 'downloadLandLeaseReportPdf'])->name('download.report.pdf');
        Route::get('/payment/download-report-pdf/{dates}', [LandLeaseController::class, 'downloadLandLeasePaymentReportPdf'])->name('payment.download.report.pdf');
    });

    //Tax Clearance
    Route::name('tax-clearance.')->prefix('tax-clearance')->group(function () {
        Route::get('/tax-clearance/index', [TaxClearanceController::class, 'index'])->name('index');
        Route::get('/tax-clearance/view/{id}', [TaxClearanceController::class, 'viewRequest'])->name('request.view');
        Route::get('/tax-clearance/approval/{id}', [TaxClearanceController::class, 'approval'])->name('request.approval');
        Route::get('/tax-clearance/certificate/{location}', [TaxClearanceController::class, 'certificate'])->name('certificate');
    });

    Route::name('payments.')->prefix('payments')->group(function () {
        Route::get('/complete', [PaymentsController::class, 'complete'])->name('complete');
        Route::get('/pending', [PaymentsController::class, 'pending'])->name('pending');
        Route::get('/cancelled', [PaymentsController::class, 'cancelled'])->name('cancelled');
        Route::get('/failed', [PaymentsController::class, 'failed'])->name('failed');
        Route::get('/recons/{reconId}', [PaymentsController::class, 'recons'])->name('recons');
        Route::get('/recons/transaction/{transactionId}', [PaymentsController::class, 'viewReconTransaction'])->name('recons.transaction');
        Route::get('/recon-enquire', [PaymentsController::class, 'reconEnquire'])->name('recon.enquire');
        Route::get('/{paymentId}', [PaymentsController::class, 'show'])->name('show');
    });

    Route::prefix('mvr')->as('mvr.')->group(function () {
        Route::get('/register', [MotorVehicleRegistrationController::class, 'index'])->name('register');
        Route::get('/registered', [MotorVehicleRegistrationController::class, 'registeredIndex'])->name('registered');
        Route::get('/plate-numbers', [MotorVehicleRegistrationController::class, 'plateNumbers'])->name('plate-numbers');
        Route::get('/change-status', [MotorVehicleRegistrationController::class, 'index'])->name('change-status');
        Route::get('/view/{id}', [MotorVehicleRegistrationController::class, 'show'])->name('show');
        Route::get('/certificate-of-registration/{id}', [MotorVehicleRegistrationController::class, 'registrationCertificate'])->name('certificate-of-registration');
        Route::get('/certificate-of-worth/{id}', [MotorVehicleRegistrationController::class, 'printCertificateOfWorth'])->name('certificate-of-worth');
        Route::get('/de-registration-certificate/{id}', [MotorVehicleRegistrationController::class, 'deRegistrationCertificate'])->name('de-registration-certificate');
        Route::get('/submit-inspection/{id}', [MotorVehicleRegistrationController::class, 'submitInspection'])->name('submit-inspection');
        Route::get('/transfer-ownership', [OwnershipTransferController::class, 'index'])->name('transfer-ownership');
        Route::get('/transfer-ownership/approve/{id}', [OwnershipTransferController::class, 'approve'])->name('transfer-ownership.approve');
        Route::get('/transfer-ownership/reject/{id}', [OwnershipTransferController::class, 'reject'])->name('transfer-ownership.reject');
        Route::get('/transfer-ownership/{id}', [OwnershipTransferController::class, 'show'])->name('transfer-ownership.show');
        Route::get('/de-register-requests', [DeRegistrationController::class, 'index'])->name('de-register-requests');
        Route::get('/de-register-requests/approve/{id}', [DeRegistrationController::class, 'approve'])->name('de-register-requests.approve');
        Route::get('/de-register-requests/reject/{id}', [DeRegistrationController::class, 'reject'])->name('de-register-requests.reject');
        Route::get('/de-register-requests/submit/{id}', [DeRegistrationController::class, 'zbsSubmit'])->name('de-register-requests.submit');
        Route::get('/de-register-requests/{id}', [DeRegistrationController::class, 'show'])->name('de-register-requests.show');
        Route::get('/reg-change-requests', [RegistrationChangeController::class, 'index'])->name('reg-change-requests');
        Route::get('/reg-change-requests/approve/{id}', [RegistrationChangeController::class, 'approve'])->name('reg-change-requests.approve');
        Route::get('/reg-change-requests/reject/{id}', [RegistrationChangeController::class, 'reject'])->name('reg-change-requests.reject');
        Route::get('/reg-change-requests/{id}', [RegistrationChangeController::class, 'show'])->name('reg-change-requests.show');
        Route::get('/written-off', [WrittenOffVehiclesController::class, 'index'])->name('written-off');
        Route::get('/chassis-search/{chassis}', [TRAChassisSearchController::class, 'search'])->name('chassis-search');
        Route::get('/agent', [AgentsController::class, 'index'])->name('agent');
        Route::get('/agent/create', [AgentsController::class, 'create'])->name('agent.create');
        Route::get('/reg-change-chassis-search/{type}/{number}', [RegistrationChangeController::class, 'search'])
            ->name('internal-search')->where('type', 'plate-number|chassis');
        Route::get('/de-registration-chassis-search/{type}/{number}', [DeRegistrationController::class, 'search'])
            ->name('internal-search-dr')->where('type', 'plate-number|chassis');
        Route::get('/ownership-transfer-chassis-search/{type}/{number}', [OwnershipTransferController::class, 'search'])
            ->name('internal-search-ot')->where('type', 'plate-number|chassis');
        Route::get('/written-off-chassis-search/{type}/{number}', [WrittenOffVehiclesController::class, 'search'])
            ->name('internal-search-wo')->where('type', 'plate-number|chassis');
        Route::get('/files/{path}', [MotorVehicleRegistrationController::class, 'showFile'])->name('files');
        Route::get('/sp-rg/{id}', [MotorVehicleRegistrationController::class, 'simulatePayment']); //todo: remove
        Route::get('/sp-rc/{id}', [RegistrationChangeController::class, 'simulatePayment']); //todo: remove
        Route::get('/sp-dr/{id}', [DeRegistrationController::class, 'simulatePayment']); //todo: remove
        Route::get('/sp-ot/{id}', [OwnershipTransferController::class, 'simulatePayment']); //todo: remove
    });

    Route::prefix('drivers-license')->as('drivers-license.')->group(function () {
        Route::get('/license', [LicenseApplicationsController::class, 'indexLicense'])->name('licenses');
        Route::get('/license/{id}', [LicenseApplicationsController::class, 'showLicense'])->name('licenses.show');
        Route::get('/applications', [LicenseApplicationsController::class, 'index'])->name('applications');
        Route::get('/applications/create', [LicenseApplicationsController::class, 'create'])->name('applications.create');
        Route::get('/applications/submit/{id}', [LicenseApplicationsController::class, 'submit'])->name('applications.submit');
        Route::get('/applications/approve/{id}', [LicenseApplicationsController::class, 'approve'])->name('applications.approve');
        Route::get('/applications/printed/{id}', [LicenseApplicationsController::class, 'printed'])->name('applications.printed');
        Route::get('/applications/{id}', [LicenseApplicationsController::class, 'show'])->name('applications.show');
        Route::get('/applications/sp/{id}', [LicenseApplicationsController::class, 'simulatePayment'])->name('applications.sp');
        Route::get('/applications/license/{id}', [LicenseApplicationsController::class, 'license'])->name('license.print');
        Route::get('/applications/file/{location}', [LicenseApplicationsController::class, 'getFile'])->name('license.file');
    });

    Route::prefix('rio')->as('rio.')->group(function () {
        Route::get('/register', [RegisterController::class, 'index'])->name('register');
        Route::get('/register/create', [RegisterController::class, 'create'])->name('register.create');
        Route::get('/register/remove-restriction/{id}', [RegisterController::class, 'removeRestriction'])->name('register.remove-restriction');
        Route::get('/register/{id}', [RegisterController::class, 'show'])->name('register.show');
    });

    Route::prefix('cases')->as('cases.')->group(function () {
        Route::get('/', [CasesController::class, 'index'])->name('index');
        Route::get('/show/{id}', [CasesController::class, 'show'])->name('show');
        Route::get('/appeals', [CasesController::class, 'appealsIndex'])->name('appeals');
        Route::get('/appeals/{id}', [CasesController::class, 'appealShow'])->name('appeal.show');
    });

    Route::get('/control-number/retry/{id}', [LicenseApplicationsController::class, 'retryControlNumber'])->name('control-number.retry');

    // Finance
    Route::name('finance.')->prefix('finance')->group(function () {
        Route::get('/taxpayer/ledger', [FinanceController::class, 'taxpayerLedgersList'])->name('taxpayer.ledgers');
        Route::get('/taxpayer/ledger/{id}', [FinanceController::class, 'taxpayerLedger'])->name('taxpayer.ledger.details');
    });
});
