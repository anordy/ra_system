<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Listeners\SendMailFired;
use App\Models\BusinessLocation;
use App\Models\District;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Region;
use App\Models\TaxPayer;
use App\Models\Ward;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

use App\Models\LandLease;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\LeasePaymentPenalty;
use App\Models\PenaltyRate;
use App\Traits\CheckReturnConfigurationTrait;
use Carbon\Carbon;

class RegisterLandLease extends Component
{
    use CustomAlert, WithFileUploads, CheckReturnConfigurationTrait;

    public $isBusiness;
    public $businessZin;
    public $applicantType;
    public $zrbNumber;
    public $name;
    public $email;
    public $phoneNumber;
    public $address;
    public $applicantCategory;

    public $commenceDate;
    public $rentCommenceDate;
    public $dpNumber;
    public $paymentMonth;
    public $reviewSchedule;
    public $validPeriodTerm;
    public $paymentAmount;
    public $region;
    public $district;
    public $ward;

    public $showBusinessDetails;
    public $businessName;
    public $showTaxpayerDetails;
    public $taxpayerName;

    public $leaseAgreement;
    public $customPeriod;

    public function mount()
    {
        $this->applicantType = 'registered';
        $this->reviewSchedule = 3;
        $this->validPeriodTerm = 33;
        $this->isBusiness = 1;

        $this->regions = Region::select('id', 'name')->get();
        // $this->districts = District::select('id', 'name')->get();
        // $this->wards = Ward::select('id', 'name')->get();
        $this->districts = [];
        $this->wards = [];

        $this->showTaxpayerDetails = false;
        $this->showBusinessDetails = false;
    }

    public function render()
    {
        return view('livewire.land-lease.register-land-lease');
    }

    public function rules()
    {
        return [
            'businessZin' => $this->isBusiness == 1 ? 'exists:business_locations,zin|required|strip_tag' : ' ',
            'name' => $this->isBusiness == 0 && $this->applicantType == 'unregistered' ? 'required|strip_tag' : ' ',
            'email' => $this->isBusiness == 0 && $this->applicantType == 'unregistered' ? 'email|required' : ' ',
            'zrbNumber' => $this->isBusiness == 0 && $this->applicantType == 'registered' ? 'exists:taxpayers,reference_no|required|strip_tag' : ' ',
            'phoneNumber' => $this->isBusiness == 0 && $this->applicantType == 'unregistered' ? 'required|numeric' : ' ',
            'address' => $this->isBusiness == 0 && $this->applicantType == 'unregistered' ? 'required|strip_tag' : ' ',
            'applicantCategory' => 'required',
            'commenceDate' => 'required|strip_tag',
            'dpNumber' => 'unique:land_leases,dp_number|required',
            'paymentMonth' => 'required|strip_tag',
            'reviewSchedule' => 'required|strip_tag',
            'validPeriodTerm' => 'required|strip_tag',
            'paymentAmount' => 'required|numeric',
            'region' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'leaseAgreement' => 'required|mimes:pdf|max:1024|max_file_name_length:100',
            'customPeriod' => 'nullable|numeric|min:33',
            'rentCommenceDate' => 'required|strip_tag',
        ];
    }

    protected $messages = [
        'zrbNumber.exists' => 'The ZRA reference Number is invalid',
        'businessZin.exists' => 'The Business Number is invalid',
        'dpNumber.unique' => 'This DP number already exists',
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'region') {
            $this->districts = District::where('region_id', intval($this->region))
                ->select('id', 'name')
                ->get();
            $this->wards = [];
        }

        if ($propertyName === 'district') {
            $this->wards = [];
            $this->wards = Ward::where('district_id', intval($this->district))
                ->select('id', 'name')
                ->get();
        }

        if ($propertyName === 'zrbNumber') {
            $taxPayer = TaxPayer::where('reference_no', $this->zrbNumber)->first();
            if ($taxPayer) {
                $this->taxpayerName = $taxPayer->first_name . ' ' . $taxPayer->last_name;
                $this->showTaxpayerDetails = true;
            } else {
                $this->showTaxpayerDetails = false;
                $this->taxpayerName = '';
            }
        }

