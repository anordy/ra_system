<?php

namespace App\Http\Controllers\UpgradeTaxType;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\StampDuty\StampDutyReturnItem;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpgradeTaxtypeController extends Controller
{
    public function index()
    {
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $hotel_tax_type_id = TaxType::query()->where('code', TaxType::HOTEL)->value('id');
        $hotel_returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $hotel_tax_type_id)
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id'])->get();

        $stampConfigs = StampDutyConfig::query()->where('heading_type', ['supplies'])->get()->pluck('id');
        $stamp_tax_type_id = TaxType::query()->where('code', TaxType::STAMP_DUTY)->value('id');
        $stamp_duty_return = StampDutyReturn::query()
            ->selectRaw(' SUM(stamp_duty_return_items.value) as total_sales, stamp_duty_returns.id, business_location_id, stamp_duty_returns.business_id')
            ->leftJoin('stamp_duty_return_items', 'stamp_duty_returns.id', 'stamp_duty_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'stamp_duty_returns.business_id')
            ->where('stamp_duty_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('stamp_duty_return_items.config_id', $stampConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $stamp_tax_type_id)
            ->groupBy(['business_location_id','stamp_duty_returns.id','stamp_duty_returns.business_id'])->get();

        $lump_tax_type_id = TaxType::query()->where('code', TaxType::LUMPSUM_PAYMENT)->value('id');
        $lump_sum_return = LumpSumReturn::query()
            ->selectRaw(' SUM(lump_sum_returns.total_amount_due) as total_sales, lump_sum_returns.id, business_location_id, lump_sum_returns.business_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'lump_sum_returns.business_id')
            ->where('lump_sum_returns.status', ReturnStatus::COMPLETE)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $lump_tax_type_id)
            ->groupBy(['business_location_id','lump_sum_returns.id','lump_sum_returns.business_id'])->get();

        return view('upgrade-tax-type.index', compact('hotel_returns','hotel_tax_type_id',
            'stamp_duty_return', 'stamp_tax_type_id', 'lump_tax_type_id', 'lump_sum_return'));
    }

    public function show($id, $tax_type_id, $sales)
    {
        $return_id = decrypt($id);
        $sales = decrypt($sales);
        $tax_type_id = decrypt($tax_type_id);
        $tax_type = TaxType::query()->findOrFail($tax_type_id);
        $tax_type_code = $tax_type->code;

        switch ($tax_type_code)
        {
            case TaxType::HOTEL:
                $return = HotelReturn::query()->findOrFail($return_id);
                $currency = BusinessTaxType::query()->findOrFail($return->taxtype->id)->value('currency');
                break;
            case TaxType::STAMP_DUTY:
                $return = StampDutyReturn::query()->findOrFail($return_id);
                $currency = BusinessTaxType::query()->findOrFail($return->taxtype->id)->value('currency');
                break;
            case TaxType::LUMPSUM_PAYMENT:
                $return = LumpSumReturn::query()->findOrFail($return_id);
                $currency = BusinessTaxType::query()->findOrFail($return->taxtype->id)->value('currency');
                break;
            default:
                abort(404);
        }


        return view('upgrade-tax-type.show', compact('return', 'return_id', 'sales','currency'));
    }
}
