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
use Illuminate\Support\Facades\Gate;

class UpgradeTaxtypeController extends Controller
{
    public function index()
    {
        if (!Gate::allows('qualified-tax-types-upgrade-view')) {
            abort(403);
        }
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $hotel_tax_type_id = TaxType::query()->where('code', TaxType::HOTEL)->value('id');
        $hotel_returns = $this->getSales(HotelReturn::class, $salesConfigs, HotelReturnItem::class, $hotel_tax_type_id);

        $stampConfigs = StampDutyConfig::query()->where('heading_type', ['supplies'])->get()->pluck('id');
        $stamp_tax_type_id = TaxType::query()->where('code', TaxType::STAMP_DUTY)->value('id');
        $stamp_duty_return = $this->getSales(StampDutyReturn::class, $stampConfigs, StampDutyReturnItem::class, $stamp_tax_type_id);


        $lump_tax_type_id = TaxType::query()->where('code', TaxType::LUMPSUM_PAYMENT)->value('id');
        $lump_sum_return = LumpSumReturn::query()
            ->selectRaw('SUM(lump_sum_returns.total_amount_due) as total_sales, lump_sum_returns.id, 
            business_location_id, lump_sum_returns.business_id,lump_sum_returns.tax_type_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'lump_sum_returns.business_id')
            ->where('lump_sum_returns.status', ReturnStatus::COMPLETE)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $lump_tax_type_id)
            ->groupBy(['lump_sum_returns.business_id'])
            ->orderByDesc('lump_sum_returns.id')->get();

        return view('upgrade-tax-type.index', compact('hotel_returns','hotel_tax_type_id',
            'stamp_duty_return', 'stamp_tax_type_id', 'lump_tax_type_id', 'lump_sum_return'));
    }

    public function getSales($modelName, $configs, $itemModel, $tax_type_id)
    {
        $returnTableName = (new $modelName())->getTable();
        $returnItems = (new $itemModel())->getTable();
        $return = $modelName::query()
            ->selectRaw(' SUM('.$returnItems.'.value) as total_sales, '.$returnTableName.'.id,
            business_location_id, '.$returnTableName.'.business_id, '.$returnTableName.'.tax_type_id')
            ->leftJoin(''.$returnItems.'', ''.$returnTableName.'.id', ''.$returnItems.'.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', ''.$returnTableName.'.business_id')
            ->where(''.$returnTableName.'.status', ReturnStatus::COMPLETE)
            ->whereIn(''.$returnItems.'.config_id', $configs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $tax_type_id)
            ->groupBy([''.$returnTableName.'.business_id'])
            ->orderByDesc(''.$returnTableName.'.id')->get();
        return $return;
    }

    public function show($id, $tax_type_id, $sales)
    {
        if (!Gate::allows('qualified-tax-types-upgrade-view')) {
            abort(403);
        }
        $return_id = decrypt($id);
        $sales = decrypt($sales);
        $tax_type_id = decrypt($tax_type_id);
        $tax_type = TaxType::query()->findOrFail($tax_type_id);
        $tax_type_code = $tax_type->code;

        switch ($tax_type_code)
        {
            case TaxType::HOTEL:
                $return = HotelReturn::query()->findOrFail($return_id);
                break;
            case TaxType::STAMP_DUTY:
                $return = StampDutyReturn::query()->findOrFail($return_id);
                break;
            case TaxType::LUMPSUM_PAYMENT:
                $return = LumpSumReturn::query()->findOrFail($return_id);
                break;
            default:
                abort(404);
        }

        $currency = $this->getCurrency($return->business_id, $return->tax_type_id);

        return view('upgrade-tax-type.show', compact('return', 'return_id', 'sales','currency'));
    }

    public function getCurrency($business_id, $tax_type_id)
    {
        $result = BusinessTaxType::query()->where('business_id', $business_id)
            ->where('tax_type_id',$tax_type_id)->first();
        $currency = $result->currency;
        return $currency;

    }
}
