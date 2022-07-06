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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WardController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\ISIC1Controller;
use App\Http\Controllers\ISIC2Controller;
use App\Http\Controllers\ISIC3Controller;
use App\Http\Controllers\ISIC4Controller;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TaxTypeController;
use App\Http\Controllers\Business\BusinessController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\BusinessCategoryController;
use App\Http\Controllers\WithholdingAgentController;
use App\Http\Controllers\TaxAgents\TaxAgentController;
use App\Http\Controllers\Taxpayers\TaxpayersController;
use App\Http\Controllers\Business\RegistrationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Taxpayers\RegistrationsController;
use App\Http\Controllers\WorkflowerTestController;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/workflow', [WorkflowerTestController::class, 'index']);

Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend');
Route::get('captcha', [CaptchaController::class, 'reload'])->name('captcha.reload');
Route::get('password/change/{user}', [ChangePasswordController::class, 'index'])->name('password.change');
Route::post('password/save-changed', [ChangePasswordController::class, 'updatePassword'])->name('password.save-changed');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    Route::get('/notifications', [NotificationController::class,'index'])->name('notifications');

    Route::prefix('settings')->name('settings.')->group(function(){
        Route::resource('/users', UserController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/country', CountryController::class);
        Route::resource('/region', RegionController::class);
        Route::resource('/district', DistrictController::class);
        Route::resource('/ward', WardController::class);
        Route::resource('/banks', BankController::class);
        Route::resource('/business-categories', BusinessCategoryController::class);
        Route::resource('/taxtypes', TaxTypeController::class);
        Route::resource('/isic1', ISIC1Controller::class);
        Route::resource('/isic2', ISIC2Controller::class);
        Route::resource('/isic3', ISIC3Controller::class);
        Route::resource('/isic4', ISIC4Controller::class);
    });

    Route::prefix('system')->name('system.')->group(function (){
        Route::resource('audits', AuditController::class); 
    });


    Route::prefix('taxpayers')->as('taxpayers.')->group(function (){
        Route::resource('/registrations', RegistrationsController::class); // KYC
        Route::get('/enroll-fingerprint/{kyc_id}', [RegistrationsController::class, 'enrollFingerprint'])->name('enroll-fingerprint');
        Route::get('/verify-user/{kyc_id}', [RegistrationsController::class, 'verifyUser'])->name('verify-user');
    });
    Route::resource('taxpayers', TaxpayersController::class);

    Route::prefix('withholdingAgents')->as('withholdingAgents.')->group(function (){
        Route::get('register', [WithholdingAgentController::class, 'registration'])->name('register');
        Route::get('list', [WithholdingAgentController::class, 'index'])->name('list');
    });

    Route::prefix('business')->as('business.')->group(function (){
        Route::get('/registrationsApproval/{id}', [RegistrationController::class, 'approval'])->name('registrations.approval'); // KYC
        Route::resource('registrations', RegistrationController::class);
        Route::get('/closure', [BusinessController::class, 'closure'])->name('closure');
    });


	Route::name('taxagents.')->prefix('taxagents')->group(function (){
		Route::get('/requests', [TaxAgentController::class, 'index'])->name('requests');
		Route::get('/active', [TaxAgentController::class, 'activeAgents'])->name('active');
		Route::get('/show/{id}', [TaxAgentController::class, 'showActiveAgent'])->name('active-show');
		Route::get('/renew', [TaxAgentController::class, 'renewal'])->name('renew');
		Route::get('/fee', [TaxAgentController::class, 'fee'])->name('fee');

	});
});
