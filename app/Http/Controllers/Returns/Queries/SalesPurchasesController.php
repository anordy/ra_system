<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Traits\PurchasesTrait;
use Illuminate\Http\Request;

class SalesPurchasesController extends Controller
{
    use PurchasesTrait;
    public function index()
    {
        $pu = $this->getHotelPurchases();


        return view('returns.queries.sales-purchases.index');
    }


}
