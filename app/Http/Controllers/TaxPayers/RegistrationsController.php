<?php

namespace App\Http\Controllers\TaxPayers;

use App\Http\Controllers\Controller;
use App\Models\KYC;
use App\Models\TaxPayer;
use App\Traits\Taxpayer\KYCTrait;
use Carbon\Carbon;
use Illuminate\Hashing\HashManager;
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
        $data['password'] = Hash::make(rand(0, 999999));

        // Send email for OTP

        $taxpayer = TaxPayer::create($data);

        $taxpayer ? $kyc->delete() : session()->flash('error', "Couldnt verify user.");
        return redirect()->route('taxpayers.registrations.index');
    }
}
