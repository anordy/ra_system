<?php

namespace App\Traits;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\Returns\Vat\VatReturnItem;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;

trait PurchasesTrait
{

    public function getHotelPurchases()
    {
        $purchaseConfigs = HotelReturnConfig::query()->whereIn('code', ['LP', 'IP'])->get()->pluck('id');
        $return = HotelReturn::class;
        $item = HotelReturnItem::class;
        $hotel_returns = $this->getPurchases($return, $purchaseConfigs, $item);
        return $hotel_returns;
    }

    public function getVatPurchases()
    {
        $purchaseConfigs = VatReturnConfig::query()->whereIn('code', ['SLP', 'IP', 'SRI'])->get()->pluck('id');
        $return = VatReturn::class;
        $item = VatReturnItem::class;
        $vat_returns = $this->getPurchases($return, $purchaseConfigs, $item);
        return $vat_returns;
    }

    public function getPurchases($modelName, $configs, $itemModel)
    {
        $returnTableName = (new $modelName())->getTable();
        $returnItems = (new $itemModel())->getTable();
        $return = $modelName::query()
            ->selectRaw('SUM('.$returnItems.'.value) as total_purchases,financial_month_id, financial_year_id, '.$returnTableName.'.currency, '.$returnTableName.'.id, 
            business_location_id, '.$returnTableName.'.business_id, '.$returnTableName.'.tax_type_id')
            ->leftJoin(''.$returnItems.'', ''.$returnTableName.'.id', ''.$returnItems.'.return_id')
            ->where(''.$returnTableName.'.status', ReturnStatus::COMPLETE)
            ->whereIn(''.$returnItems.'.config_id', $configs)
            ->orderByDesc(''.$returnTableName.'.id')->groupBy([''.$returnTableName.'.id'])->get();
        return $return;
    }

}
