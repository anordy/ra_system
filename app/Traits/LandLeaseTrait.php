<?php

namespace App\Traits;

use App\Enum\LeaseStatus;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\LeasePaymentPenalty;
use App\Models\PenaltyRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function createLeasePayment($landLease, $paymentFinancialMonth)
    {
        try {

            $financialYear = FinancialYear::where('code', $paymentFinancialMonth->due_date->year)->firstOrFail('id');

            $due_date = Carbon::parse($paymentFinancialMonth->due_date)->endOfMonth();

            $leasePayment = LeasePayment::create([
                'land_lease_id' => $landLease['id'],
                'taxpayer_id' => $landLease['taxpayer_id'],
                'financial_month_id' => $paymentFinancialMonth->id,
                'financial_year_id' => $financialYear->id,
                'total_amount' => $landLease['payment_amount'],
                'total_amount_with_penalties' => $landLease['payment_amount'],
                'outstanding_amount' => $landLease['payment_amount'],
                'status' => LeaseStatus::PENDING,
                'due_date' => $due_date,
            ]);

            $currentFinancialYearId = FinancialYear::where('code', Carbon::now()->year)->value('id');
            $currentFinancialMonth = FinancialMonth::select('id', 'name', 'due_date')
                ->where('financial_year_id', $currentFinancialYearId)
                ->where('number', Carbon::now()->month)
                ->firstOrFail();

            $penaltyIteration = 0;

            if ($currentFinancialMonth->due_date->year > Carbon::parse($paymentFinancialMonth->due_date)->year) {
                $penaltyIteration = Carbon::now() <= $currentFinancialMonth->due_date ? $currentFinancialMonth->due_date->startOfMonth()->diffInMonths($paymentFinancialMonth->due_date) : $currentFinancialMonth->due_date->diffInMonths($paymentFinancialMonth->due_date);
            }

            if ($penaltyIteration > 0) {

                $total_amount_with_penalties = $this->calculateLeasePenalties($leasePayment, $paymentFinancialMonth, $penaltyIteration);

                $updateLeasePayment = LeasePayment::find($leasePayment->id);
                $updateLeasePayment->penalty = $leasePayment->totalPenalties();
                $updateLeasePayment->total_amount_with_penalties = $total_amount_with_penalties;
                $updateLeasePayment->outstanding_amount = $total_amount_with_penalties;
                $updateLeasePayment->status = LeaseStatus::DEBT;
                $updateLeasePayment->created_at = Carbon::now();
                $updateLeasePayment->save();


                LandLeaseDebt::create([
                    'lease_payment_id' => $leasePayment->id,
                    'business_location_id' => $leasePayment->landLease->business_location_id,
                    'original_total_amount' => $leasePayment->total_amount,
                    'penalty' => $updateLeasePayment->penalty,
                    'total_amount' => $updateLeasePayment->total_amount_with_penalties,
                    'outstanding_amount' => $updateLeasePayment->outstanding_amount,
                    'status' => LeaseStatus::PENDING,
                    'last_due_date' => $due_date,
                    'curr_due_date' => Carbon::now()->endOfMonth(),
                ]);
            }

            return true;

        } catch (\Throwable $th) {
            Log::error('LAND-LEASE-TRAIT-CREATE-LEASE-PAYMENT', [$th]);
            return false;
        }
    }

    private function calculateLeasePenalties($leasePayment, $paymentFinancialMonth, $penaltyIteration)
    {
        $currentFinancialYearId = FinancialYear::where('code', Carbon::now()->year)->firstOrFail()->id;
        $penaltyRate = PenaltyRate::where('financial_year_id', $currentFinancialYearId)->where('code', 'LeasePenaltyRate')->firstOrFail()->rate;

        $principalAmount = $leasePayment->total_amount;
        $penaltyAmountAccumulated = 0;

        for ($i = 1; $i <= $penaltyIteration; $i++) {
            $penaltyAmount = round($principalAmount * $penaltyRate, 2);
            $penaltyAmountAccumulated += $penaltyAmount;
            $totalAmount = round($principalAmount + $penaltyAmountAccumulated, 2);

            LeasePaymentPenalty::create([
                'lease_payment_id' => $leasePayment->id,
                'tax_amount' => $principalAmount,
                'rate_percentage' => $penaltyRate,
                'penalty_amount' => $penaltyAmount,
                'total_amount' => $totalAmount,
                'start_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonth->due_date, $i)[0],
                'end_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonth->due_date, $i)[1],
            ]);
        }

        return $principalAmount + $penaltyAmountAccumulated;
    }

    public function getFirstLastDateOfMonth($due_date, $i)
    {
        $currentMonth = $due_date->addMonths($i);
        $start_date = clone $currentMonth->startOfMonth();
        $end_date = clone $currentMonth->endOfMonth();
        return [
            $start_date,
            $end_date
        ];
    }
}

