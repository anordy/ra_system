<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use App\Models\MvrDeregistration;
use App\Models\MvrRegistration;
use App\Models\RoadLicense\RoadLicense;
use App\Models\TaxAgent;
use App\Models\TaxClearanceRequest;
use App\Models\TaxType;
use App\Models\WithholdingAgent;
use App\Models\ZmBill;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class QRCodeCheckController extends Controller
{
    public function withholdingAgentCertificate($id)
    {
        $whagent = WithholdingAgent::find(base64_decode($id));

        if (!$whagent) {
            return view('qr-check.error');
        }

        $code = [
            'Institution Name'  => $whagent->institution_name,
            'Institution Place' => $whagent->institution_place,
            'Agency No.'        => $whagent->wa_number,
        ];
    
        return view('qr-check.index', ['code' => $code]);
    }
    
    public function businessCertificate($locationId, $taxTypeId)
    {
        $locationId = base64_decode($locationId);
        $taxTypeId = base64_decode($taxTypeId);
        $location   = BusinessLocation::with('business', 'business.taxpayer')->find($locationId);
        $tax        = TaxType::find($taxTypeId);
        if (!$location || !$tax) {
            return view('qr-check.error');
        }

        $code = [
            'ZIN'           => $location->zin,
            'Business Name' => $location->business->name,
            'Tax Type'      => $tax->name,
            'Location'      => $location->street->name.', '. $location->district->name.', '. $location->region->name,
        ];
         
        return view('qr-check.index', ['code' => $code]);
    }
    
    
    public function taxAgentsCertificate($id)
    {
        $id       = base64_decode($id);
        $taxagent = TaxAgent::with('taxpayer')->find($id);
        if (!$taxagent) {
            return view('qr-check.error');
        }

        if ($taxagent->is_first_application == 1) {
            $start_date = $taxagent->app_first_date;
            $end_date   = $taxagent->app_expire_date;
            $diff       = Carbon::create($end_date)->diffInYears($start_date);
        } else {
            $renew      = $taxagent->request->firstOrFail();
            $start_date = $renew->renew_first_date;
            $end_date   = $renew->renew_expire_date;
            $diff       = Carbon::create($end_date)->diffInYears($start_date);
        }

        $word = $diff > 1 ? 'years' : 'year';

        $code = [
            'Name'     => $taxagent->taxpayer->fullName,
            'Location' => $taxagent->district->name . ', ' . $taxagent->region->name,
            'Period'   => $diff . ' ' . $word,
            'From'     => $start_date,
            'To'       => $end_date,
        ];

        return view('qr-check.index', ['code' => $code]);
    }

    public function invoice($id)
    {
        $bill = ZmBill::with('user')->find(base64_decode($id));
        $name = $bill->user->full_name ?? '';

        if (!$bill) {
            return view('qr-check.error');
        }

        $code = [
            "shortCode" => "001001",
            "bill Reference" => $bill->control_number,
            "amount" => number_format($bill->amount) .' '.$bill->currency,
            "bill Currency" => $bill->currency,
            "bill Expiry Date" => $bill->expire_date,
            // "billRsv01" => "ZANZIBAR REVENUE AUTHORITY | {$name}"
        ];
        

        return view('qr-check.index', ['code' => $code]);
    }

    public function transfer($billId)
    {
        $bill = ZmBill::find(base64_decode($billId));
        $name = $bill->payer_name;

        if (!$bill) {
            return view('qr-check.error');
        }

        $code = [
            "shortCode" => "001001",
            "bill Reference" => $bill->control_number,
            "amount" => number_format($bill->amount) .' '.$bill->currency,
            "bill Currency" => $bill->currency,
            "bill Expiry Date" => $bill->expire_date,
            // "billRsv01" => "ZANZIBAR REVENUE AUTHORITY | {$name}"
        ];
        
        return view('qr-check.index', ['code' => $code]);
    }

    public function mvrDeregistrationCertificate($id)
    {
        $id = base64_decode($id);

        if (!$id) {
            return view('qr-check.error');
        }

        $deRegistration = MvrDeregistration::findOrFail($id);

        $code = [
            'Chassis Number' => $deRegistration->registration->chassis->chassis_number,
            'Model' => $deRegistration->registration->chassis->model_type ?? 'N/A',
            'Plate Number' => $deRegistration->registration->plate_number,
            'Registration Number' => $deRegistration->registration->registration_number,
            'Date of Deregistration' => Carbon::create($deRegistration->deregistered_at)->format('d M, Y'),
        ];

        return view('qr-check.index', ['code' => $code]);
    }

    public function mvrRegistrationCertificate($id)
    {
        $id = base64_decode($id);

        if (!$id) {
            return view('qr-check.error');
        }

        $registration = MvrRegistration::findOrFail($id);

        $code = [
            'Chassis Number' => $registration->chassis->chassis_number,
            'Model' => $registration->chassis->model_type ?? 'N/A',
            'Plate Number' => $registration->plate_number,
            'Registration Number' => $registration->registration_number,
            'Date of Registration' => Carbon::create($registration->registered_at)->format('d M, Y'),
        ];

        return view('qr-check.index', ['code' => $code]);
    }

    public function roadLicenseSticker($roadLicenseId)
    {
        $id = base64_decode($roadLicenseId);

        if (!$id) {
            return view('qr-check.error');
        }

        $roadLicense = RoadLicense::findOrFail($id);

        $code = [
            'Chassis Number' => $roadLicense->registration->chassis->chassis_number,
            'Registration Type' => 'Road License Sticker',
            'Plate Number' => $roadLicense->registration->plate_number ?? 'N/A',
            'Issued Date' => Carbon::create($roadLicense->issued_date)->format('d M Y'),
            'Expiry Date' => Carbon::create($roadLicense->expire_date)->format('d M Y'),
            'Status' => Carbon::now()->gt($roadLicense->expire_date) ? 'EXPIRED' : 'ACTIVE',
        ];

        return view('qr-check.index', ['code' => $code]);
    }

    public function taxClearanceCertificate($clearanceId)
    {
        $taxClearanceRequestId = base64_decode($clearanceId);
        $taxClearanceRequest = TaxClearanceRequest::findOrFail($taxClearanceRequestId);
        $location = $taxClearanceRequest->businessLocation;
        if (!$location) {
            return view('qr-check.error');
        }

        $code = [
            'ZIN'           => $location->zin,
            'Business Name' => $location->business->name,
            'Location'      => $location->street->name.', '. $location->district->name.', '. $location->region->name,
        ];

        return view('qr-check.index', ['code' => $code]);
    }


}
