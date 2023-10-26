<?php

namespace App\Traits;

use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyTypeStatus;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PaymentInterest;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait PropertyTaxTrait
{

    public function generateMonthlyInterest($propertyPayment) {
        // Check if payment due date diff in days btn now is 30 days
        $currentPaymentDate = Carbon::parse($propertyPayment->curr_payment_date);
        $currentDate = Carbon::now();

        $period = $currentDate->diffInMonths($currentPaymentDate);

        if ($currentDate->diffInDays($currentPaymentDate) === 30) {

            try {
                DB::beginTransaction();

                // Calculate interest and add it to current payment with new total amount
                $principalTaxAmount = $propertyPayment->amount;
                $interest = $this->calculatePaymentInterest($principalTaxAmount);
                $totalAmount = $propertyPayment + $interest;

                $newPaymentDate = $currentPaymentDate->addDays(30);

                // Record interest in payment interests table
                $propertyPayment->interest = $interest;
                $propertyPayment->total_amount = $totalAmount;
                $propertyPayment->curr_payment_date = $newPaymentDate;
                $propertyPayment->payment_category = PropertyPaymentCategoryStatus::DEBT;
                $propertyPayment->save();

                $currFinancialMonth = $this->getCurrentPropertyTaxFinancialMonth();

                PaymentInterest::create([
                    'property_payment_id' => $propertyPayment->id,
                    'financial_year_id' => $currFinancialMonth->year->id,
                    'financial_month_id' => $currFinancialMonth->id,
                    'amount' => $principalTaxAmount,
                    'interest' => $interest,
                    'total_amount' => $totalAmount,
                    'payment_date' => $newPaymentDate,
                    'period' => $period,
                ]);

                DB::commit();

                return $propertyPayment;
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }

        }

        return false;

    }

    private function calculatePaymentInterest($taxAmount){
        $rate = 0; // Fetch from DB
        $period = 0; // Fetch from DB
        return $taxAmount * pow((1 + $rate), $period);
    }

    private function getCurrentPropertyTaxFinancialMonth() {
        $now = Carbon::now();
        $financialYear = FinancialYear::where('code', $now->year)->firstOrFail();
        return FinancialMonth::where('financial_year_id', $financialYear->id)
            ->where('number', ($now->month))
            ->firstOrFail();
    }

    public function getPayableAmount($property) {
        $propertyType = $property->type;

        if ($propertyType === PropertyTypeStatus::CONDOMINIUM) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::CONDOMINIUM_BUILDING);
        } else if ($propertyType === PropertyTypeStatus::HOTEL) {
            $amount = $property->star->amount_charged;
        } else if ($propertyType === PropertyTypeStatus::RESIDENTIAL_STOREY) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::RESIDENTIAL_STOREY_BUILDING);
        } else if ($propertyType === PropertyTypeStatus::STOREY_BUSINESS) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::STOREY_BUSINESS_BUILDING);
        } else if ($propertyType === PropertyTypeStatus::OTHER) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::OTHER_BUSINESS_BUILDING);
        } else {
            throw new \Exception('Invalid Property Type Provided');
        }

        if (!$amount || $amount < 0) {
            throw new \Exception('Invalid Property Tax Amount');
        }

        return $amount;
    }

    private function getPropertyTaxPayableAmount($systemSettingCode) {
        $setting =  SystemSetting::where('code', $systemSettingCode)->firstOrFail();

        if ($setting->unit != 'number' && !is_numeric($setting->value)) {
            throw new \Exception('Property Tax System Setting Is Not A Number');
        }

        return $setting->value;
    }
}

