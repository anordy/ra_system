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

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaxPayers\RegistrationsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);

Route::prefix('taxpayers')->as('taxpayers.')->group(function (){
    Route::resource('registrations', RegistrationsController::class);
});