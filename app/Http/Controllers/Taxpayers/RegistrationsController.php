<?php

namespace App\Http\Controllers\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\KYC;
use App\Models\Taxpayer;
use App\Traits\Taxpayer\KYCTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegistrationsController extends Controller
{
    use KYCTrait;

    public function index(){
        return view('taxpayers.registrations.index');
    }

    public function show($kycId){
        $kyc = KYC::findOrFail($kycId);
        return view('taxpayers.registrations.show', compact('kyc'));
    }


    public function enrollFingerprint($kycId){
        $kyc = KYC::findOrFail($kycId);

        return view('taxpayers.registrations.enroll-fingerprint', compact('kyc'));
    }

    public function verifyUser($kycId){
        $kyc = KYC::findOrFail($kycId);

        if(!$kyc->authorities_verified_at){
            session()->flash('error', 'User not verified by authorities');
            return redirect()->route('taxpayers.registrations.index');
        }

        $kyc->biometric_verified_at = Carbon::now()->toDateTimeString();
        $kyc->save();

        $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at'])->toArray();
        $password = rand(0, 999999);
        $data['password'] = Hash::make($password);

        $taxpayer = Taxpayer::create($data);

        // Send email and password for OTP
        event(new SendSms('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        if ($taxpayer->email){
            event(new SendMail('taxpayer-registration', $taxpayer->id, ['code' => $password]));
        }

        $taxpayer ? $kyc->delete() : session()->flash('error', "Couldn't verify user.");

        return redirect()->route('taxpayers.registrations.index');
    }
}
