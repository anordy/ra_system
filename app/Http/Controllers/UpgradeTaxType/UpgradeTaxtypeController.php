<?php

namespace App\Http\Controllers\UpgradeTaxType;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpgradeTaxtypeController extends Controller
{
    public function index()
    {
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');

        $returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, business_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->groupBy(['business_location_id','hotel_returns.id','business_id'])->get();

        return view('upgrade-tax-type.index', compact('returns'));
    }

    public function show($id)
    {
        $return_id = decrypt($id);
        $return = HotelReturn::query()->findOrFail($return_id);
//        dd($return->taxtype);
        dd($return->businessLocaton->taxRegion);
        return view('upgrade-tax-type.show', compact('return', 'return_id'));
    }
}
