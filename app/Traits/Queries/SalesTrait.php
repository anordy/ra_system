<?php

namespace App\Traits\Queries;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxType;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait SalesTrait
{
    public function getAllHotelSales($modelName, )
    {
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $hotel_tax_type_id = TaxType::query()->where('code', TaxType::HOTEL)->value('id');
        $hotel_returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id, hotel_returns.tax_type_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $hotel_tax_type_id)
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id','hotel_returns.tax_type_id'])
            ->orderByDesc('hotel_returns.id')->get();
        return $hotel_returns;
    }
}
