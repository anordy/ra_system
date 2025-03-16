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
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Captcha\CaptchaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RaIncedentsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Reports\GeneralReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\DualControlActivityController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::get('/', [HomeController::class, 'index']);

Route::get('checkCaptcha', [CaptchaController::class, 'reload'])->name('captcha.reload')->middleware('throttle:captcha');
Route::get('captcha/{config?}', [CaptchaController::class, 'getCaptcha'])->name('captcha.get')->name('captcha.get')->middleware('throttle:captcha');



Route::middleware('auth')->group(function () {
    Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
    Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
    Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend')->middleware('throttle:auth');
    Route::get('/kill', [TwoFactorAuthController::class, 'kill'])->name('session.kill');

    // OTP using Security Qns
    Route::get('2fa/security-questions', [TwoFactorAuthController::class, 'securityQuestions'])->name('2fa.security-questions');

    Route::get('password/change', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('password/change', [ChangePasswordController::class, 'updatePassword'])->name('password.store');
});

 Route::middleware(['2fa', 'auth'])->group(function (){
     Route::get('/account/login-security-questions', [AccountController::class, 'preSecurityQuestions'])->name('account.pre-security-questions');
 });

Route::middleware(['2fa', 'auth', 'check-qns'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    // Incedents
    Route::prefix('ra')->name('ra.')->group(function () {
        Route::get('/incedent/index', [RaIncedentsController::class, 'index'])->name('incedent.index');
        Route::get('/incedent/show/{id}', [RaIncedentsController::class, 'show'])->name('incedent.show');
        Route::get('/incedent/create', [RaIncedentsController::class, 'create'])->name('incedent.create');
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/account', [AccountController::class, 'show'])->name('account');
    Route::get('/account/security-questions', [AccountController::class, 'securityQuestions'])->name('account.security-questions');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('/users', \App\Http\Controllers\UserController::class);
        Route::resource('/roles', RoleController::class);
        Route::resource('/country', CountryController::class);
    Route::prefix('system')->name('system.')->group(function () {
        Route::resource('audits', AuditController::class);
        Route::resource('workflow', WorkflowController::class);
        Route::get('/dual-control-activities', [DualControlActivityController::class, 'index'])->name('dual-control-activities.index');
        Route::get('/dual-control-activities/show/{id}', [DualControlActivityController::class, 'show'])->name('dual-control-activities.show');
        Route::get('/dual-control-configure', [DualControlActivityController::class, 'configure'])->name('dual-control-activities.configure');
    });

  

  

    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('all', [AllPdfController::class, 'index'])->name('all');
        Route::get('all/{file}', [AllPdfController::class, 'demandNotice'])->name('demand-notice');
    });

    
});
   
});
