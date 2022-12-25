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

    
}
