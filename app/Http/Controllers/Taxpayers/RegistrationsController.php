<?php

namespace App\Http\Controllers\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\Biometric;
use App\Models\KYC;
use App\Models\Taxpayer;
use App\Traits\Taxpayer\KYCTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegistrationsController extends Controller
{
    use KYCTrait;

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
        $kyc = KYC::findOrFail(decrypt($kycId));
        return view('taxpayers.registrations.show', compact('kyc'));
    }


    public function enrollFingerprint($kycId)
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }
        $kyc = KYC::findOrFail(decrypt($kycId));
        return view('taxpayers.registrations.enroll-fingerprint', compact('kyc'));
    }

    public function verifyUser($kycId)
    {
        if (!Gate::allows('kyc_view')) {
            abort(403);
        }
        $kyc = KYC::findOrFail(decrypt($kycId));
        if (config('app.env') != 'local') {
            $biometrics = Biometric::where('reference_no', $kyc->id)
                ->get();

            if (count($biometrics) != 4) {
                session()->flash('error', 'Enroll four fingers');
                return redirect()->back();
            }
        }


        if (!$kyc->authorities_verified_at) {
            session()->flash('error', 'User not verified by authorities');
            return redirect()->back();
        }

        $kyc->biometric_verified_at = Carbon::now()->toDateTimeString();
        $kyc->verified_by = Auth::id();
        $kyc->save();

        try {
            DB::beginTransaction();

            $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by'])->toArray();
            $permitted_chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#%';
            $password = substr(str_shuffle($permitted_chars), 0, 8);
            $data['password'] = Hash::make($password);

            if (config('app.env') == 'local') {
                $data['password'] = Hash::make('password');
            }

            $taxpayer = Taxpayer::create($data);
            $taxpayer->generateReferenceNo();

            // Send email and password for OTP
            event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
            if ($taxpayer->email) {
                event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
            }

            $taxpayer ? $kyc->delete() : session()->flash("error", "Couldn't verify user.");
            DB::commit();

            return redirect()->route('taxpayers.taxpayer.index');
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            session()->flash('error', 'Something went wrong, please try again later.');
            return redirect()->route('taxpayers.registrations.index');
        }

    }
}
