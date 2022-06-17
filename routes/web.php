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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/twoFactorAuth', [TwoFactorAuthController::class, 'index'])->name('twoFactorAuth.index');
Route::post('/twoFactorAuth', [TwoFactorAuthController::class, 'confirm'])->name('twoFactorAuth.confirm');
Route::post('/twoFactorAuth/resend', [TwoFactorAuthController::class, 'resend'])->name('twoFactorAuth.resend');
Route::get('captcha', [CaptchaControlle::class, 'reload'])->name('captcha.reload');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
    Route::resource('/users', UserController::class);

    Route::prefix('settings')->name('settings.')->group(function(){
        Route::resource('/roles', RoleController::class);
    });
});
