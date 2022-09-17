<?php

namespace App\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait LeasePaymentReportTrait
{


    public function getMonthList($dates){
        $period = CarbonPeriod::create($dates['startDate'], $dates['endDate'])->month();

        $months = collect($period)->map(function (Carbon $date) {
        return  $date->monthName;
        })->toArray();

        return $months;
    }

    public function getYearList($dates){
        $endDate = Carbon::parse($dates['endDate'])->copy()->endOfYear();
        $period = CarbonPeriod::create($dates['startDate'], $endDate)->year();
        $years = collect($period)->map(function (Carbon $date) {
        return  $date->year;
        })->toArray();

        return $years;
    }
    
}

