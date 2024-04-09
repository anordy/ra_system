<?php

namespace App\Traits;

use App\Enum\PaymentStatus;
use App\Enum\ReportStatus;
use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaim;
use Carbon\Carbon;

trait ClaimReportTrait
{
    public function getRecords($parameters)
    {
        try {
            $model = TaxClaim::query()->select('tax_claims.id', 'tax_claims.business_id', 'tax_claims.location_id', 'tax_claims.tax_type_id',
                'tax_claims.amount', 'tax_claims.currency', 'tax_claims.financial_month_id', 'tax_claims.status', 'tax_claims.approved_on',
                'tax_claims.created_at', 'tax_credits.payment_method', 'tax_credits.installments_count', 'tax_credits.payment_status')->with('business')
                ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id');

            if (!isset($parameters['status'])) {
                throw new \Exception('Missing status key in parameters on ClaimReportTrait in getRecords()');
            }

            if (isset($parameters['duration']) && $parameters['duration'] == ReportStatus::yearly) {
                if ($parameters['status'] == TaxClaimStatus::APPROVED) {
                    $claim = clone $model
                        ->where('tax_claims.status', '=', $parameters['status']);
                } elseif ($parameters['status'] == ReportStatus::both) {
                    $claim = clone $model
                        ->whereIn('tax_claims.status', [TaxClaimStatus::APPROVED, TaxClaimStatus::REJECTED, TaxClaimStatus::PENDING]);
                } else {
                    $claim = clone $model->where('tax_claims.status', $parameters['status']);
                }
            } else {
                if (!isset($parameters['from']) && !isset($parameters['to'])) {
                    throw new \Exception('Missing from and to key in parameters on ClaimReportTrait in getRecords()');
                }

                if ($parameters['status'] == TaxClaimStatus::APPROVED) {
                    $claim = clone $model
                        ->where('tax_claims.status', '=', $parameters['status'])
                        ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
                } elseif ($parameters['status'] == ReportStatus::both) {
                    $claim = clone $model
                        ->orWhereIn('tax_claims.status', [TaxClaimStatus::APPROVED, TaxClaimStatus::REJECTED, TaxClaimStatus::PENDING])
                        ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
                } else {
                    $claim = clone $model->where('tax_claims.status', $parameters['status'])
                        ->whereBetween('tax_claims.created_at', [Carbon::createFromFormat('Y-m-d', $parameters['from']), Carbon::createFromFormat('Y-m-d', $parameters['to'])]);
                }
            }

            if (!array_key_exists('payment_status', $parameters)) {
                throw new \Exception('Missing payment_status key in parameters on ClaimReportTrait in getRecords()');
            }

            if ($parameters['payment_status'] != null) {
                if ($parameters['payment_status'] != ReportStatus::all) {
                    if (isset($parameters['tax_payer_id']) && $parameters['tax_payer_id'] != ReportStatus::all) {
                        $claim = $claim
                            ->leftJoin('businesses', 'tax_claims.business_id', '=', 'businesses.id')
                            ->where('tax_credits.payment_status', '=', $parameters['payment_status'])
                            ->where('businesses.responsible_person_id', '=', $parameters['tax_payer_id']);
                    } else {
                        $claim = $claim->where('tax_credits.payment_status', '=', $parameters['payment_status']);
                    }
                } else {
                    if (isset($parameters['tax_payer_id']) && $parameters['tax_payer_id'] != ReportStatus::all) {
                        $claim = $claim
                            ->leftJoin('businesses', 'tax_claims.business_id', '=', 'businesses.id')
                            ->where('businesses.responsible_person_id', '=', $parameters['tax_payer_id'])
                            ->orWhereIn('tax_credits.payment_status', [PaymentStatus::PAID, PaymentStatus::PARTIALLY_PAID, PaymentStatus::PENDING]);
                    } else {
                        $claim = $claim
                            ->orWhereIn('tax_credits.payment_status', [PaymentStatus::PAID, PaymentStatus::PARTIALLY_PAID, PaymentStatus::PENDING])
                            ->orWhereNull('tax_credits.payment_status');;
                    }
                }

                if (isset($parameters['payment_method']) && $parameters['payment_method'] != ReportStatus::all)
                {
                    $claim = $claim->where('tax_credits.payment_method', '=', $parameters['payment_method']);
                }
            }

            return $this->getSelectedRecords($claim, $parameters);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }


    public function getSelectedRecords($records, $parameters)
    {
        if (!isset($parameters['dates'])) {
            throw new \Exception('Missing dates key in parameters on ClaimReportTrait in getSelectedRecords()');
        }

        try {
            $dates = $parameters['dates'];

            if ($dates == []) {
                return $records->orderBy("tax_claims.created_at", 'asc');
            }

            if (!array_key_exists('startDate', $dates) && !array_key_exists('endDate', $dates)) {
                throw new \Exception('Missing startDate and endDate keys in parameters on PaymentReportTrait in getSelectedRecords()');
            }

            if ($dates['startDate'] == null || $dates['endDate'] == null) {
                return $records->orderBy("tax_claims.created_at", 'asc');
            }

            return $records->whereBetween("tax_claims.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("tax_claims.created_at", 'asc');
        } catch (\Exception $exception) {
            throw $exception;
        }


    }
}
