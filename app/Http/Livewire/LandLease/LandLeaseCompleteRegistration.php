<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\GeneralConstant;
use App\Enum\LeaseStatus;
use App\Enum\TransactionType;
use App\Events\SendSms;
use App\Listeners\SendMailFired;
use App\Models\BusinessLocation;
use App\Models\District;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\LandLeaseFiles;
use App\Models\Region;
use App\Models\TaxType;
use App\Models\Ward;
use App\Traits\TaxpayerLedgerTrait;
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

class LandLeaseCompleteRegistration extends Component
{
    use CustomAlert, WithFileUploads, CheckReturnConfigurationTrait, TaxpayerLedgerTrait;

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
    public $area;
    public $usedFor;

    public $customPeriod;
    public $landLease;
    public $previousLeaseAgreementPath;

    public function mount($enc_id)
    {
        $this->landLease = LandLease::find(decrypt($enc_id));
        $this->previousLeaseAgreementPath = $this->getLeaseFiles();
        $this->applicantType = 'registered';
        $this->reviewSchedule = 3;
        $this->validPeriodTerm = 33;
        $this->isBusiness = 1;

        $this->regions = Region::select('id', 'name')->get();
        $this->districts = [];
        $this->wards = [];

        $this->showTaxpayerDetails = false;
        $this->showBusinessDetails = false;
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-complete-registration');
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
            'paymentAmount' => 'required|thousand_separator',
            'region' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'area' => 'required|thousand_separator',
            'usedFor' => 'required|strip_tag',
            'customPeriod' => 'nullable|numeric|min:33|max:99',
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
        $this->previousLeaseAgreementPath = $this->getLeaseFiles();
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
            $taxPayer = DB::table('taxpayers')->where('reference_no', $this->zrbNumber)->first();
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

    /**
     * @throws \Exception
     */
    public function createLeasePayment($landLease)
    {
        $commence_date = Carbon::parse($landLease->rent_commence_date);
        $currentYear = Carbon::now()->year;
        $commenceYear = $commence_date->year;

        // Loop through each year from the commence year to the current year
        for ($year = $commenceYear; $year <= $currentYear; $year++) {

            $financialYear = FinancialYear::where('code', $year)->firstOrFail('id');

            // used with Approach 1
            if ($financialYear) {
                $paymentFinancialMonth = FinancialMonth::select('id', 'name', 'due_date')
                    ->where('financial_year_id', $financialYear->id)
                    ->where('name', $commence_date->monthName)
                    ->firstOrFail();
            } else {
                Log::error("Create Land Lease Payment: {$year} PENALTY RATES DOES NOT EXIST");
                $this->customAlert('warning', __('Some data are missing, please contact ZRA support for more assistance!'));
                continue; // Skip to the next year
            }

            // from commence date, end of month.
            $originalDueDate = Carbon::parse($landLease->rent_commence_date)->setYear($year)->endOfMonth();

            if ($originalDueDate->greaterThan(Carbon::now())){
                return;
            }

            $penaltiesIterations = ceil(Carbon::now()->floatDiffInMonths($originalDueDate));

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

            if ($penaltiesIterations > 0) {
                $leasePenaltiesResponse = $this->calculateLeasePenalties($leasePayment, $paymentFinancialMonth,
                    $penaltiesIterations, $currentYear);

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
            $this->recordTransactionLedger($leasePayment);
        }
    }

    /*
     * Penalty on each month should be 10% of only principal and not compounded but accumulated
     * Penalty to be calculated for active lease only
     */
    private function calculateLeasePenalties($leasePayment, $paymentFinancialMonth, $penaltyIteration, $currentYear)
    {
        $currentFinancialYear = FinancialYear::select('id')
            ->where('code', $currentYear)
            ->first();

        if ($currentFinancialYear) {
            $penaltyRate = PenaltyRate::where('financial_year_id', $currentFinancialYear->id)
                ->where('code', 'LeasePenaltyRate')
                ->first();

            if (!$penaltyRate) {
                $this->customAlert(GeneralConstant::ERROR, "Land lease penalty rate for year $currentYear is not configured.");
                return;
            }

            $penaltyRate = $penaltyRate->rate;

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
            DB::beginTransaction();
            $area = str_replace(',', '', $this->area);
            $paymentAmount = str_replace(',', '', $this->paymentAmount);

            if ($this->isBusiness == '1') {
                $businessLocation = BusinessLocation::where('zin', $this->businessZin)->first();

                $this->landLease->is_registered = true;
                $this->landLease->taxpayer_id = $businessLocation->taxpayer->id;
                $this->landLease->business_location_id = $businessLocation->id;
                $this->landLease->area = $area;
                $this->landLease->lease_for = $this->usedFor;
                $this->landLease->commence_date = $this->commenceDate;
                $this->landLease->rent_commence_date = $this->rentCommenceDate;
                $this->landLease->dp_number = $this->dpNumber;
                $this->landLease->payment_month = $this->paymentMonth;
                $this->landLease->review_schedule = $this->reviewSchedule;
                $this->landLease->valid_period_term = ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm;
                $this->landLease->payment_amount = $paymentAmount;
                $this->landLease->region_id = $this->region;
                $this->landLease->district_id = $this->district;
                $this->landLease->ward_id = $this->ward;
                $this->landLease->name = $this->name;
                $this->landLease->email = $this->email;
                $this->landLease->phone = $this->phoneNumber;
                $this->landLease->address = $this->address;
                $this->landLease->category = $this->applicantCategory;
                $this->landLease->lease_status = 1;
                $this->landLease->completed_at = now();
                $this->landLease->completed_by = Auth::user()->id;
                $this->landLease->save();

                $this->landLease->refresh();
                $this->createLeasePayment($this->landLease);
            } else {
                if ($this->applicantType == 'registered') {
                    $taxpayer = DB::table('taxpayers')->where('reference_no', $this->zrbNumber)->first();

                    $this->landLease->is_registered = true;
                    $this->landLease->taxpayer_id = $taxpayer->id;
                    $this->landLease->area = $area;
                    $this->landLease->lease_for = $this->usedFor;
                    $this->landLease->commence_date = $this->commenceDate;
                    $this->landLease->rent_commence_date = $this->rentCommenceDate;
                    $this->landLease->dp_number = $this->dpNumber;
                    $this->landLease->payment_month = $this->paymentMonth;
                    $this->landLease->review_schedule = $this->reviewSchedule;
                    $this->landLease->valid_period_term = ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm;
                    $this->landLease->payment_amount = $paymentAmount;
                    $this->landLease->region_id = $this->region;
                    $this->landLease->district_id = $this->district;
                    $this->landLease->ward_id = $this->ward;
                    $this->landLease->category = $this->applicantCategory;
                    $this->landLease->lease_status = 1;
                    $this->landLease->completed_at = now();
                    $this->landLease->completed_by = Auth::user()->id;
                    $this->landLease->save();

                    $this->landLease->refresh();
                    $this->createLeasePayment($this->landLease);
                } else {
                    $this->landLease->is_registered = false;
                    $this->landLease->commence_date = $this->commenceDate;
                    $this->landLease->area = $area;
                    $this->landLease->lease_for = $this->usedFor;
                    $this->landLease->rent_commence_date = $this->rentCommenceDate;
                    $this->landLease->dp_number = $this->dpNumber;
                    $this->landLease->payment_month = $this->paymentMonth;
                    $this->landLease->review_schedule = $this->reviewSchedule;
                    $this->landLease->valid_period_term = ($this->validPeriodTerm == 'other') ? $this->customPeriod : $this->validPeriodTerm;
                    $this->landLease->payment_amount = $paymentAmount;
                    $this->landLease->region_id = $this->region;
                    $this->landLease->district_id = $this->district;
                    $this->landLease->ward_id = $this->ward;
                    $this->landLease->name = $this->name;
                    $this->landLease->email = $this->email;
                    $this->landLease->phone = $this->phoneNumber;
                    $this->landLease->address = $this->address;
                    $this->landLease->category = $this->applicantCategory;
                    $this->landLease->lease_status = 1;
                    $this->landLease->completed_at = now();
                    $this->landLease->completed_by = Auth::user()->id;
                    $this->landLease->save();

                    $this->landLease->refresh();

                    event(new SendSms('landlease-unregister-taxpayer', $this->landLease));
                    event(new SendMailFired());
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

    public function getLeaseFiles()
    {
        return LandLeaseFiles::select('file_path', 'name')->where('land_lease_id', $this->landLease->id)->get();
    }

    public function getTaxtype()
    {
        return TaxType::select('id')->where('code', 'land-lease')->first()->id;
    }

    public function recordTransactionLedger($leasePayment)
    {
        $penalty = $leasePayment->totalPenalties();
        $totalAmount = $leasePayment->total_amount + $penalty;

        //record to taxpayer ledger
        $this->recordLedger(TransactionType::DEBIT, get_class($leasePayment), $leasePayment->id,
            $leasePayment->total_amount, 0, $penalty, $totalAmount, $this->getTaxtype
            (), "USD", $leasePayment->taxpayer_id, null, null);
    }
}
