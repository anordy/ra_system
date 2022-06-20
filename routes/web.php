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

use App\Http\Controllers\CaptchaControlle;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaxPayers\RegistrationsController;
use App\Http\Controllers\Taxpayers\TaxpayersController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::prefix('taxpayers')->as('taxpayers.')->group(function (){
    Route::resource('registrations', RegistrationsController::class); // KYC
    Route::get('enroll-fingerprint/{kyc_id}', [RegistrationsController::class, 'enrollFingerprint'])->name('enroll-fingerprint');
    Route::get('verify-user/{kyc_id}', [RegistrationsController::class, 'verifyUser'])->name('verify-user');
});

Route::resource('taxpayers', TaxpayersController::class);
Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend');
Route::get('captcha', [CaptchaControlle::class, 'reload'])->name('captcha.reload');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
    Route::resource('/users', UserController::class);

    Route::prefix('settings')->name('settings.')->group(function(){
        Route::resource('/roles', RoleController::class);
        Route::resource('/country', CountryController::class);
        Route::resource('/region', RegionController::class);
        Route::resource('/district', DistrictController::class);
    });
});
