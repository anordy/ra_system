<?php

namespace App\Http\Livewire\LandLease;

use App\Models\BusinessLocation;
use App\Models\District;
use App\Models\LandLease;
use App\Models\LandLeaseHistory;
use App\Models\Region;
use App\Models\Role;
use App\Models\TaxPayer;
use App\Models\User;
use App\Models\Ward;
use App\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class LandLeaseEdit extends Component
{
    use CustomAlert, WithFileUploads;

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
    public $dpNumber;
    public $paymentMonth;
    public $reviewSchedule;
    public $validPeriodTerm;
    public $customPeriod;
    public $paymentAmount;
    public $region;
    public $district;
    public $ward;

    public $showBusinessDetails;
    public $businessName;
    public $showTaxpayerDetails;
    public $taxpayerName;

    public $leaseAgreement;
    public $previousLeaseAgreementPath;
    public $showEditDocument;

    public $landLease;

    public function mount($enc_id)
    {
        //get regions, districts, wards
        $this->regions = Region::select('id', 'name')->get();
        $this->districts = District::select('id', 'name')->get();
        $this->wards = Ward::select('id', 'name')->get();

        //get land lease
        $this->landLease = LandLease::find(decrypt($enc_id));

        //initialize values
        if ($this->landLease->category == 'business') {
            $this->isBusiness = "1";
            $this->businessZin = $this->landLease->businessLocation->zin;
        } else {
            $this->isBusiness = "0";
            if ($this->landLease->is_registered) {
                $this->zrbNumber = $this->landLease->taxpayer->reference_no;
            } else {
                $this->name = $this->landLease->name;
                $this->email = $this->landLease->email;
                $this->phoneNumber = $this->landLease->phone;
                $this->address = $this->landLease->address;
                // $this->applicantCategory = $this->landLease->category;
            }
        }
        $this->applicantType = $this->landLease->is_registered ? 'registered' : 'unregistered';
        $this->commenceDate = $this->landLease->commence_date;
        $this->dpNumber = $this->landLease->dp_number;
        $this->paymentMonth = $this->landLease->payment_month;
        $this->reviewSchedule = $this->landLease->review_schedule;
        $this->validPeriodTerm = $this->landLease->valid_period_term;
        $this->paymentAmount = $this->landLease->payment_amount;
        $this->region = $this->landLease->region_id;
        $this->district = $this->landLease->district_id;
        $this->ward = $this->landLease->ward_id;
        $this->previousLeaseAgreementPath = $this->landLease->lease_agreement_path;

        $this->showEditDocument = false;
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-edit');
    }

    public function rules()
    {
        return [
            'businessZin' => $this->isBusiness == "1" ? 'exists:business_locations,zin|required|strip_tag' : ' ',
            'name' => $this->isBusiness == "0" && $this->applicantType == 'unregistered' ? 'required|strip_tag' : ' ',
            'email' => $this->isBusiness == "0" && $this->applicantType == 'unregistered' ? 'email|required' : ' ',
            'zrbNumber' => $this->isBusiness == "0" && $this->applicantType == 'registered' ? 'exists:taxpayers,reference_no|required|strip_tag' : ' ',
            'phoneNumber' => $this->isBusiness == "0" && $this->applicantType == 'unregistered' ? 'required|numeric' : ' ',
            'address' => $this->isBusiness == "0" && $this->applicantType == 'unregistered' ? 'required|strip_tag' : ' ',
            // 'applicantCategory' => 'required',
            'commenceDate' => 'required|strip_tag',
            'dpNumber' => 'required|strip_tag|unique:land_leases,dp_number,' . $this->landLease->id,
            'paymentMonth' => 'required|strip_tag',
            'reviewSchedule' => 'required|strip_tag',
            'validPeriodTerm' => 'required|strip_tag',
            'paymentAmount' => 'required|numeric',
            'region' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'leaseAgreement' => 'nullable|mimes:pdf|max:1024|max_file_name_length:100',
            'customPeriod' => 'nullable|numeric|min:33',
        ];
    }

    protected $messages = [
        'zrbNumber.exists' => 'The ZRA reference Number is invalid',
        'businessZin.exists' => 'The Business Number is invalid',
        'dpNumber.unique' => 'This DP number already exists',
    ];

    public function submit()
    {
        if (!Gate::allows('lease-edit')) {
            $this->customAlert('error', __('You have no Permission to Register Land Lease'));
            return ;
        }

        $this->validate();
        try {
            $leaseAgreementPath = $this->leaseAgreement != null ? $this->leaseAgreement->store('/lease_agreement_documents', 'local-admin') : $this->previousLeaseAgreementPath;
            DB::beginTransaction();

            //create record in land lease history
            $landLeaseHistory = LandLeaseHistory::create([
                'land_lease_id' => $this->landLease->id,
                'is_registered' => $this->landLease->is_registered,
                'taxpayer_id' => $this->landLease->taxpayer_id,
                'business_location_id' => $this->landLease->business_location_id,
                'dp_number' => $this->landLease->dp_number,
                'commence_date' => $this->landLease->commence_date,
                'payment_month' => $this->landLease->payment_month,
                'payment_amount' => $this->landLease->payment_amount,
                'review_schedule' => $this->landLease->review_schedule,
                'valid_period_term' => $this->validPeriodTerm == 'other' ? $this->customPeriod : $this->validPeriodTerm,
                'region_id' => $this->landLease->region_id,
                'district_id' => $this->landLease->district_id,
                'ward_id' => $this->landLease->ward_id,
                'created_by' => $this->landLease->created_by,
                'edited_by' => $this->landLease->edited_by,
                'category' => $this->landLease->category,
                'name' => $this->landLease->name,
                'email' => $this->landLease->email,
                'phone' => $this->landLease->phone,
                'address' => $this->landLease->address,
                'lease_agreement_path' => $leaseAgreementPath,
                'status' => $this->landLease->status,
            ]);

            if ($this->applicantCategory == 'business') {
                $businessLocation = BusinessLocation::where('zin', $this->businessZin)->first();
                $isUpdated = LandLease::where('id', $this->landLease->id)->update([
                    'business_location_id' => $businessLocation->id,
                    'commence_date' => $this->commenceDate,
                    'dp_number' => $this->dpNumber,
                    'payment_month' => $this->paymentMonth,
                    'review_schedule' => $this->reviewSchedule,
                    'valid_period_term' => $this->validPeriodTerm == 'other' ? $this->customPeriod : $this->validPeriodTerm,
                    'payment_amount' => $this->paymentAmount,
                    'region_id' => $this->region,
                    'district_id' => $this->district,
                    'ward_id' => $this->ward,
                    'lease_agreement_path' => $leaseAgreementPath,
                    'edited_by' => Auth::user()->id
                ]);
            } else {
                if ($this->applicantType == 'registered') {
                    $taxpayer = TaxPayer::where('reference_no', $this->zrbNumber)->first();
                    $isUpdated = LandLease::where('id', $this->landLease->id)->update([
                        'taxpayer_id' => $taxpayer->id,
                        'commence_date' => $this->commenceDate,
                        'dp_number' => $this->dpNumber,
                        'payment_month' => $this->paymentMonth,
                        'review_schedule' => $this->reviewSchedule,
                        'valid_period_term' => $this->validPeriodTerm == 'other' ? $this->customPeriod : $this->validPeriodTerm,
                        'payment_amount' => $this->paymentAmount,
                        'region_id' => $this->region,
                        'district_id' => $this->district,
                        'ward_id' => $this->ward,
                        'lease_agreement_path' => $leaseAgreementPath,
                        'edited_by' => Auth::user()->id,
                    ]);
                } else {
                    $isUpdated = LandLease::where('id', $this->landLease->id)->update([
                        'commence_date' => $this->commenceDate,
                        'dp_number' => $this->dpNumber,
                        'payment_month' => $this->paymentMonth,
                        'review_schedule' => $this->reviewSchedule,
                        'valid_period_term' => $this->validPeriodTerm == 'other' ? $this->customPeriod : $this->validPeriodTerm,
                        'payment_amount' => $this->paymentAmount,
                        'region_id' => $this->region,
                        'district_id' => $this->district,
                        'ward_id' => $this->ward,
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phoneNumber,
                        'address' => $this->address,
                        // 'category' => $this->applicantCategory,
                        'lease_agreement_path' => $leaseAgreementPath,
                        'edited_by' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();

            //create notification on sidebar
            $this->createNotification($this->dpNumber);

            //redirect to route "land-lease.index"
            $this->flash('success', 'Edited successfully');
            return redirect()->route("land-lease.list");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->flash('error', 'Somethingwent wrong');
        }
    }

    public function removeChangedLeaseDocument()
    {
        $this->showEditDocument = false;
        $this->leaseAgreement = null;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region') {
            $this->districts = District::where('region_id', $this->region)->select('id', 'name')->get();
            $this->wards = [];
        }

        if ($propertyName === 'district') {
            $this->wards = [];
            $this->wards = Ward::where('district_id', $this->region)->select('id', 'name')->get();
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
        if ($propertyName === 'isBusiness') {
        }
    }

    public function createNotification($dpNumber)
    {
        $leaseOfficers = User::whereHas('role', function ($query) {
            $query->where('name', 'Land Lease Officer');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                $subject = 'Land Lease Edit Notification',
                $message = "Lease with DP No $dpNumber been edited by " . auth()->user()->fname . " " . auth()
                        ->user()->lname,
                $href = 'land-lease.list',
            ));
        }
    }
}

