<?php
namespace App\Traits;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;

trait ReturnReportTrait
{
    public function getRecords($parameters)
    {
        if($parameters['tax_type_id'] == 'all'){
            $model = TaxReturn::query();
        }else{
            $model = TaxReturn::query()->where('tax_type_id',$parameters['tax_type_id']);
        }
        if ($parameters['type'] == 'Filing') {
            if ($parameters['filing_report_type'] == 'On-Time-Filings') {
                $returns = clone $model->where('filing_due_date', '>=', "tax_returns.created_at");
            } elseif ($parameters['filing_report_type'] == 'Late-Filings') {
                $returns = clone $model->where('filing_due_date', '<', "tax_returns.created_at");
            } elseif ($parameters['filing_report_type'] == 'All-Filings') {
                $returns = clone $model;
            } elseif ($parameters['filing_report_type'] == 'Tax-Claims') {
                $returns = clone $model->where('has_claim', true);
            } elseif ($parameters['filing_report_type'] == 'Nill-Returns') {
                $returns = clone $model->where('is_nill', true);
            }
        } elseif ($parameters['type'] == 'Payment') {
            if ($parameters['payment_report_type'] == 'On-Time-Paid-Returns') {
                $returns = clone $model->whereNotNull('paid_at');
                $returns = $returns->where('payment_due_date', '>=', 'paid_at');
            } elseif ($parameters['payment_report_type'] == 'Late-Paid-Returns') {
                $returns = clone $model->whereNotNull('paid_at');
                $returns = $returns->where('payment_due_date', '<', 'paid_at',);
            } elseif ($parameters['payment_report_type'] == 'Unpaid-Returns') {
                $returns = clone $model->whereNull('paid_at');
            } elseif ($parameters['payment_report_type'] == 'All-Paid-Returns') {
                $returns = clone $model->whereNotNull('paid_at');
            }
        }

        if ($returns->count() < 1) {
            return $returns;
        }
        return $this->getSelectedRecords($returns, $parameters);
    }


    public function getSelectedRecords($records, $parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $records->orderBy("tax_returns.created_at", 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $records->orderBy("tax_returns.created_at", 'asc');
        }

        return $records->whereBetween("tax_returns.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("tax_returns.created_at", 'asc');
    }
}
