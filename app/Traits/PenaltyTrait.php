<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\Currency;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait PenaltyTrait
{

    use ExchangeRateTrait;

    /**
     * Get total penalties iteration that includes skipped penalties for a financial month
     * @param $financialMonth
     * @param $taxAmount
     * @param $taxTypeCurency
     * @return array
     * @throws \Exception
     */
    public function getTotalPenalties($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $lateFilingFee = 0;
            if ($this->isLateFiling($financialMonth)) {
                $lateFilingFee = $this->getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency);
            }

            // Get late total payments
            $penaltableAmount = 0;

            $date = $this->getDateFromFinancialMonth($financialMonth);
            $diffInMonths = $date->diffInMonths(Carbon::now());
            $interestRate = InterestRate::where('year', $financialMonth->year->code)->firstOrFail()->rate;
            $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPB')->firstOrFail()->rate;
            $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPA')->firstOrFail()->rate;

            $paymentStructure = [];
            $penaltableAMountForPerticularMonth = 0;
            for ($i = 0; $i < $diffInMonths; $i++) {
                if ($i === 0) {

                    $penaltableAmount = $lateFilingFee + $taxAmount;
                    $latePaymentAmount = ($latePaymentBeforeRate * $penaltableAmount);
                    $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                    $interestAmount = $this->calculateInterest($penaltableAmount, $interestRate, $i);
                    $penaltableAmount = $interestAmount + $penaltableAmount;

                    $penaltableAMountForPerticularMonth = $penaltableAmount;

                    $paymentStructure[] = [
                        'returnMonth' => $date->monthName,
                        'taxAmount' => $taxAmount,
                        'penaltyAmount' => $penaltableAMountForPerticularMonth,
                        'lateFilingAmount' => $lateFilingFee ?? 0,
                        'latePaymentAmount' => 0,
                        'interestRate' => $interestRate,
                        'interestAmount' => $interestAmount
                    ];

                    $date->addMonth();
                    continue;
                }

                $latePaymentAmount = ($latePaymentAfterRate * $penaltableAmount);
                $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                $interestAmount = $this->calculateInterest($penaltableAmount, $interestRate, $i);
                $penaltableAmount = $penaltableAmount + $interestAmount;

                $paymentStructure[] = [
                    'returnMonth' => $date->monthName,
                    'taxAmount' => $penaltableAMountForPerticularMonth,
                    'penaltyAmount' => $penaltableAmount,
                    'lateFilingAmount' => 0,
                    'latePaymentAmount' => 0,
                    'interestRate' => $interestRate,
                    'interestAmount' => $interestAmount,
                ];

                $penaltableAMountForPerticularMonth = $penaltableAmount;
                $date->addMonth();

            }
            return $paymentStructure;

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-TOTAl-PENALTIES', [$exception]);
            throw $exception;
        }
    }

    /**
     * Get total summary data for a filed return in total amount, interest & penalty
     * @param $financialMonth
     * @param $taxAmount
     * @param $taxTypeCurency
     * @return array
     * @throws \Exception
     */
    public function getTotals($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $penalty = $this->getTotalPenalties($financialMonth, $taxAmount, $taxTypeCurency);

            if (count($penalty)) {
                return [
                    'total' => end($penalty)['penaltyAmount'],
                    'penalty' => end($penalty)['penaltyAmount'] - $taxAmount,
                    'interest' => 0
                ];
            } else {
                return [
                    'total' => $taxAmount,
                    'penalty' => 0,
                    'interest' => 0
                ];
            }

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-TOTALS', [$exception]);
            throw $exception;
        }
    }

    /**
     * Check if the financial month is late filing
     * @param $financialMonth
     * @return bool
     * @throws \Exception
     */
    public function isLateFiling($financialMonth)
    {
        try {
            // We have filing month
            if (Carbon::now()->greaterThan($financialMonth->due_date)) {
                return true;
            }

            return false;

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-IS-LATE-FILING', [$exception]);
            throw $exception;
        }
    }

    /**
     * Get late filing fee from the provided financial month
     * @param $financialMonth
     * @param $taxAmount
     * @param $taxTypeCurency
     * @return float|int
     * @throws \Exception
     */
    public function getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $lateFilingRate = PenaltyRate::select('rate')->where('financial_year_id', $financialMonth->year->id)->where('code', 'LF')->firstOrFail()->rate;
            $percentageFee = $lateFilingRate * $taxAmount;
            $weGRate = PenaltyRate::select('rate')->where('financial_year_id', $financialMonth->year->id)->where('code', 'WEG')->firstOrFail()->rate;

            $rate = self::getExchangeRate($taxTypeCurency);

            if ($taxTypeCurency != Currency::TZS) {
                $percentageFee = ($percentageFee * $rate);
            }

            if ($percentageFee >= $weGRate) {
                return ($percentageFee / $rate);
            }

            if ($taxTypeCurency != Currency::TZS) {
                $weGRate = ($weGRate / $rate);
            }

            return $weGRate;

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-LATE-FILING', [$exception]);
            throw $exception;
        }
    }

    /**
     * Get filing month for a return & location, basically this is checks the last return and proceed to next viable return
     * if no previous/first return a new filing will be initiated from the effective date of business location
     * @param $locationId
     * @param $ReturnClass
     * @return false|mixed
     * @throws \Exception
     */
    public function getFilingMonth($locationId, $ReturnClass)
    {
        try {
            // Check last return w/ Status
            if ($return = $ReturnClass::where('business_location_id', $locationId)->latest()->first()) {
                if ($return->status === 'complete') {
                    return $this->checkNextViableReturnMonth($return->financialMonth);
                } else {
                    return $return->financialMonth;
                }
            }

            // If not, Check date of commence
            $date = BusinessLocation::findOrFail($locationId)->business->effective_date;
            $financialYear = FinancialYear::where('code', $date->year)->firstOrFail();
            $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', $date->month)
                ->firstOrFail();
            return $this->checkNextViableReturnMonth($financialMonth);

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-FILING-MONTH', [$exception]);
            throw $exception;
        }
    }

    /**
     * Check if return is viable for filing on the provided financial month
     * @param $financialMonth
     * @return false
     * @throws \Exception
     */
    public function checkNextViableReturnMonth($financialMonth)
    {
        try {
            // Get next return month,
            // check if 12, add year, get first month
            if ($financialMonth->number === 12) {
                $year = FinancialYear::where('code', $financialMonth->year->code + 1);
                $month = FinancialMonth::where('number', 1)
                    ->where('financial_year_id', $year->id)
                    ->firstOrFail();
            } else {
                $month = FinancialMonth::where('financial_year_id', $financialMonth->financial_year_id)
                    ->where('number', $financialMonth->number + 1)
                    ->firstOrFail();
            }

            $date = $this->getDateFromFinancialMonth($month);
            // Compare with current date
            if ($date->lessThanOrEqualTo(Carbon::now())) {
                return $month;
            } else {
                return false;
            }

        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-CHECK-NEXT-VIABLE-MONTH', [$exception]);
            throw $exception;
        }
    }

    /**
     * Get the first day of the month from the provided financial month
     * @param $financialMonth
     * @return Carbon|false
     */
    public function getDateFromFinancialMonth($financialMonth)
    {
        try {
            return Carbon::create($financialMonth->year->code, $financialMonth->number, 1);
        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-DATE-FROM-FINANCIAL-MONTH', [$exception]);
            throw $exception;
        }
    }

    /**
     * Calculate interest amount based on the compound interest formula
     * @param $taxAmount
     * @param $rate
     * @param $period
     * @return float|int
     */
    public function calculateInterest($taxAmount, $rate, $period)
    {
        try {
            $interest = ($taxAmount * pow((1 + $rate), $period)) - $taxAmount;
            return $interest;
        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-CALCULATE-INTEREST', [$exception]);
            throw $exception;
        }
    }

    /**
     * Get the current financial month based on current date
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentFinancialMonth()
    {
        try {
            $now = Carbon::now();

            $financialYear = FinancialYear::where('code', $now->year)->firstOrFail();
            $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', ($now->month))
                ->firstOrFail();
            return $financialMonth;
        } catch (\Exception $exception) {
            Log::error('PENALTY-TRAIT-GET-FINANCIAL-MONTH', [$exception]);
            throw $exception;
        }

    }
}

