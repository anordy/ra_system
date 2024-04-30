<?php

namespace App\Traits;

use App\Enum\GeneralConstant;
use App\Enum\ReportStatus;
use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ReturnFilterTrait
{
    //filter data according to user criteria
    public function dataFilter($filter, $data, $returnTable)
    {
        try {
            if ($data == []) {
                $filter->whereMonth($returnTable . '.created_at', '=', date('m'));
                $filter->whereYear($returnTable . '.created_at', '=', date('Y'));
            }
            if (isset($data['type']) && $data['type'] != ReportStatus::all) {
                $filter->Where('return_category', $data['type']);
            }
            if (isset($data['year']) && $data['year'] != ReportStatus::All && $data['year'] != ReportStatus::CUSTOM_RANGE) {
                $filter->whereYear($returnTable . '.created_at', '=', $data['year']);
            }
            if (isset($data['month']) && $data['month'] != ReportStatus::all && $data['year'] != ReportStatus::CUSTOM_RANGE) {
                $filter->whereMonth($returnTable . '.created_at', '=', $data['month']);
            }
            if (isset($data['from']) && isset($data['to']) && $data['year'] == ReportStatus::CUSTOM_RANGE) {
                $from = Carbon::create($data['from'])->startOfDay();
                $to = Carbon::create($data['to'])->endOfDay();
                $filter->whereBetween($returnTable . '.created_at', [$from, $to]);
            }

            return $filter;
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-FILTER-TRAIT-DATA-FILTER', [$exception]);
            throw $exception;
        }

    }

    public function getSummaryData($model)
    {
        try {
            $vars = $model->select(
                DB::raw('COUNT(*) as totalSubmittedReturns'),
                DB::raw('SUM(CASE WHEN created_at > filing_due_date THEN 1 ELSE 0 END) as totalLateFiledReturns'),
                DB::raw('SUM(CASE WHEN created_at <= filing_due_date THEN 1 ELSE 0 END) as totalInTimeFiledReturns'),
                DB::raw('SUM(CASE WHEN paid_at IS NOT NULL THEN 1 ELSE 0 END) as totalPaidReturns'),
                DB::raw('SUM(CASE WHEN paid_at IS NULL THEN 1 ELSE 0 END) as totalUnpaidReturns'),
                DB::raw('SUM(CASE WHEN paid_at > payment_due_date THEN 1 ELSE 0 END) as totalLatePaidReturns')
            )
                ->first();

            // If any of the counts return NULL, replace them with zero
            $vars['totalSubmittedReturns'] = $vars['totalsubmittedreturns'] ?? GeneralConstant::ZERO_INT;
            $vars['totalLateFiledReturns'] = $vars['totallatefiledreturns'] ?? GeneralConstant::ZERO_INT;
            $vars['totalInTimeFiledReturns'] = $vars['totalintimefiledreturns'] ?? GeneralConstant::ZERO_INT;
            $vars['totalPaidReturns'] = $vars['totalpaidreturns'] ?? GeneralConstant::ZERO_INT;
            $vars['totalUnpaidReturns'] = $vars['totalunpaidreturns'] ?? GeneralConstant::ZERO_INT;
            $vars['totalLatePaidReturns'] = $vars['totallatepaidreturns'] ?? GeneralConstant::ZERO_INT;


            return $vars;
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-FILTER-TRAIT-GET-SUMMARY-DATA', [$exception]);
            throw $exception;
        }

    }

    //paid Returns
    public function paidReturns($returnClass, $returnTableName, $penaltyTableName)
    {
        try {
            $allowedCharacters = '/^[a-zA-Z0-9_]+$/';
            if (!preg_match($allowedCharacters, $penaltyTableName) || !preg_match($allowedCharacters, $returnTableName)) {
                throw new \Exception('Invalid penalty table name or return table name format');
            }

            // Return both USD and TZS
            $returnClass1 = clone $returnClass;
            $returnClass2 = $returnClass1;
            $penaltyData = $returnClass1->where("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])
                ->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
                ->select(
                    DB::raw('SUM(' . $penaltyTableName . '.late_filing) as totallatefiling'),
                    DB::raw('SUM(' . $penaltyTableName . '.late_payment) as totallatepayment'),
                    DB::raw('SUM(' . $penaltyTableName . '.rate_amount) as totalrate'),
                )
                ->groupBy('return_id')
                ->get();

            $totalTaxAmount = $returnClass2->where($returnTableName . '.status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])
                ->sum("{$returnTableName}.total_amount_due");

            // Return for USD and TZS

            return [
                'totalTaxAmount' => $totalTaxAmount ?? GeneralConstant::ZERO_INT,
                'totalLateFiling' => $penaltyData->sum('totallatefiling') ?? GeneralConstant::ZERO_INT,
                'totalLatePayment' => $penaltyData->sum('totallatepayment') ?? GeneralConstant::ZERO_INT,
                'totalRate' => $penaltyData->sum('totalrate') ?? GeneralConstant::ZERO_INT,
            ];
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-FILTER-TRAIT-PAID-RETURNS', [$exception]);
            throw $exception;
        }

    }

    //unpaid Returns
    public function unPaidReturns($returnClass, $returnTableName, $penaltyTableName)
    {
        try {
            $allowedCharacters = '/^[a-zA-Z0-9_]+$/';
            if (!preg_match($allowedCharacters, $penaltyTableName) || !preg_match($allowedCharacters, $returnTableName)) {
                throw new \Exception('Invalid penalty table name or return table name');
            }

            $returnClass1 = clone $returnClass;
            $returnClass2 = clone $returnClass;

            $penaltyData = $returnClass1->whereNotIn("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
                ->select(
                    DB::raw('SUM(' . $penaltyTableName . '.late_filing) as totallatefiling'),
                    DB::raw('SUM(' . $penaltyTableName . '.late_payment) as totallatepayment'),
                    DB::raw('SUM(' . $penaltyTableName . '.rate_amount) as totalrate'),
                )
                ->groupBy("{$returnTableName}.currency")
                ->get();

            $totalTaxAmount = $returnClass2->whereNotIn($returnTableName . '.status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->sum("{$returnTableName}.total_amount_due");

            return [
                'totalTaxAmount' => $totalTaxAmount ?? GeneralConstant::ZERO_INT,
                'totalLateFiling' => $penaltyData->sum('totallatefiling') ?? GeneralConstant::ZERO_INT,
                'totalLatePayment' => $penaltyData->sum('totallatepayment') ?? GeneralConstant::ZERO_INT,
                'totalRate' => $penaltyData->sum('totalrate') ?? GeneralConstant::ZERO_INT,
            ];
        } catch (\Exception $exception) {
            Log::error('TRAITS-RETURN-FILTER-TRAIT-UNPAID-RETURNS', [$exception]);
            throw $exception;
        }

    }
}
