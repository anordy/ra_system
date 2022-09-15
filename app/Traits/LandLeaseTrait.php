<?php

namespace App\Traits;

use App\Models\FinancialMonth;
use Carbon\Carbon;

trait LandLeaseTrait
{


    public function getLeasePaymentFinancialMonth($financial_year_id, $payment_month)
    {
        $paymentFinancialMonth = FinancialMonth::select('id', 'name', 'due_date')
            ->where('financial_year_id', $financial_year_id)
            ->where('name', $payment_month)
            ->firstOrFail();
        return $paymentFinancialMonth->due_date;
    }

}

