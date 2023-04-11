<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Biometric;
use App\Models\IDType;
use App\Models\KYC;
use App\Models\Taxpayer;
use App\Traits\Taxpayer\KYCTrait;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class EnrollFingerprint extends Component
{
    use KYCTrait, CustomAlert, VerificationTrait;

    public $kyc;
    public $error;

    public $selectedStep = 'details';
    public $userVerified = false;
    public $verifyingUser = false;

    public function changeStep($step)
    {
        $this->selectedStep = $step;
    }

    public function mount()
    {
        // Allow unverified passport and nida number
        if ($this->kyc->zanid_verified_at || empty($this->kyc->passport_verified_at) || empty($this->kyc->nida_verified_at)) {
            $this->userVerified = true;
        } else {
            $this->userVerified = false;
            $this->verifyingUser = true;
        }
    }

    public function enrolled($hand, $finger)
    {
        $check = Biometric::where('hand', $hand)
            ->where('finger', $finger)
            ->where('reference_no', $this->kyc->id)
            ->where('template', '!=', null)
            ->get();
        if ($check->count() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    protected $listeners = [
        'verifyUser'
    ];

    public function confirmPopUpModal($action)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
        ]);
    }

    public function verifyUser()
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }
        $kyc = $this->kyc;

        if (config('app.env') != 'local') {
            $biometrics = Biometric::where('reference_no', $kyc->id)
                ->get();

            if (count($biometrics) != 4) {
                $this->customAlert('error', 'Enroll four fingers');
                return;
            }
        }

        // If id is zanid or nida & zanid check if zan id has been verified
        if ($this->kyc->identification->name == IDType::ZANID || $this->kyc->identification->name == IDType::NIDA_ZANID) {
            if ($kyc->is_citizen == '1' && isNullOrEmpty($kyc->zanid_verified_at)) {
                $this->customAlert('error', 'User ZANID not verified by authorities');
                return;
            }
        } else if ($this->kyc->identification->name == IDType::PASSPORT) {
            // TODO: Allow this when immigration api has been integrated
            // if($kyc->is_citizen == '0' && (isNullOrEmpty($kyc->passport_verified_at))) {
            //     $this->customAlert('error', 'User Passport Number not verified by authorities');
            //     return;
            // }
        } else if ($this->kyc->identification->name == IDType::NIDA) {
            // TODO: Check nida when nida api has been integrated
        } 

        DB::beginTransaction();

        try {

            $kyc->biometric_verified_at = Carbon::now()->toDateTimeString();
            $kyc->verified_by = Auth::id();
            $kyc->save(); // todo: unless the exception tha would occur below will not affect this record, i suggest to put this inside trx
            $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by', 'comments'])->toArray();
            $permitted_chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#%';
            $password = substr(str_shuffle($permitted_chars), 0, 8);
            $data['password'] = Hash::make($password);

            if (config('app.env') == 'local') {
                $data['password'] = Hash::make('password');
            }

            $taxpayer = Taxpayer::create([
                'id_type' => $data['id_type'],
                'nida_no' => $data['nida_no'],
                'zanid_no' => $data['zanid_no'],
                'passport_no' => $data['passport_no'],
                'permit_number' => $data['permit_number'],
                'nida_verified_at' => $data['nida_verified_at'],
                'zanid_verified_at' => $data['zanid_verified_at'],
                'passport_verified_at' => $data['passport_verified_at'],
                'biometric_verified_at' => $data['biometric_verified_at'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'password' => $data['password'],
                'physical_address' => $data['physical_address'],
                'street_id' => $data['street_id'],
                'district_id' => $data['district_id'],
                'ward_id' => $data['ward_id'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'alt_mobile' => $data['alt_mobile'],
                'region_id' => $data['region_id'],
                'is_citizen' => $data['is_citizen'],
                'country_id' => $data['country_id']
            ]);

            $taxpayer->generateReferenceNo();

            // sign taxpayer
            $this->sign($taxpayer);

            // todo: this should before sending the email/Sms
            if ($taxpayer) {
                $kyc->delete();
            } else {
                session()->flash("error", "Couldn't verify user.");
                throw new \Exception("Couldn't verify user");
            }

            DB::commit();

            // Send email and password for OTP
            event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
            if ($taxpayer->email) {
                event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
            }

            return redirect()->route('taxpayers.taxpayer.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, Could you please contact our administrator for assistance');
            return;
        }
    }

    public function render()
    {
        return view('livewire.taxpayers.enroll-vendor-fingerprint');
    }
}
