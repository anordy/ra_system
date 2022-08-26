<?php

namespace App\Traits\Queries;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
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
        $hotel_returns = HotelReturn::query()
            ->selectRaw(' SUM(hotel_return_items.value) as total_sales, hotel_returns.id, business_location_id, hotel_returns.business_id, hotel_returns.tax_type_id')
            ->leftJoin('hotel_return_items', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'hotel_returns.business_id')
            ->where('hotel_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('hotel_return_items.config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $this->getTaxType(TaxType::HOTEL))
            ->groupBy(['business_location_id','hotel_returns.id','hotel_returns.business_id','hotel_returns.tax_type_id'])
            ->orderByDesc('hotel_returns.id')->get();
        $result = [
            'hotel_tax_type_id'=>$this->getTaxType(TaxType::HOTEL),
            'return'=>$hotel_returns,
            'return_type'=>HotelReturn::class
        ];
        return $result;
    }

    public function getAllVatSales()
    {
        $salesConfigs = VatReturnConfig::query()->whereIn('code', ['SRS', 'ZRS', 'ES', 'SER'])->get()->pluck('id');
        $vat_returns = VatReturn::query()
            ->selectRaw('SUM(vat_return_items.input_amount) as total_sales, vat_returns.id, business_location_id, vat_returns.business_id, vat_returns.tax_type_id')
            ->leftJoin('vat_return_items', 'vat_returns.id', 'vat_return_items.vat_return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'vat_returns.business_id')
            ->where('vat_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('vat_return_items.vat_return_config_id', $salesConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $this->getTaxType(TaxType::VAT))
            ->groupBy(['business_location_id','vat_returns.id','vat_returns.business_id','vat_returns.tax_type_id'])
            ->orderByDesc('vat_returns.id')->get();
        $result = [
            'vat_tax_type_id'=>$this->getTaxType(TaxType::VAT),
            'return'=>$vat_returns,
            'return_type'=>VatReturn::class
        ];
        return $result;
    }

    public function getAllStampDutySales()
    {
        $stampConfigs = StampDutyConfig::query()->where('heading_type', ['supplies'])->get()->pluck('id');
        $stamp_duty_return = StampDutyReturn::query()
            ->selectRaw(' SUM(stamp_duty_return_items.value) as total_sales, stamp_duty_returns.id, 
            business_location_id, stamp_duty_returns.business_id, stamp_duty_returns.tax_type_id')
            ->leftJoin('stamp_duty_return_items', 'stamp_duty_returns.id', 'stamp_duty_return_items.return_id')
            ->leftJoin('business_tax_type', 'business_tax_type.business_id', 'stamp_duty_returns.business_id')
            ->where('stamp_duty_returns.status', ReturnStatus::COMPLETE)
            ->whereIn('stamp_duty_return_items.config_id', $stampConfigs)
            ->where('business_tax_type.status', '=','current-used')
            ->where('business_tax_type.tax_type_id','=', $this->getTaxType(TaxType::STAMP_DUTY))
            ->groupBy(['business_location_id','stamp_duty_returns.id','stamp_duty_returns.business_id','stamp_duty_returns.tax_type_id'])
            ->orderByDesc('stamp_duty_returns.id')->get();
        $result = [
            'stamp_duty_tax_type_id'=>$this->getTaxType(TaxType::STAMP_DUTY),
            'return'=>$stamp_duty_return,
            'return_type'=>StampDutyReturn::class
        ];
        return $result;
    }

    public function getTaxType($code)
    {
        $tax_type_id = TaxType::query()->where('code', $code)->value('id');
        return $tax_type_id;
    }
}
