<?php

namespace App\Traits;

use App\Models\Returns\Petroleum\PetroleumReturn;
use Exception;
use Carbon\Carbon;
use App\Models\PenaltyRate;
use App\Models\InterestRate;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\Debts\DebtPenalty;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Debts\DebtScheduleState;
use App\Models\SevenDaysFinancialMonth;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\TaxAssessments\TaxAssessment;

class PenaltyForDebt
{
    use ExchangeRateTrait, VerificationTrait, TaxpayerLedgerTrait;

    public static function getTotalPenalties($debtId, $date, $taxAmount, $period)
    {

        $year = FinancialYear::where('code', $date->year)->firstOrFail();
        $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->firstOrFail()->rate;

        $penaltableAmount = $taxAmount;
        $latePaymentAmount = $latePaymentAfterRate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;
        $date->addDays(1);
        $fromDate = clone $date;
        $date->addDays(29);
        $toDate = clone $date;

        DebtPenalty::create([
            'debt_id' => $debtId,
            'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
            'tax_amount' => $taxAmount,
            'late_filing' => 0,
            'late_payment' => $latePaymentAmount,
            'rate_percentage' => $interestRate,
            'rate_amount' => $interestAmount,
            'penalty_amount' => $penaltableAmount,
            'start_date' => $fromDate,
            'end_date' => $toDate,
        ]);

        $totalAmount = $penaltableAmount;

        $endDate = DebtPenalty::where('debt_id', $debtId)
            ->latest()
            ->firstOrFail()->end_date;

        return [$endDate, $totalAmount];
    }


