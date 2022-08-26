<?php

namespace App\Traits;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxType;

trait PurchasesTrait
{

    public function getHotelPurchases()
    {
        $purchaseConfigs = HotelReturnConfig::query()->whereIn('code', ['LP', 'IP'])->get()->pluck('id');
        $return = HotelReturn::class;
        $item = HotelReturnItem::class;
        $hotel_returns = $this->getPurchases($return, $purchaseConfigs, $item);
        dd($hotel_returns->items);
        return $hotel_returns;
    }

    public function getPurchases($modelName, $configs, $itemModel)
    {
        $returnTableName = (new $modelName())->getTable();
        $returnItems = (new $itemModel())->getTable();
        $return = $modelName::query()
            ->selectRaw('financial_month_id, '.$returnTableName.'.id, 
            business_location_id, '.$returnTableName.'.business_id, '.$returnTableName.'.tax_type_id')
            ->leftJoin(''.$returnItems.'', ''.$returnTableName.'.id', ''.$returnItems.'.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', ''.$returnTableName.'.business_id')
            ->where(''.$returnTableName.'.status', ReturnStatus::COMPLETE)
            ->whereIn(''.$returnItems.'.config_id', $configs)
            ->where('business_tax_type.status', '=','current-used')
            ->orderByDesc(''.$returnTableName.'.id')->groupBy([''.$returnTableName.'.id'])->first();
        return $return;
    }

}
