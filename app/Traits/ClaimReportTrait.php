<?php

namespace App\Traits;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Taxpayer;
use App\Models\TaxType;

trait ClaimReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['tax_payer_id'] != 'all')
        {
            $model = TaxClaim::query()->with('credit', 'business')
                ->leftJoin('businesses', 'tax_claims.business_id', '=', 'businesses.id')
                ->where('businesses.responsible_person_id', '=', $parameters['tax_payer_id']);
        }
        else
        {
            $model = TaxClaim::query()->with('credit', 'business');
        }

        if ($parameters['duration'] == 'yearly') {
            if ($parameters['status'] == 'approved') {
                $claim = clone $model->where('tax_claims.status', '=', 'approved')
                    ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id')
                    ->where('tax_credits.payment_status', '=', $parameters['payment_status']);
            } elseif ($parameters['status'] == 'both') {
                $claim = clone $model;
            } else {
                $claim = clone $model->where('tax_claims.status', $parameters['status']);
            }
        } else {
            if ($parameters['status'] == 'approved') {
                $claim = clone $model->where('tax_claims.status', '=', 'approved')
                    ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id')
                    ->where('tax_credits.payment_status', '=', $parameters['payment_status'])
                    ->whereBetween('tax_claims.created_at', [$parameters['from'], $parameters['to']]);
            } elseif ($parameters['status'] == 'both') {
                $claim = clone $model->whereBetween('tax_claims.created_at', [$parameters['from'], $parameters['to']]);
            } else {
                $claim = clone $model->where('tax_claims.status', $parameters['status'])
                    ->whereBetween('tax_claims.created_at', [$parameters['from'], $parameters['to']]);
            }
        }

        return $this->getSelectedRecords($claim, $parameters);
    }


    public function getSelectedRecords($records, $parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $records->orderBy("tax_claims.created_at", 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $records->orderBy("tax_claims.created_at", 'asc');
        }

        return $records->whereBetween("tax_claims.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("tax_claims.created_at", 'asc');
    }
}
