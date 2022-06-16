<?php

namespace App\Http\Controllers\TaxPayers;

use App\Http\Controllers\Controller;
use App\Models\KYC;
use App\Traits\Taxpayer\KYCTrait;

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

        $response = $this->updateUser($kyc);

        if (!$response){
            session()->flash('error', 'Could not start the enrollment process, Please try again later.');
            return redirect()->route('taxpayers.registrations.show', $kycId);
        }

        session()->flash('success', 'User details updated, proceed with biometric enrollment');

        return view('taxpayers.registrations.enroll-fingerprint');
    }
}
