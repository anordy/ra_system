<?php

namespace App\Traits;

use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyTypeStatus;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PaymentInterest;
use App\Models\PropertyTax\Property;
use App\Models\Region;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait PropertyTaxTrait
{

    public function generateMonthlyInterest($propertyPayment) {
        // Check if payment due date diff in days btn now is 30 days
        $currentPaymentDate = Carbon::parse($propertyPayment->curr_payment_date);
        $currentDate = Carbon::now();

        $period = $currentDate->diffInMonths($currentPaymentDate);

        // TODO: Add condition to calculate interest whenever viable but not everyday
        if ($currentDate->diffInDays($currentPaymentDate) === 30) {

            try {
                DB::beginTransaction();

                // Calculate interest and add it to current payment with new total amount
                $principalTaxAmount = $propertyPayment->amount + $propertyPayment->interest;
                $interest = $this->calculatePaymentInterest($principalTaxAmount, $period);
                $totalAmount = $propertyPayment->amount + $interest;

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

    private function calculatePaymentInterest($taxAmount, $period){
        $rate = SystemSetting::where('code', SystemSetting::PROPERTY_TAX_INTEREST_RATE)->firstOrFail()->value;
        $numberOfTimesInterestIsCompounded = SystemSetting::where('code', SystemSetting::NUMBER_OF_TIMES_INTEREST_IS_COMPOUNDED_IN_PROPERTY_TAX_PER_YEAR)->firstOrFail()->value;
        return $taxAmount * pow((1 + ($rate/$numberOfTimesInterestIsCompounded)), ($period));
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
            $amount = $property->storeys->count() * $amount;
        } else if ($propertyType === PropertyTypeStatus::STOREY_BUSINESS) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::STOREY_BUSINESS_BUILDING);
            $amount = $property->storeys->count() * $amount;
        } else if ($propertyType === PropertyTypeStatus::OTHER) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::OTHER_BUSINESS_BUILDING);
        } else {
            throw new \Exception('Invalid Property Type Provided');
        }

        if (!$amount || $amount <= 0) {
            throw new \Exception('Invalid Property Tax Amount');
        }

        return $amount;
    }

    public function previewPayableAmount($property) {
        $propertyType = $property->type;

        if ($propertyType === PropertyTypeStatus::CONDOMINIUM) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::CONDOMINIUM_BUILDING);
            $breakDown = [
                'units' => 1,
                'amount' => $amount,
                'total_amount' => $amount
            ];
        } else if ($propertyType === PropertyTypeStatus::HOTEL) {
            $amount = $property->star->amount_charged;
            $breakDown = [
                'units' => 1,
                'amount' => $amount,
                'total_amount' => $amount
            ];
        } else if ($propertyType === PropertyTypeStatus::RESIDENTIAL_STOREY) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::RESIDENTIAL_STOREY_BUILDING);
            $breakDown = [
                'units' => $property->storeys->count(),
                'amount' => $amount,
                'total_amount' => $property->storeys->count() * $amount
            ];
        } else if ($propertyType === PropertyTypeStatus::STOREY_BUSINESS) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::STOREY_BUSINESS_BUILDING);
            $breakDown = [
                'units' => $property->storeys->count(),
                'amount' => $amount,
                'total_amount' => $property->storeys->count() * $amount
            ];
        } else if ($propertyType === PropertyTypeStatus::OTHER) {
            $amount = $this->getPropertyTaxPayableAmount(SystemSetting::OTHER_BUSINESS_BUILDING);
            $breakDown = [
                'units' => 1,
                'amount' => $amount,
                'total_amount' => $amount
            ];
        } else {
            throw new \Exception('Invalid Property Type Provided');
        }

        if ($breakDown['total_amount'] <= 0) {
            throw new \Exception('Invalid Property Tax Amount');
        }

        return $breakDown;
    }


    private function getPropertyTaxPayableAmount($systemSettingCode) {
        $setting =  SystemSetting::where('code', $systemSettingCode)->firstOrFail();

        if ($setting->unit != 'number' && !is_numeric($setting->value)) {
            throw new \Exception('Property Tax System Setting Is Not A Number');
        }

        return $setting->value;
    }

    public function generateURN($property) {
        $region = Region::where('name', 'like', '%'. $property->region_id .'%')->first();

        $location = $region->location;

        if (!$region) {
            $region = Region::first();
            $location = 'unguja';
        }

        if ($location === 'unguja') {
            $locationCode = '01';
        } else if ($location === 'pemba') {
            $locationCode = '02';
        } else {
            throw new \Exception('Invalid Location Provided');
        }

        $regionCode = sprintf("%02d", $region->id);
        $districtCode = sprintf("%02d", random_int(1,99));
        $wardCode = sprintf("%02d", random_int(1,99));

        $urn = "{$locationCode}{$regionCode}{$districtCode}{$wardCode}";

        // Hotels do not have house number
        if ($property->type === PropertyTypeStatus::HOTEL) {
            $hotelId = sprintf("%02d", $property->id);
            $urn = "{$urn}-{$property->hotel_stars_id}{$hotelId}";
        } else if ($property->type === PropertyTypeStatus::OTHER ||
            $property->type === PropertyTypeStatus::RESIDENTIAL_STOREY ||
            $property->type === PropertyTypeStatus::STOREY_BUSINESS) {
            $id = $property->id;
            $urn = "{$urn}-{$id}";
        } else if ($property->type === PropertyTypeStatus::CONDOMINIUM) {
            $id = $property->id;
            $urn = "{$urn}-{$id}";
        } else {
            throw new \Exception('Invalid Property Type Provided');
        }

        $doesURNExists = Property::where('urn', $urn)->first();

        if ($doesURNExists) {
            Log::error("DUPLICATE URN FOUND {$urn}");
            throw new \Exception('Duplicate URN Found');
        }

        return $urn;
    }
}

