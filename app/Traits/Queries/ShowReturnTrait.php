<?php

namespace App\Traits\Queries;

use App\Models\BusinessTaxType;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxType;
use Exception;
use Carbon\Carbon;
use App\Models\Audit;
use Illuminate\Support\Facades\Log;

trait ShowReturnTrait
{
    public function getReturn($tax_type_code, $return_id)
    {
        switch ($tax_type_code)
        {
            case TaxType::HOTEL:
                $return = HotelReturn::query()->findOrFail($return_id);
                return $return;
            case TaxType::STAMP_DUTY:
                $return = StampDutyReturn::query()->findOrFail($return_id);
                return $return;
            case TaxType::LUMPSUM_PAYMENT:
                $return = LumpSumReturn::query()->findOrFail($return_id);
                return $return;
            case TaxType::VAT:
                $return = VatReturn::query()->findOrFail($return_id);
                return $return;
            default:
                abort(404);
        }
    }

    public function getCurrency($business_id, $tax_type_id)
    {
        $result = BusinessTaxType::query()->where('business_id', $business_id)
            ->where('tax_type_id',$tax_type_id)->first();
        $currency = $result->currency;
        return $currency;

    }
}
