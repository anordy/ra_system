<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Traits\SalesTrait;
use Illuminate\Http\Request;

class SalesPurchasesController extends Controller
{
    use SalesTrait;

    public function index()
    {
        $salesHotel = $this->getAllHotelSales()['return'];
        $purchasesHotel = $this->getAllHotelSales()['return'];
        dd($salesHotel);



        return view('returns.queries.sales-purchases.index');
    }

}
