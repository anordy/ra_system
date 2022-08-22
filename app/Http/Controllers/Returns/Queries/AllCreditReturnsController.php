<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\TaxType;
use Illuminate\Http\Request;

class AllCreditReturnsController extends Controller
{
    public function index()
    {
        $hotel_returns = $this->getAllHotelSales();
        return view('returns.queries.all-credit-returns.index', compact('hotel_returns'));
    }

    public function getAllHotelSales()
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

    public function getAllVatSales()
    {
        $salesConfigs = VatReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $hotel_returns = VatReturn::query()
            ->selectRaw('SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id, hotel_returns.tax_type_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id','hotel_returns.tax_type_id'])
            ->orderByDesc('hotel_returns.id')->get();
        return $hotel_returns;
    }

    public function getAllStampDutySales()
    {
        $stampConfigs = StampDutyConfig::query()->where('heading_type', ['supplies'])->get()->pluck('id');
        $stamp_tax_type_id = TaxType::query()->where('code', TaxType::STAMP_DUTY)->value('id');
        $stamp_duty_return = StampDutyReturn::query()
            ->selectRaw(' SUM(stamp_duty_return_items.value) as total_sales, stamp_duty_returns.id, 
            business_location_id, stamp_duty_returns.business_id, stamp_duty_returns.tax_type_id')
            ->leftJoin('stamp_duty_return_items', 'stamp_duty_returns.id', 'stamp_duty_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'stamp_duty_returns.business_id')
            ->where('stamp_duty_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('stamp_duty_return_items.config_id', $stampConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $stamp_tax_type_id)
            ->groupBy(['business_location_id','stamp_duty_returns.id','stamp_duty_returns.business_id','stamp_duty_returns.tax_type_id'])
            ->orderByDesc('stamp_duty_returns.id')->get();
        return $stamp_duty_return;
    }

}
