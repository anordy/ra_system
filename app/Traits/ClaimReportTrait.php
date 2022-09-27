<?php

namespace App\Traits;

use App\Models\Claims\TaxClaim;
use Carbon\Carbon;

trait ClaimReportTrait
{
    public function getRecords($parameters)
    {
        $model = TaxClaim::query()->select('tax_claims.id', 'tax_claims.business_id', 'tax_claims.location_id', 'tax_claims.tax_type_id',
            'tax_claims.amount', 'tax_claims.currency', 'tax_claims.financial_month_id', 'tax_claims.status', 'tax_claims.approved_on',
            'tax_claims.created_at', 'tax_credits.payment_method', 'tax_credits.installments_count', 'tax_credits.payment_status')->with('business')
            ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id');

        if ($parameters['duration'] == 'yearly') {
            if ($parameters['status'] == 'approved') {
                $claim = clone $model
                    ->where('tax_claims.status', '=', $parameters['status']);
            } elseif ($parameters['status'] == 'both') {
                $claim = clone $model
                    ->whereIn('tax_claims.status', ['approved', 'rejected', 'pending']);
            } else {
                $claim = clone $model->where('tax_claims.status', $parameters['status']);
            }
        } else {
            if ($parameters['status'] == 'approved') {
                $claim = clone $model
                    ->where('tax_claims.status', '=', $parameters['status'])
                    ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
            } elseif ($parameters['status'] == 'both') {
                $claim = clone $model
                    ->orWhereIn('tax_claims.status', ['approved', 'rejected', 'pending'])
                    ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
            } else {
                $claim = clone $model->where('tax_claims.status', $parameters['status'])
                    ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
            }
        }

        if ($parameters['payment_status'] != null) {
            if ($parameters['payment_status'] != 'all') {
                if ($parameters['tax_payer_id'] != 'all') {
                    $claim = $claim
                        ->leftJoin('businesses', 'tax_claims.business_id', '=', 'businesses.id')
                        ->where('tax_credits.payment_status', '=', $parameters['payment_status'])
                        ->where('businesses.responsible_person_id', '=', $parameters['tax_payer_id']);
                } else {
                    $claim = $claim->where('tax_credits.payment_status', '=', $parameters['payment_status']);
                }
            } else {
                if ($parameters['tax_payer_id'] != 'all') {
                    $claim = $claim
                        ->leftJoin('businesses', 'tax_claims.business_id', '=', 'businesses.id')
                        ->where('businesses.responsible_person_id', '=', $parameters['tax_payer_id'])
                        ->orWhereIn('tax_credits.payment_status', ['paid', 'partially-paid', 'pending']);
                } else {
                    $claim = $claim
                        ->orWhereIn('tax_credits.payment_status', ['paid', 'partially-paid', 'pending'])
                        ->orWhereNull('tax_credits.payment_status');;
                }
            }

            if ($parameters['payment_method'] != 'all')
            {
                $claim = $claim->where('tax_credits.payment_method', '=', $parameters['payment_method']);
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
