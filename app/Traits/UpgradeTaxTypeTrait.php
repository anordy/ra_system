<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\LumpSumPayment;
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

trait UpgradeTaxTypeTrait
{
    public function checkPreviousFiling($businessId, $returnCode)
    {
        $locations = BusinessLocation::query()->where('business_id', $businessId)->get();
        $location_id = [];
        foreach ($locations as $location)
        {
            $location_id[] = $location->id;
        }

        if ($returnCode == TaxType::LUMPSUM_PAYMENT)
        {
            $model = LumpSumPayment::class;
        }
        elseif ($returnCode == TaxType::STAMP_DUTY)
        {
            $model = StampDutyReturn::class;
        }
        elseif ($returnCode == TaxType::HOTEL)
        {
            $model = HotelReturn::class;
        }

        $check = $this->getReturn($model, $businessId, $location_id, $this->currentFinancialYear()->id, $this->currentFinancialMonth()->id);

        if (sizeof($location_id) == sizeof($check))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getReturn($model, $businessId, $location_id, $year_id, $month_id)
    {
        $return = $model::query()
            ->where('business_id', $businessId)
            ->whereIn('business_location_id', $location_id)
            ->where('financial_year_id', $year_id)
            ->where('financial_month_id', '=',$month_id)
            ->get();

        return $return;
    }

    public function currentFinancialMonth()
    {
        $month = date("F", strtotime(date('Y-m-d')));
        $currentFinancialMonth = FinancialMonth::query()->where('name', $month)->where('financial_year_id', $this->currentFinancialYear()->id)->first();
        return $currentFinancialMonth;
    }

    public function currentFinancialYear()
    {
        $year = FinancialYear::query()->select('id')->where('code', date('Y'))->first();
        return $year;
    }
}
