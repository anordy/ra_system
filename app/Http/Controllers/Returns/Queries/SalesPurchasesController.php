<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use Illuminate\Http\Request;

class SalesPurchasesController extends Controller
{
    public function index()
    {
        $purchases = $this->getAllPurchases();
        foreach ($purchases as $purchase)
        {
            $purchases = $purchase;
        }
        $purchases = $this->getAllPurchases();
        foreach ($purchases as $purchase)
        {
            $purchases = $purchase;
        }



        return view('returns.queries.sales-purchases.index');
    }

    public function getAllSales()
    {
        //for hotel
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $hotel_returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id, hotel_returns.tax_type_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id','hotel_returns.tax_type_id'])
            ->orderByDesc('hotel_returns.id')->get();
        return $hotel_returns;
    }

    public function getAllPurchases()
    {
        //for hotel
        $purchaseConfigs = HotelReturnConfig::query()->whereIn('code', ['LP', 'IP'])->get()->pluck('id');
        $hotel_returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id, hotel_returns.tax_type_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $purchaseConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id','hotel_returns.tax_type_id'])
            ->orderByDesc('hotel_returns.id')->get();
        return $hotel_returns;

    }
}
