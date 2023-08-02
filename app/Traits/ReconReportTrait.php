<?php

namespace App\Traits;

use App\Enum\PaymentStatus;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Builder;

trait ReconReportTrait
{
    public function getBillBuilder($parameters): Builder
    {
        return ZmBill::query()
            ->with('taxType', 'billable', 'zmRecon')
            ->where('status', PaymentStatus::PAID)
            ->whereBetween('created_at',[$parameters['range_start'],$parameters['range_end']])
            ->orderBy('created_at', 'DESC');
    }
}
