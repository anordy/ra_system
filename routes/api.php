<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\v1\ZanMalipoController;
use Illuminate\Support\Facades\Route;

Route::post('bill-control-number', [ZanMalipoController::class, 'controlNumberCallback']);
Route::post('bill-payment-notification', [ZanMalipoController::class, 'paymentCallback']);
Route::post('reconciliation-results', [ZanMalipoController::class, 'reconCallback']);