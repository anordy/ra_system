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

use App\Http\Controllers\AllPdfController;
use App\Http\Controllers\Assesments\ObjectionController;
use App\Http\Controllers\Assesments\WaiverController;
use App\Http\Controllers\Assesments\WaiverObjectionController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Audit\TaxAuditApprovalController;
use App\Http\Controllers\Audit\TaxAuditAssessmentController;
use App\Http\Controllers\Audit\TaxAuditFilesController;
use App\Http\Controllers\Audit\TaxAuditVerifiedController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\Business\BranchController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\Business\BusinessFileController;
use App\Http\Controllers\Business\BusinessUpdateFileController;
use App\Http\Controllers\Business\RegistrationController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Claims\ClaimFilesController;
use App\Http\Controllers\Claims\ClaimsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Debt\AssessmentDebtController;
use App\Http\Controllers\Debt\DebtController;
use App\Http\Controllers\Debt\ReturnDebtController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\Extension\ExtensionController;
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
use App\Http\Controllers\MVR\AgentsController;
use App\Http\Controllers\MVR\DeRegistrationController;
use App\Http\Controllers\MVR\MotorVehicleRegistrationController;
use App\Http\Controllers\MVR\MvrGenericSettingController;
use App\Http\Controllers\MVR\OwnershipTransferController;
use App\Http\Controllers\MVR\RegistrationChangeController;
use App\Http\Controllers\MVR\TRAChassisSearchController;
use App\Http\Controllers\LandLease\LandLeaseController;
use App\Http\Controllers\MVR\WrittenOffVehiclesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Payments\PaymentsController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Relief\ReliefApplicationsController;
use App\Http\Controllers\Relief\ReliefGenerateReportController;
use App\Http\Controllers\Relief\ReliefMinistriestController;
use App\Http\Controllers\Relief\ReliefProjectController;
use App\Http\Controllers\Relief\ReliefRegistrationController;
use App\Http\Controllers\Reports\Returns\ReturnReportController;
use App\Http\Controllers\Returns\BfoExciseDuty\BfoExciseDutyController;
use App\Http\Controllers\Returns\EmTransaction\EmTransactionController;
use App\Http\Controllers\Returns\ExciseDuty\MnoReturnController;
use App\Http\Controllers\Returns\ExciseDuty\MobileMoneyTransferController;
use App\Http\Controllers\Returns\HotelLevyReturnController;
use App\Http\Controllers\Returns\Hotel\HotelReturnController;
use App\Http\Controllers\Returns\LumpSum\LumpSumReturnController;
use App\Http\Controllers\Returns\Petroleum\PetroleumReturnController;
use App\Http\Controllers\Returns\Petroleum\QuantityCertificateController;
use App\Http\Controllers\Returns\Port\PortReturnController;
use App\Http\Controllers\Returns\Queries\AllCreditReturnsController;
use App\Http\Controllers\Returns\Queries\NonFilersController;
use App\Http\Controllers\Returns\Queries\SalesPurchasesController;
use App\Http\Controllers\Returns\ReturnsController;
use App\Http\Controllers\Returns\SettingController;
use App\Http\Controllers\Returns\StampDuty\StampDutyReturnController;
use App\Http\Controllers\Returns\Vat\VatReturnController;
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
use App\Http\Controllers\UpgradeTaxType\UpgradeTaxtypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Verification\TaxVerificationApprovalController;
use App\Http\Controllers\Verification\TaxVerificationAssessmentController;
use App\Http\Controllers\Verification\TaxVerificationFilesController;
use App\Http\Controllers\Verification\TaxVerificationVerifiedController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\WithholdingAgentController;
use App\Http\Controllers\WorkflowController;
use App\Http\Livewire\Reports\Returns\ReturnReport;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index']);

Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend');
Route::get('checkCaptcha', [CaptchaController::class, 'reload'])->name('captcha.reload');
Route::get('password/change/{user}', [ChangePasswordController::class, 'index'])->name('password.change');
Route::post('password/save-changed', [ChangePasswordController::class, 'updatePassword'])->name('password.save-changed');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

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
        Route::resource('/assesment-files', AssesmentFileController::class);
        Route::resource('/exchange-rate', ExchangeRateController::class);
        Route::resource('/tax-regions', TaxRegionController::class);
        Route::name('mvr-generic.')->prefix('mvr-generic')->group(function(){
            Route::get('/{model}', [MvrGenericSettingController::class, 'index'])
                ->name('index')
                ->where('model','MvrTransferFee|MvrOwnershipTransferReason|MvrTransferCategory|MvrDeRegistrationReason|MvrFee|MvrBodyType|MvrClass|MvrFuelType|MvrMake|MvrModel|MvrMotorVehicle|MvrTransmissionType|MvrColor|MvrPlateSize');
        });
    });

    Route::get('/bill_invoice/pdf/{id}', [QRCodeGeneratorController::class, 'invoice'])->name('bill.invoice');
    Route::get('bill_transfer/pdf/{id}', [QRCodeGeneratorController::class, 'transfer'])->name('bill.transfer');
    Route::get('bill_receipt/pdf/{id}', [QRCodeGeneratorController::class, 'receipt'])->name('bill.receipt');

    Route::name('returns.')->prefix('returns')->group(function () {
        Route::resource('/interest-rates', InterestRateController::class);
        Route::get('/stamp-duty', [SettingController::class, 'getStampDutySettings'])->name('stamp-duty');

        Route::name('returns.')->prefix('returns')->group(function () {
            Route::name('returns.')->prefix('returns')->group(function () {
                Route::get('/', [ReturnsController::class, 'index'])->name('index');
                Route::get('hotel', [HotelLevyReturnController::class, 'hotel'])->name('hotel');
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

        Route::get('/port/index', [PortReturnController::class, 'index'])->name('port.index');
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
    });

    Route::name('petroleum.')->prefix('petroleum')->group(function () {
        Route::resource('/filling', PetroleumReturnController::class);
        Route::resource('/certificateOfQuantity', QuantityCertificateController::class);
        Route::get('/certificateOfQuantityFile/{id}', [QuantityCertificateController::class, 'certificate'])->name('certificateOfQuantity.certificate');
    });

    Route::name('queries.')->prefix('queries')->group(function () {
        Route::get('/sales-purchases', [SalesPurchasesController::class, 'index'])->name('sales-purchases');
        Route::get('/all-credit-returns', [AllCreditReturnsController::class, 'index'])->name('all-credit-returns');
        Route::get('/all-credit-returns/show/{id}/{return_id}/{sales}', [AllCreditReturnsController::class, 'show'])->name('all-credit-returns.show');
        Route::get('/non-filers', [NonFilersController::class, 'index'])->name('non-filers');
        Route::get('/non-filers/show/{id}', [NonFilersController::class, 'show'])->name('non-filers.show');
    });

    Route::name('reliefs.')->prefix('reliefs')->group(function () {
        Route::resource('/ministries', ReliefMinistriestController::class);
        Route::resource('/registrations', ReliefRegistrationController::class);
        Route::resource('/projects', ReliefProjectController::class);
        Route::resource('/applications', ReliefApplicationsController::class);
        Route::get('/get-attachment/{path}', [ReliefApplicationsController::class, 'getAttachment'])->name('get.attachment');
        Route::get('/generate-report', [ReliefGenerateReportController::class, 'index'])->name('generate.report');
        Route::get('/download-report-pdf/{dates}', [ReliefGenerateReportController::class, 'downloadReliefReportPdf'])->name('download.report.pdf');
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
        Route::get('/returns',[ReturnReportController::class,'index'])->name('returns');
        Route::get('/returns/preview/{parameters}',[ReturnReportController::class,'preview'])->name('returns.preview');
        Route::get('/download-report-pdf/{data}',[ReturnReportController::class, 'exportReturnReportPdf'])->name('returns.download.pdf');
    });

    Route::name('claims.')->prefix('/tax-claims')->group(function () {
        Route::get('/', [ClaimsController::class, 'index'])->name('index');
        Route::get('/{claim}', [ClaimsController::class, 'show'])->name('show');
        Route::get('/{claim}/approve', [ClaimsController::class, 'approve'])->name('approve');
        Route::get('/files/{file}', [ClaimFilesController::class, 'show'])->name('files.show');
    });

    Route::name('extension.')->prefix('/extensions-e-filling')->group(function () {
        Route::get('/', [ExtensionController::class, 'index'])->name('index');
        Route::get('show/{debtId}', [ExtensionController::class, 'show'])->name('show');
        Route::get('file/{file}', [ExtensionController::class, 'file'])->name('file');
    });

    Route::name('installment.')->prefix('/installments-e-filling')->group(function () {
        Route::get('/', [InstallmentController::class, 'index'])->name('index');
        Route::get('/show/{installmentId}', [InstallmentController::class, 'show'])->name('show');

        Route::prefix('/requests')->as('requests.')->group(function (){
            Route::get('/', [InstallmentRequestController::class, 'index'])->name('index');
            Route::get('create/{debtId}', [InstallmentRequestController::class, 'create'])->name('create');
            Route::get('show/{debtId}', [InstallmentRequestController::class, 'show'])->name('show');
            Route::get('file/{file}', [InstallmentRequestController::class, 'file'])->name('file');
        });
    });

    Route::name('upgrade-tax-types.')->prefix('/upgrade-tax-types')->group(function () {
        Route::get('/', [UpgradeTaxtypeController::class, 'index'])->name('index');
        Route::get('/show/{id}/{tax_type_id}/{sales}', [UpgradeTaxtypeController::class, 'show'])->name('show');
    });


    Route::name('debts.')->prefix('/debts')->group(function () {
        // General debts
        Route::get('/all', [DebtController::class, 'index'])->name('debt.index');
        Route::get('/overdue', [DebtController::class, 'overdue'])->name('debt.overdue');
        Route::get('/recovery-measure/{debtId}', [DebtController::class, 'recovery'])->name('debt.recovery');
        Route::get('/show/{debtId}', [DebtController::class, 'show'])->name('debt.show');
        Route::get('/overdue/show/{debtId}', [DebtController::class, 'showOverdue'])->name('debt.showOverdue');
        Route::get('/demand-notice/send/{debtId}', [DebtController::class, 'sendDemandNotice'])->name('debt.sendDemandNotice');


        // Assesments
        Route::get('/waivers', [AssessmentDebtController::class, 'waivers'])->name('waivers.index');
        Route::get('/waivers/{waiverId}', [AssessmentDebtController::class, 'approval'])->name('waivers.approval');

        Route::get('/audits', [AssessmentDebtController::class, 'audit'])->name('audits.index');
        Route::get('/assessments', [AssessmentDebtController::class, 'verification'])->name('assessments.index');
        Route::get('/investigations', [AssessmentDebtController::class, 'investigation'])->name('investigations.index');

        // Return debts
        Route::get('/returns/hotel/{taxType}', [ReturnDebtController::class, 'index'])->name('hotel.index');
        Route::get('/returns/tour/{taxType}', [ReturnDebtController::class, 'index'])->name('tour.index');
        Route::get('/returns/restaurant/{taxType}', [ReturnDebtController::class, 'index'])->name('restaurant.index');
        Route::get('/returns/petroleum/{taxType}', [ReturnDebtController::class, 'index'])->name('petroleum.index');
        Route::get('/returns/vat/{taxType}', [ReturnDebtController::class, 'index'])->name('vat.index');
        Route::get('/returns/port/{taxType}', [ReturnDebtController::class, 'index'])->name('port.index');
        Route::get('/returns/mno/{taxType}', [ReturnDebtController::class, 'index'])->name('mno.index');
        Route::get('/returns/bfo/{taxType}', [ReturnDebtController::class, 'index'])->name('bfo.index');
        Route::get('/returns/stamp-duty/{taxType}', [ReturnDebtController::class, 'index'])->name('stamp-duty.index');
        Route::get('/returns/lump-sum/{taxType}', [ReturnDebtController::class, 'index'])->name('lump-sum.index');
        Route::get('/returns/emt/{taxType}', [ReturnDebtController::class, 'index'])->name('emt.index');
        Route::get('/returns/sea/{taxType}', [ReturnDebtController::class, 'index'])->name('sea.index');
        Route::get('/returns/airport/{taxType}', [ReturnDebtController::class, 'index'])->name('airport.index');
    });

    Route::name('tax_investigation.')->prefix('tax_investigation')->group(function () {
        Route::resource('/approvals', TaxInvestigationApprovalController::class);
        Route::resource('/assessments', TaxInvestigationAssessmentController::class);
        Route::resource('/verified', TaxInvestigationVerifiedController::class);
        Route::resource('/files', TaxInvestigationFilesController::class);
    });

    Route::get('agent-file/{file}/{type}', [TaxAgentFileController::class, 'getAgentFile'])->name('agent.file');

        Route::name('land-lease.')->prefix('land-lease')->group(function () {
            Route::get('/list', [LandLeaseController::class, 'index'])->name('list');
            Route::get('/view/{id}', [LandLeaseController::class, 'view'])->name('view');
            Route::get('/agreement-doc/{path}', [LandLeaseController::class, 'getAgreementDocument'])->name('get.lease.document');
            Route::get('/generate-report', [LandLeaseController::class, 'generateReport'])->name('generate.report');
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
            ->name('internal-search')->where('type','plate-number|chassis');
        Route::get('/de-registration-chassis-search/{type}/{number}', [DeRegistrationController::class, 'search'])
            ->name('internal-search-dr')->where('type','plate-number|chassis');
        Route::get('/ownership-transfer-chassis-search/{type}/{number}', [OwnershipTransferController::class, 'search'])
            ->name('internal-search-ot')->where('type','plate-number|chassis');
        Route::get('/written-off-chassis-search/{type}/{number}', [WrittenOffVehiclesController::class, 'search'])
            ->name('internal-search-wo')->where('type','plate-number|chassis');
        Route::get('/sp-rg/{id}', [MotorVehicleRegistrationController::class, 'simulatePayment']);//todo: remove
        Route::get('/sp-rc/{id}', [RegistrationChangeController::class, 'simulatePayment']);//todo: remove
        Route::get('/sp-dr/{id}', [DeRegistrationController::class, 'simulatePayment']);//todo: remove
        Route::get('/sp-ot/{id}', [OwnershipTransferController::class, 'simulatePayment']);//todo: remove
    });
});