        if ($propertyName === 'businessZin') {
            $businessLocation = BusinessLocation::where('zin', $this->businessZin)->first();
            if ($businessLocation) {
                $this->businessName = $businessLocation->business->name . ' | ' . $businessLocation->name;
                $this->showBusinessDetails = true;
            } else {
                $this->businessName = '';
                $this->showBusinessDetails = false;
            }
        }
    }

    public function createLeasePayment($landLease)
    {
        $commence_date = Carbon::parse($landLease->rent_commence_date);
        $currentYear = $commence_date->year;
        $commenceMonthNumber = $commence_date->month;
        $paymentMonthNumber = Carbon::parse('1 ' . $landLease->payment_month)->month;

        if ($commenceMonthNumber > $paymentMonthNumber) {
            $date = clone $commence_date->addYear();
            $currentYear = $date->year;
        }

        $financialYear = FinancialYear::where('code', $currentYear)->firstOrFail('id');

        if ($financialYear) {
            $paymentFinancialMonth = FinancialMonth::select('id', 'name', 'due_date')
                ->where('financial_year_id', $financialYear->id)
                ->where('name', $landLease->payment_month)
                ->firstOrFail();
        } else {
            Log::error("Create Land Lease Payment: {$currentYear} PENALTY RATES DOES NOT EXIST");
            $this->customAlert('warning', __('Some data are missing, please contact ZRA support for more assistance!'));
        }

        $due_date = Carbon::parse($paymentFinancialMonth->due_date)->endOfMonth();

        $leasePayment = LeasePayment::create([
            'land_lease_id' => $landLease->id,
            'taxpayer_id' => $landLease->taxpayer_id,
            'financial_month_id' => $paymentFinancialMonth->id,
            'financial_year_id' => $financialYear->id,
            'total_amount' => $landLease->payment_amount,
            'total_amount_with_penalties' => $landLease->payment_amount,
            'outstanding_amount' => $landLease->payment_amount,
            'status' => LeaseStatus::PENDING,
            'due_date' => $due_date,
        ]);

        $penaltyIteration = Carbon::now()->month - Carbon::parse($paymentFinancialMonth->due_date)->month;

        if ($due_date > Carbon::now()) {
            $penaltyIteration = 0;
        }

        if ($penaltyIteration > 0) {
            $leasePenaltiesResponse = $this->calculateLeasePenalties($leasePayment, $paymentFinancialMonth, $penaltyIteration);

            if ($leasePenaltiesResponse[0]) {
                $updateLeasePayment = LeasePayment::find($leasePayment->id);
                $updateLeasePayment->penalty = $leasePayment->totalPenalties();
                $updateLeasePayment->total_amount_with_penalties = $leasePenaltiesResponse[1];
                $updateLeasePayment->outstanding_amount = $leasePenaltiesResponse[1];
                $updateLeasePayment->status = LeaseStatus::DEBT;
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
            } else {
                $this->customAlert('warning', __('Some data are missing, please contact ZRA support for more assistance!'));
            }

        }
    }

    /*
     * Penalty on each month should be 10% of only principal and not compounded but accumulated
     */
    private function calculateLeasePenalties($leasePayment, $paymentFinancialMonth, $penaltyIteration)
    {
        $currentYear = Carbon::now()->year;
        $currentFinancialYear = FinancialYear::select('id')
            ->where('code', $currentYear)
            ->first();

        if ($currentFinancialYear) {
            $penaltyRate = PenaltyRate::where('financial_year_id', $currentFinancialYear->id)
                ->where('code', 'LeasePenaltyRate')
                ->firstOrFail()->rate;

            $wholeTotalAmount = $leasePayment->total_amount;
            $penaltyAmountAccumulated = 0;

            for ($i = 1; $i <= $penaltyIteration; $i++) {
                $penaltyAmount = round($leasePayment->total_amount * $penaltyRate, 2);
                $penaltyAmountAccumulated += $penaltyAmount;
                $totalAmount = round($leasePayment->total_amount + $penaltyAmountAccumulated, 2);

                LeasePaymentPenalty::create([
                    'lease_payment_id' => $leasePayment->id,
                    'tax_amount' => $leasePayment->total_amount,
                    'rate_percentage' => $penaltyRate,
                    'penalty_amount' => $penaltyAmount,
                    'total_amount' => $totalAmount,
                    'start_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonth->due_date, $i)[0],
                    'end_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonth->due_date, $i)[1],
                ]);
            }

            return [true, $leasePayment->total_amount + $penaltyAmountAccumulated];
        } else {
            Log::error("Create Land Lease Payment: {$currentYear} PENALTY RATES DOES NOT EXIST");
            return [false, null];
        }
    }

    public function getFirstLastDateOfMonth($due_date, $i)
    {
        $currentMonth = $due_date->addMonths($i);
        $start_date = clone $currentMonth->startOfMonth();
        $end_date = clone $currentMonth->endOfMonth();
        return [$start_date, $end_date];
    }

    public function submit()
    {
        // if (auth()->user()->isLandLeaseAgent() == null) {
        //     $this->customAlert('error', __('You have no Permission to Register Land Lease'));
        //     return;
        // }

        if ($this->applicantType == 'registered') {
            $this->name = '';
            $this->email = '';
            $this->phoneNumber = '';
            $this->address = '';
        } else {
            $this->zrbNumber = '';
        }

        if ($this->isBusiness) {
            $this->applicantCategory = 'business';
        } else {
            $this->applicantCategory = 'sole owner';
        }

        $this->validate();

        try {
            $leaseAgreementPath = $this->leaseAgreement->store('/lease_agreement_documents', 'local-admin');
            DB::beginTransaction();

            if ($this->isBusiness == '1') {
                $businessLocation = BusinessLocation::where('zin', $this->businessZin)->first();
                $landLease = LandLease::create([
                    'is_registered' => true,
                    'taxpayer_id' => $businessLocation->taxpayer->id,
                    'business_location_id' => $businessLocation->id,
                    'commence_date' => $this->commenceDate,
                    'rent_commence_date' => $this->rentCommenceDate,
                    'dp_number' => $this->dpNumber,
                    'payment_month' => $this->paymentMonth,
                    'review_schedule' => $this->reviewSchedule,
                    'valid_period_term' => ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm,
                    'payment_amount' => $this->paymentAmount,
                    'region_id' => $this->region,
                    'district_id' => $this->district,
                    'ward_id' => $this->ward,
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phoneNumber,
                    'address' => $this->address,
                    'category' => $this->applicantCategory,
                    'lease_agreement_path' => $leaseAgreementPath,
                    'created_by' => Auth::user()->id,
                ]);

                $this->createLeasePayment($landLease);
            } else {
                if ($this->applicantType == 'registered') {
                    $taxpayer = TaxPayer::where('reference_no', $this->zrbNumber)->first();
                    $landLease = LandLease::create([
                        'is_registered' => true,
                        'taxpayer_id' => $taxpayer->id,
                        'commence_date' => $this->commenceDate,
                        'rent_commence_date' => $this->rentCommenceDate,
                        'dp_number' => $this->dpNumber,
                        'payment_month' => $this->paymentMonth,
                        'review_schedule' => $this->reviewSchedule,
                        'valid_period_term' => ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm,
                        'payment_amount' => $this->paymentAmount,
                        'region_id' => $this->region,
                        'district_id' => $this->district,
                        'ward_id' => $this->ward,
                        'category' => $this->applicantCategory,
                        'lease_agreement_path' => $leaseAgreementPath,
                        'created_by' => Auth::user()->id,
                    ]);

                    $this->createLeasePayment($landLease);
                } else {
                    $landLease = LandLease::create([
                        'is_registered' => false,
                        'commence_date' => $this->commenceDate,
                        'rent_commence_date' => $this->rentCommenceDate,
                        'dp_number' => $this->dpNumber,
                        'payment_month' => $this->paymentMonth,
                        'review_schedule' => $this->reviewSchedule,
                        'valid_period_term' => ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm,
                        'payment_amount' => $this->paymentAmount,
                        'region_id' => $this->region,
                        'district_id' => $this->district,
                        'ward_id' => $this->ward,
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phoneNumber,
                        'address' => $this->address,
                        'category' => $this->applicantCategory,
                        'lease_agreement_path' => $leaseAgreementPath,
                        'created_by' => Auth::user()->id,
                    ]);

                    event(new SendSms('landlease-unregister-taxpayer', $landLease));
                    event(new SendMailFired('landlease-unregister-taxpayer', $landLease));
                }
            }

            DB::commit();
            $this->customAlert('success', __('Land Lease registered successfully'));
            return redirect()->route('land-lease.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', __('Failed to register lease'));
        }
    }
}
