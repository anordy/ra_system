<?php

use App\Http\Controllers\NonTaxResident\NonTaxResidentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['2fa', 'auth', 'check-qns'])->name('ntr.')->group(function () {
    Route::get('/business', [NonTaxResidentController::class, 'listBusinesses'])->name('business.index');
    Route::get('/business/view/{id}', [NonTaxResidentController::class, 'showBusiness'])->name('business.show');

    Route::get('/business/de-registration', [NonTaxResidentController::class, 'listDeregistrations'])->name('business.de-registration.index');
    Route::get('/business/de-registration/{id}', [NonTaxResidentController::class, 'showDeregistration'])->name('business.de-registration.show');

    Route::get('/cancelled-returns', [NonTaxResidentController::class, 'listCancelledReturns'])->name('returns.cancelled');
    Route::get('/filed-returns', [NonTaxResidentController::class, 'listFiledReturns'])->name('returns.index');
    Route::get('/returns/{id}', [NonTaxResidentController::class, 'showReturn'])->name('returns.show');
    Route::get('/business/updates', [NonTaxResidentController::class, 'listBusinessUpdates'])->name('business.updates.index');
    Route::get('/business/updates/{id}', [NonTaxResidentController::class, 'showBusinessUpdates'])->name('business.updates.show');

});