<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Biometric;
use App\Models\IDType;
use App\Models\SystemSetting;
use App\Models\Taxpayer;
use App\Traits\CustomAlert;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class EnrollFingerprint extends Component
{
    use CustomAlert, VerificationTrait;

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
        try {
            // Allow unverified passport and nida number
            if ($this->kyc->zanid_verified_at || empty($this->kyc->passport_verified_at) || empty($this->kyc->nida_verified_at)) {
                $this->userVerified = true;
            } else {
                $this->userVerified = false;
                $this->verifyingUser = true;
            }

            $count = Biometric::where('reference_no', $this->kyc->id)
                ->where('template', '!=', null)
                ->count();

            if ($count){
                $this->selectedStep = 'biometric';
            }
        } catch (\Exception $exception){
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator.');
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

        if (!$kyc = $this->kyc){
            $this->customAlert('error', 'Something went wrong, please contact your system administrator for support.');
            return;
        }

        $biometricStatus = SystemSetting::where('code', SystemSetting::BIOMETRIC_STATUS)->first();

        if ($biometricStatus) {
            if ($biometricStatus->value || !$kyc->zanid_no) {
                $biometrics = Biometric::where('reference_no', $kyc->id)
                    ->get();

                if (count($biometrics) != 4) {
                    $this->customAlert('error', 'Enroll four fingers');
                    return;
                }
            }
        } else {
            $this->customAlert('error', 'Please ensure biometric status has been configured in the system');
            return;
        }

        if ($this->kyc->tin_no && !$this->kyc->tin_verified_at){
            $this->customAlert('error', 'TIN No. Not verified by Authorities');
            return;
        }

        if ($this->kyc->zanid_no && !$this->kyc->zanid_verified_at){
            $this->customAlert('error', 'ZANID Not verified by Authorities');
            return;
        }

        $checkMobile = Taxpayer::query()
            ->where('mobile', $kyc->mobile)
            ->whereNotNull('biometric_verified_at')
            ->exists();

        if ($checkMobile) {
            $this->customAlert("error", "User mobile no. already exists in a verified account.");
            return;
        }

        DB::beginTransaction();

        try {
            $kyc->biometric_verified_at = Carbon::now()->toDateTimeString();
            $kyc->verified_by = Auth::id();
            $kyc->save();
            $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by', 'comments'])->toArray();

            $password = Str::random(8);
            $data['password'] = Hash::make($password);
            $data['zanid_verified_at'] = $data['zanid_verified_at'] ? Carbon::make($data['zanid_verified_at'])->toDateTimeString() : null;

            $existingTaxpayer = Taxpayer::query()
                ->where('mobile', $data['mobile'])
                ->orWhere('email', $data['email'] ?? '')
                ->first();

            if ($existingTaxpayer){
                $existingTaxpayer->biometric_verified_at = $data['biometric_verified_at'];
                if (!$existingTaxpayer->save()){
                    session()->flash("error", "Couldn't verify user.");
                    throw new \Exception("Couldn't verify user");
                }
                $kyc->forceDelete();
                DB::commit();

                // Update Biometrics
                Biometric::query()
                    ->where('reference_no', $kyc->id)
                    ->update([
                        'taxpayer_id' => $existingTaxpayer->id
                    ]);

                $this->sign($existingTaxpayer);
            } else {
                $taxpayer = Taxpayer::create([
                    'id_type' => $data['id_type'],
                    'nida_no' => $data['nida_no'],
                    'zanid_no' => $data['zanid_no'],
                    'tin_no' => $data['tin_no'],
                    'passport_no' => $data['passport_no'],
                    'permit_number' => $data['permit_number'],
                    'nida_verified_at' => $data['nida_verified_at'],
                    'zanid_verified_at' => $data['zanid_verified_at'],
                    'tin_verified_at' => $data['tin_verified_at'],
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


                Biometric::query()
                    ->where('reference_no', $kyc->id)
                    ->update([
                        'taxpayer_id' => $taxpayer->id
                    ]);

                // todo: this should before sending the email/Sms
                if ($taxpayer) {
                    $kyc->forceDelete();
                } else {
                    session()->flash("error", "Couldn't verify user.");
                    throw new \Exception("Couldn't verify user");
                }

                DB::commit();

                // sign taxpayer
                $this->sign($taxpayer);

                // Send email and password for OTP
                event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
                if ($taxpayer->email) {
                    event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
                }
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
