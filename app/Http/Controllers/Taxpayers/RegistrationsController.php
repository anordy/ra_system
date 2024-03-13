<?php

namespace App\Http\Controllers\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\Biometric;
use App\Models\KYC;
use App\Models\Taxpayer;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegistrationsController extends Controller
{
    use VerificationTrait;

    public function index()
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }
        return view('taxpayers.registrations.index');
    }

    public function show($kycId)
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }

        try {
            $kyc = KYC::findOrFail(decrypt($kycId));
            return view('taxpayers.registrations.show', compact('kyc'));
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Something went wrong, please contact the administrator for help');
            return redirect()->back();
        }
    }


    public function enrollFingerprint($kycId)
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }

        try {
            $kyc = KYC::with('region:id,name', 'district:id,name', 'ward:id,name', 'street:id,name')->findOrFail(decrypt($kycId));
            return view('taxpayers.registrations.enroll-fingerprint', compact('kyc'));
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Something went wrong, please contact the administrator for help');
            return redirect()->back();
        }
    }

    public function verifyUser($kycId)
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }

        try {
            $kyc = KYC::findOrFail(decrypt($kycId));
            if (config('app.env') != 'local') {
                $biometrics = Biometric::where('reference_no', $kyc->id)
                    ->get();

                if (count($biometrics) != 4) {
                    session()->flash('error', 'Enroll four fingers');
                    return redirect()->back();
                }
            }

            if ($kyc->is_citizen == '1' && isNullOrEmpty($kyc->zanid_verified_at)) {
                session()->flash('error', 'User ZANID not verified by authorities');
                return redirect()->back();
            } else if($kyc->is_citizen == '0' && (isNullOrEmpty($kyc->passport_verified_at))) {
                session()->flash('error', 'User Passport Number not verified by authorities');
                return redirect()->back();
            }

            DB::beginTransaction();

            $kyc->biometric_verified_at = Carbon::now()->toDateTimeString();
            $kyc->verified_by = Auth::id();
            $kyc->save();

            $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by', 'comments'])->toArray();
            $password = Str::random(8);
            $data['password'] = Hash::make($password);

            $taxpayer = Taxpayer::create($data);
            $taxpayer->generateReferenceNo();

            // sign taxpayer
            $this->sign($taxpayer);

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
            session()->flash('error', 'Something went wrong, please contact the administrator for help');
            return redirect()->route('taxpayers.registrations.index');
        }
    }
}