    /**
     * Generate returns penalty
     */
    public static function generateReturnsPenalty($tax_return)
    {
        // Join return penalties & Debt penalties
        $tax_return->return->penalties = $tax_return->return->penalties->concat($tax_return->penalties)->sortBy('tax_amount');

        if (count($tax_return->return->penalties) > 0) {
            $outstanding_amount = $tax_return->return->penalties->last()->penalty_amount;
        } else {
            if ($tax_return->return_type != PetroleumReturn::class) {
                $outstanding_amount = $tax_return->principal;
            } else {
                $outstanding_amount = $tax_return->principal + $tax_return->infrastructure;
            }
        }

        // If return has waiver, use the waived amount as outstanding amount
        if ($tax_return->waiver) {
            $outstanding_amount = $tax_return->outstanding_amount;
        }

        $curr_payment_due_date = Carbon::create($tax_return->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

        if (!$year) {
            throw new \Exception("JOB FAILED TO RUN, NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
        }

        $interestRate = InterestRate::where('year', $year->code)->first();

        if (!$interestRate) {
            throw new \Exception("JOB FAILED TO RUN, NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', PenaltyRate::LATE_PAYMENT_AFTER)
            ->first();

        if (!$latePaymentAfterRate) {
            throw new \Exception("JOB FAILED TO RUN, NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        // Subtract 1 from periods as the filing due date is of previous month
        $period = $tax_return->periods - 1;

        $period = round($period);

        $outstanding_amount = roundOff($outstanding_amount, $tax_return->currency);

        $penaltableAmount = $outstanding_amount;

        $start_date = Carbon::create($tax_return->curr_payment_due_date)->addDay()->startOfDay();
        $end_date = Carbon::create($tax_return->curr_payment_due_date)->addDays(30)->endOfDay();

        /**
         * If return is EM or MM Do not calculate interest Amount and Late payment amount is always constant ie. 1,000,000 (Fetched from DB)
         */
        if ($tax_return->return_type == EmTransactionReturn::class || $tax_return->return_type == MmTransferReturn::class) {
            $latePaymentAmount = PenaltyRate::where('financial_year_id', $tax_return->financialMonth->year->id)->where('code', PenaltyRate::PENALTY_FOR_MM_TRANSACTION)->firstOrFail()->rate;
            $latePaymentAmount = roundOff($latePaymentAmount, $tax_return->currency);
            $interestAmount = 0;
            $penaltableAmount = roundOff($latePaymentAmount + $penaltableAmount, $tax_return->currency);
        } else {
            $latePaymentAmount = 0;
            $penaltableAmount = $latePaymentAmount + $penaltableAmount;
            if ($end_date->gt(Carbon::today())) {
                $diffInDays = Carbon::today()->diffInDays($start_date);
            } else {
                $diffInDays = $end_date->diffInDays($start_date);
            }
            $interestAmount = roundOff(self::calculateInterest($penaltableAmount, $interestRate->rate, $diffInDays), $tax_return->currency);
            $penaltableAmount = roundOff($penaltableAmount + $interestAmount, $tax_return->currency);
        }

        try {
            $previous_debt_penalty_id = $tax_return->latestPenalty->id ?? null;

            $debtPenalty = DebtPenalty::create([
                'debt_id' => $tax_return->id,
                'debt_type' => TaxReturn::class,
                'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
                'tax_amount' => $outstanding_amount,
                'late_filing' => 0,
                'late_payment' => $latePaymentAmount ?? 0,
                'rate_percentage' => $interestRate->rate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'start_date' => $start_date->startOfDay(),
                'end_date' => $end_date,
                'currency' => $tax_return->currency,
                'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($tax_return->currency)
            ]);

            if (!(new PenaltyForDebt)->verify($tax_return)) {
                throw new Exception('Verification failed for tax return, please contact your system administrator for help.');
            }

            $tax_return->principal = roundOff($tax_return->principal, $tax_return->currency);
            $tax_return->penalty = roundOff($tax_return->penalty + $debtPenalty->late_payment, $tax_return->currency);
            $tax_return->interest = roundOff($tax_return->interest + $debtPenalty->rate_amount, $tax_return->currency);
            $tax_return->curr_payment_due_date = $end_date;
            $tax_return->total_amount = round($penaltableAmount, 2);
            $tax_return->outstanding_amount = round($penaltableAmount, 2);
            $tax_return->save();

            TaxpayerLedgerTrait::recordLedgerDebt(TaxReturn::class, $tax_return->id, $tax_return->interest, $tax_return->penalty, $tax_return->total_amount);

            (new PenaltyForDebt)->sign($tax_return);

            self::recordReturnDebtState($tax_return, $previous_debt_penalty_id, $debtPenalty->id);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Generate debt assessment penalty
     */
    public static function generateAssessmentsPenalty($assessment)
    {
        $outstanding_amount = $assessment->outstanding_amount;

        if (count($assessment->penalties) > 0) {
            $outstanding_amount = $assessment->penalties->last()->penalty_amount;
        } else {
            $outstanding_amount = $assessment->principal;
        }

        // If assessment has waiver, use the waived amount as outstanding amount
        if ($assessment->waiver) {
            $outstanding_amount = $assessment->outstanding_amount;
        }

        $curr_payment_due_date = Carbon::create($assessment->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

        if (!$year) {
            throw new \Exception("JOB FAILED TO RUN, NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
        }

        $interestRate = InterestRate::where('year', $year->code)->first();

        if (!$interestRate) {
            throw new \Exception("JOB FAILED TO RUN, NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->first();

        if (!$latePaymentAfterRate) {
            throw new \Exception("JOB FAILED TO RUN, NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $period = $assessment->periods + $assessment->penatableMonths;

        $penaltableAmount = $outstanding_amount;
        $latePaymentAmount = $latePaymentAfterRate->rate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate->rate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;

        $start_date = Carbon::create($assessment->curr_payment_due_date)->addDay()->startOfDay();
        $end_date = Carbon::create($assessment->curr_payment_due_date)->addDays(30)->endOfDay();

        $previous_debt_penalty_id = $assessment->latestPenalty->id ?? null;

        try {
            $debtPenalty = DebtPenalty::create([
                'debt_id' => $assessment->id,
                'debt_type' => TaxAssessment::class,
                'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
                'tax_amount' => $outstanding_amount,
                'late_filing' => 0,
                'late_payment' => $latePaymentAmount,
                'rate_percentage' => $interestRate->rate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'currency' => $assessment->currency,
                'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($assessment->currency)
            ]);

            $assessment->penalty_amount = $assessment->penalty_amount + $debtPenalty->late_payment;
            $assessment->interest_amount = $assessment->interest_amount + $debtPenalty->rate_amount;
            $assessment->curr_payment_due_date = $end_date;
            $assessment->total_amount = round($penaltableAmount, 2);
            $assessment->outstanding_amount = round($penaltableAmount, 2);
            $assessment->save();

            TaxpayerLedgerTrait::recordLedgerDebt(TaxAssessment::class, $assessment->id, $assessment->interest_amount, $assessment->penalty_amount, $assessment->total_amount);

            self::recordAssessmentDebtState($assessment, $previous_debt_penalty_id, $debtPenalty->id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }

    /**
     * Record tax return debt state
     */
    public static function recordReturnDebtState($tax_return, $previous_debt_penalty_id, $current_debt_penalty_id)
    {
        try {
            DebtScheduleState::create([
                'penalty' => $tax_return->penalty,
                'interest' => $tax_return->interest,
                'total_amount' => $tax_return->total_amount,
                'outstanding_amount' => $tax_return->outstanding_amount,
                'previous_debt_penalty_id' => $previous_debt_penalty_id,
                'current_debt_penalty_id' => $current_debt_penalty_id,
                'debt_type' => TaxReturn::class,
                'debt_id' => $tax_return->id,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Record tax assessment debt state 
     */
    public static function recordAssessmentDebtState($assessment, $previous_debt_penalty_id, $current_debt_penalty_id)
    {
        try {
            DebtScheduleState::create([
                'penalty' => $assessment->penalty_amount,
                'interest' => $assessment->interest_amount,
                'total_amount' => $assessment->total_amount,
                'outstanding_amount' => $assessment->outstanding_amount,
                'previous_debt_penalty_id' => $previous_debt_penalty_id,
                'current_debt_penalty_id' => $current_debt_penalty_id,
                'debt_type' => TaxAssessment::class,
                'debt_id' => $assessment->id,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function getPostVettingPenalties($tax_return, $iterations)
    {
        if ($iterations > 0 && $tax_return->outstanding_amount > 0) {
            $curr_payment_due_date = Carbon::create($tax_return->curr_payment_due_date);

            $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

            if (!$year) {
                throw new \Exception("NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
            }

            $interestRate = InterestRate::where('year', $year->code)->first();

            if (!$interestRate) {
                throw new \Exception("NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
            }

            $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
                ->where('code', PenaltyRate::LATE_PAYMENT_AFTER)
                ->first();

            if (!$latePaymentAfterRate) {
                throw new \Exception("NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
            }

            $paymentStructure = [];
            $penaltableAmountForPerticularMonth = $tax_return->outstanding_amount;

            $date = self::getFinancialMonthFromDate($tax_return->curr_payment_due_date, $tax_return->return_type);

            for ($i = 0; $i < $iterations; $i++) {

                $startDate = $date->due_date;
                $endDate = self::getNextFinancialMonthDueDateFromDate($tax_return->return_type, $date->due_date)->due_date;

                if ($tax_return->return_type == EmTransactionReturn::class || $tax_return->return_type == MmTransferReturn::class) {
                    $latePaymentAmount = PenaltyRate::where('financial_year_id', $tax_return->financialMonth->year->id)->where('code', PenaltyRate::PENALTY_FOR_MM_TRANSACTION)->firstOrFail()->rate;
                    $latePaymentAmount = roundOff($latePaymentAmount, $tax_return->currency);
                    $interestAmount = 0;
                    $penaltableAmount = roundOff($latePaymentAmount + $penaltableAmountForPerticularMonth, $tax_return->currency);
                } else {
                    $period = round($tax_return->periods) + $i + 1;
                    $latePaymentAmount = 0;
                    $penaltableAmount = $latePaymentAmount + $penaltableAmountForPerticularMonth;

                    if ($endDate->gt(Carbon::today())) {
                        $diffInDays = Carbon::today()->diffInDays($startDate);
                    } else {
                        $diffInDays = $endDate->diffInDays($startDate);
                    }

                    $interestAmount = roundOff(self::calculateInterest($penaltableAmount, $interestRate->rate, $diffInDays), $tax_return->currency);
                    $penaltableAmount = roundOff($penaltableAmount + $interestAmount, $tax_return->currency);
                }

                $paymentStructure[] = [
                    // Sub Month as start date to enddate interval reflects return month
                    'returnMonth' => $date->due_date->subMonth()->monthName . '-' . $date->due_date->subMonth()->year,
                    'taxAmount' => $penaltableAmountForPerticularMonth,
                    'lateFilingAmount' =>  0,
                    'latePaymentAmount' => $latePaymentAmount ?? 0,
                    'interestRate' => $interestRate->rate,
                    'interestAmount' => $interestAmount,
                    'penaltyAmount' => $penaltableAmount,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ];
                $penaltableAmountForPerticularMonth = $penaltableAmount;
                $date = self::getFinancialMonthFromDate($paymentStructure[$i]['end_date'], $tax_return->return_type);
            }

            if (count($paymentStructure) > 0) {
                try {
                    foreach ($paymentStructure as $penalty) {
                        DebtPenalty::create([
                            'debt_id' => $tax_return->id,
                            'debt_type' => TaxReturn::class,
                            'financial_month_name' => $penalty['returnMonth'],
                            'tax_amount' => $penalty['taxAmount'],
                            'late_filing' => 0,
                            'late_payment' => $penalty['latePaymentAmount'],
                            'rate_percentage' => $penalty['interestRate'],
                            'rate_amount' => $penalty['interestAmount'],
                            'penalty_amount' => $penalty['penaltyAmount'],
                            'start_date' => $penalty['start_date'],
                            'end_date' => $penalty['end_date'],
                            'currency' => $tax_return->currency,
                            'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($tax_return->currency)
                        ]);
                    }

                    $totalLatePaymentPenalty = 0;
                    $totalInterest = 0;
                    foreach ($paymentStructure as $penalty) {
                        $totalLatePaymentPenalty += $penalty['latePaymentAmount'];
                        $totalInterest += $penalty['interestAmount'];
                    }

                    $tax_return->penalty = $tax_return->penalty + $totalLatePaymentPenalty;
                    $tax_return->interest = $tax_return->interest + $totalInterest;
                    $tax_return->curr_payment_due_date = end($paymentStructure)['end_date'];
                    $tax_return->total_amount = end($paymentStructure)['penaltyAmount'];
                    $tax_return->outstanding_amount = end($paymentStructure)['penaltyAmount'];

                    $tax_return->save();

                    (new PenaltyForDebt)->sign($tax_return);

                    return $tax_return;
                } catch (Exception $e) {
                    Log::error('Error: ' . $e->getMessage(), [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw new Exception('Failed saving debt penalties');
                }
            }
        }

        return $tax_return;
    }

    public static function getNextFinancialMonthDueDateFromDate($return_type, $date)
    {
        $date = Carbon::create($date);

        if ($return_type == EmTransactionReturn::class || $return_type == MmTransferReturn::class) {
            $monthTable = SevenDaysFinancialMonth::class;
        } else {
            $monthTable = FinancialMonth::class;
        }

        $financialMonth = $monthTable::whereRaw('EXTRACT(YEAR FROM due_date) = ' . $date->year . '')
            ->whereRaw('EXTRACT(MONTH FROM due_date)  = ' . $date->month . ' ')
            ->first();

        if (!$financialMonth) {
            throw new Exception("NO FINANCIAL MONTH FOR MONTH {$date->month} AND YEAR {$date->year}");
        }

        $financialMonth = self::getNextFinancialMonth($financialMonth, $return_type);

        return $financialMonth;
    }

    public static function getNextFinancialMonth($financialMonth, $return_type)
    {
        if ($return_type == EmTransactionReturn::class || $return_type == MmTransferReturn::class) {
            $monthTable = SevenDaysFinancialMonth::class;
        } else {
            $monthTable = FinancialMonth::class;
        }

        if ($financialMonth->number == 12) {
            $code = $financialMonth->year->code + 1;
            $year = FinancialYear::where('code', $financialMonth->year->code + 1)->first();

            if (!$year) {
                throw new \Exception("NO DATA FOR FINANCIAL YEAR {$code}");
            }
            $month = $monthTable::where('number', 1)
                ->where('financial_year_id', $year->id)
                ->firstOrFail();
        } else {
            $month = $monthTable::where('financial_year_id', $financialMonth->financial_year_id)
                ->where('number', $financialMonth->number + 1)
                ->firstOrFail();
        }
        $month->due_date = Carbon::create($month->due_date);
        return $month;
    }

    public static function getFinancialMonthFromDate($date, $return_type)
    {
        if ($return_type == EmTransactionReturn::class || $return_type == MmTransferReturn::class) {
            $monthTable = SevenDaysFinancialMonth::class;
        } else {
            $monthTable = FinancialMonth::class;
        }

        if ($date->month == 12) {

            $year = FinancialYear::where('code', $date->year + 1)->first();

            if (!$year) {
                throw new \Exception("NO FINANCIAL YEAR DATA FOUND");
            }

            $month = $monthTable::where('number', 1)
                ->where('financial_year_id', $year->id)
                ->firstOrFail();
        } else {
            $year = FinancialYear::where('code', $date->year)->first();

            if (!$year) {
                throw new \Exception("NO FINANCIAL YEAR DATA FOUND");
            }

            $month = $monthTable::where('financial_year_id', $year->id)
                ->where('number', $date->month)
                ->firstOrFail();
        }

        $month->due_date = Carbon::create($month->due_date);
        return $month;
    }
}
