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
use App\Http\Controllers\Debt\ReturnDebtController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Investigation\TaxInvestigationApprovalController;
use App\Http\Controllers\Investigation\TaxInvestigationAssessmentController;
use App\Http\Controllers\Investigation\TaxInvestigationFilesController;
use App\Http\Controllers\Investigation\TaxInvestigationVerifiedController;
use App\Http\Controllers\ISIC1Controller;
use App\Http\Controllers\ISIC2Controller;
use App\Http\Controllers\ISIC3Controller;
use App\Http\Controllers\ISIC4Controller;
use App\Http\Controllers\LandLease\LandLeaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Relief\ReliefApplicationsController;
use App\Http\Controllers\Relief\ReliefMinistriestController;
use App\Http\Controllers\Relief\ReliefProjectController;
use App\Http\Controllers\Relief\ReliefRegistrationController;
use App\Http\Controllers\Relief\ReliefGenerateReportController;
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

    Route::name('reliefs.')->prefix('reliefs')->group(function () {
        Route::resource('/ministries', ReliefMinistriestController::class);
        Route::resource('/registrations', ReliefRegistrationController::class);
        Route::resource('/projects', ReliefProjectController::class);
        Route::resource('/applications', ReliefApplicationsController::class);
        Route::get('/get-attachment/{path}', [ReliefApplicationsController::class, 'getAttachment'])->name('get.attachment');
        Route::get('/generate-report',[ReliefGenerateReportController::class, 'index'])->name('generate.report');
        Route::get('/download-report-pdf/{dates}',[ReliefGenerateReportController::class, 'downloadReliefReportPdf'])->name('download.report.pdf');
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


    Route::name('claims.')->prefix('/tax-claims')->group(function () {
        Route::get('/', [ClaimsController::class, 'index'])->name('index');
        Route::get('/{claim}', [ClaimsController::class, 'show'])->name('show');
        Route::get('/{claim}/approve', [ClaimsController::class, 'approve'])->name('approve');
        Route::get('/files/{file}', [ClaimFilesController::class, 'show'])->name('files.show');
    });

    Route::name('debts.')->prefix('/debts')->group(function () {
        // Assesments
        Route::get('/waivers', [AssessmentDebtController::class, 'waivers'])->name('waivers.index');
        Route::get('/waivers/{waiverId}', [AssessmentDebtController::class, 'approval'])->name('waivers.approval');

        Route::get('/audits', [AssessmentDebtController::class, 'audit'])->name('audits.index');
        Route::get('/verifications', [AssessmentDebtController::class, 'verification'])->name('verifications.index');
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

// <<<<<<< HEAD
//Electronic Money Transaction Return
Route::name('em-transaction.')->prefix('em-transaction')->group(function () {
    Route::get('/em-transactions', [EmTransactionController::class, 'index'])->name('index');
    Route::get('/view/{return_id}', [EmTransactionController::class, 'show'])->name('show');
});


//Tax Clearance
Route::name('tax-clearance.')->prefix('tax-clearance')->group(function () {
    Route::get('/tax-clearances/request', [TaxClearanceController::class, 'requestList'])->name('list');
    Route::get('/tax-clearance/view/{id}', [TaxClearanceController::class, 'viewRequest'])->name('request.view');
});
// =======
    //Electronic Money Transaction Return
    Route::name('em-transaction.')->prefix('em-transaction')->group(function () {
        Route::get('/em-transactions', [EmTransactionController::class, 'index'])->name('index');
        Route::get('/view/{return_id}', [EmTransactionController::class, 'show'])->name('show');
    });
});
// >>>>>>> DEV
