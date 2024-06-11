<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Models\DateConfiguration;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\LeasePayment;
use App\Traits\LandLeaseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class CreateLeasePayment extends Component
{
    use CustomAlert, LandLeaseTrait;

    public $landLease;
    public $leasePayment;
    public $previousFinancialMonthDueDate;
    public $paymentFinancialMonth;
    public $displayYear;
    public $displayMonth;
    public $eligibleToPayment = false;
    public $unpaidLease;

    public function mount($landLease)
    {
        $this->landLease = $landLease;
        
        $statuses = [
            LeaseStatus::IN_ADVANCE_PAYMENT,
            LeaseStatus::LATE_PAYMENT,
            LeaseStatus::ON_TIME_PAYMENT,
            LeaseStatus::COMPLETE
        ];
            
        $leasePayment = LeasePayment::where('land_lease_id', $this->landLease['id'])
        ->whereIn('status', $statuses)
        ->latest()->first();

        $this->leasePayment = $leasePayment;
            
            if ($this->leasePayment) {
                $this->previousFinancialMonthDueDate = $this->leasePayment->financialMonth->due_date;
                $paymentFinancialDate = clone $this->previousFinancialMonthDueDate->addYear();
                $this->displayYear = $paymentFinancialDate->year;
                $financialYear = FinancialYear::where('code', $this->displayYear)->firstOrFail('id');
                $this->displayMonth = $paymentFinancialDate->format('F');
                $this->paymentFinancialMonth = FinancialMonth::where('name', $this->landLease['payment_month'])->where('financial_year_id', $financialYear->id)->firstOrFail();
            } else {
                $leasePayment = LeasePayment::where('land_lease_id', $this->landLease['id'])
                ->latest()->first();
    
                if ($leasePayment) {
                    $this->previousFinancialMonthDueDate = $leasePayment->financialMonth->due_date;
                    $paymentFinancialDate = clone $this->previousFinancialMonthDueDate->addYear();
                    $this->displayYear = $paymentFinancialDate->year;
                    $financialYear = FinancialYear::where('code', $this->displayYear)->firstOrFail('id');
                    $this->displayMonth = $paymentFinancialDate->format('F');
                    $this->paymentFinancialMonth = FinancialMonth::where('name', $this->landLease['payment_month'])->where('financial_year_id', $financialYear->id)->firstOrFail();
                } else {
                    $commence_date = Carbon::parse($this->landLease['commence_date']);
                    $currentYear = $commence_date->year;
                    $financialYear = FinancialYear::where('code', $currentYear)->firstOrFail('id');
                    
                    if ($financialYear) {
                        $paymentFinancialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)->where('name', $this->landLease['payment_month'])->firstOrFail();
                        $this->paymentFinancialDate = $paymentFinancialMonth->due_date;
                        $this->displayYear = $this->paymentFinancialDate->year;
                        $this->displayMonth = $this->paymentFinancialDate->format('F');
                        $this->paymentFinancialMonth = $paymentFinancialMonth;
                    } else {
                        Log::error("LandLeasePayment: Financial year not defined, provided year: ". $financialYear);
                        $this->customAlert('warning', __('Something went wrong, please contact ZRA support for more assist!'));
                    }
                    
                }
                
            }
            $this->checkReviewPeriod();
    }

    function checkReviewPeriod(){
        $reviewYear = clone Carbon::parse($this->landLease['commence_date'])->addYear($this->landLease['review_schedule']);
        
        $duration = Carbon::parse($this->paymentFinancialMonth->due_date)->diffInMonths($reviewYear);
        $monthsBeforeReview = DateConfiguration::where('code', 'MonthsBeforeReview')->value('value');

        if ($duration > ($monthsBeforeReview ?? 12)) {
            $this->eligibleToPayment = true;
        } else {
            $duration = Carbon::now()->diffInYears($reviewYear);
            if ($duration <= 1) {
                $this->eligibleToPayment = true;
                TODO: //Check review period for the next three years to allow payments, this will be implemented after a review Module implemenetation
            }
        }
    }

    public function submit(){
        
        if ($this->eligibleToPayment) {
            DB::beginTransaction();
            $response = $this->createLeasePayment($this->landLease, $this->paymentFinancialMonth);
            if($response) {
                DB::commit();
                $this->customAlert('success', 'Land Lease Payment for '. $this->displayMonth .' - '. $this->displayYear .' year successfully');

                if (Auth::user()->id == $this->landLease['taxpayer_id']) {
                    return redirect()->route("land-lease.taxpayer.view", ['id' => encrypt($this->landLease['id'])]);
                } else {
                    return redirect()->route("land-lease.view", ['id' => encrypt($this->landLease['id'])]);
                }
                
            } else {
                DB::rollBack();
                $this->customAlert('error', __('Failed to create lease payment'));
            }
        } else {
            $this->customAlert('warning', 'You can not create lease payment for '. $this->displayYear .'. Please wait till your contract review', ['timer' => 10000]);
        }


    }

    public function render()
    {
            return view('livewire.land-lease.create-lease-payment');
    }
}
