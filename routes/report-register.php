<?php

/*
|--------------------------------------------------------------------------
| Report Register Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ReportRegister\IncidentController;
use App\Http\Controllers\ReportRegister\TaskController;
use Illuminate\Support\Facades\Route;

Route::name('report-register.')
    ->prefix('report-register')
    ->middleware(['2fa', 'auth', 'check-qns'])
    ->group(function () {

    Route::get('/settings', [IncidentController::class, 'settings'])->name('settings');
    Route::get('/settings/sub-category/{id}', [IncidentController::class, 'subCategory'])->name('settings.sub-category');
    Route::get('/file/{path}', [IncidentController::class, 'file'])->name('file');

    Route::prefix('incident')->name('incident.')->group(function () {
        Route::get('/', [IncidentController::class, 'index'])->name('index');
        Route::get('/staff/reports', [IncidentController::class, 'staff'])->name('staff');
        Route::get('/{id}', [IncidentController::class, 'show'])->name('view');
    });

    Route::prefix('task')->name('task.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/{id}', [TaskController::class, 'show'])->name('view');
    });

});
