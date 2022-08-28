<?php

namespace App\Traits;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\StampDuty\StampDutyReturnItem;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\Returns\Vat\VatReturnItem;
use App\Models\TaxType;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait SalesTrait
{
    public function getAllHotelSales()
    {
        $salesConfigs = HotelReturnConfig::query()->whereIn('code', ['HS', 'RS', 'TOS', 'OS'])->get()->pluck('id');
        $return = HotelReturn::class;
        $item = HotelReturnItem::class;
        $hotel_returns = $this->getSales($return, $salesConfigs, $item);
        return $hotel_returns;
    }

    public function getAllVatSales()
    {
        $salesConfigs = VatReturnConfig::query()->whereIn('code', ['SRS', 'ZRS', 'ES', 'SER'])->get()->pluck('id');
        $return = VatReturn::class;
        $item = VatReturnItem::class;
        $vat_returns = $this->getSales($return, $salesConfigs, $item);
        return $vat_returns;
    }

    public function getAllStampDutySales()
    {
        $stampConfigs = StampDutyConfig::query()->where('heading_type', ['supplies'])->get()->pluck('id');
        $return = StampDutyReturn::class;
        $item = StampDutyReturnItem::class;
        $stamp_duty_return = $this->getSales($return, $stampConfigs, $item);
        return $stamp_duty_return;
    }

    public function getTaxType($code)
    {
        $tax_type_id = TaxType::query()->where('code', $code)->value('id');
        return $tax_type_id;
    }

    public function getSales($modelName, $configs, $itemModel)
    {
        $returnTableName = (new $modelName())->getTable();
        $returnItems = (new $itemModel())->getTable();
        $return = $modelName::query()
            ->selectRaw(' SUM('.$returnItems.'.value) as total_sales, financial_month_id,  financial_year_id, '.$returnTableName.'.currency, '.$returnTableName.'.id, 
            business_location_id, '.$returnTableName.'.business_id, '.$returnTableName.'.tax_type_id')
            ->leftJoin(''.$returnItems.'', ''.$returnTableName.'.id', ''.$returnItems.'.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', ''.$returnTableName.'.business_id')
            ->where(''.$returnTableName.'.status', ReturnStatus::COMPLETE)
            ->whereIn(''.$returnItems.'.config_id', $configs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $this->getTaxType(TaxType::STAMP_DUTY))
            ->orderByDesc(''.$returnTableName.'.id')->groupBy([''.$returnTableName.'.id'])->get();
        return $return;
    }
}
