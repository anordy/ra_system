<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use App\Models\TaxAgent;
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
        $whagent = WithholdingAgent::find(decrypt($id));

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
        $locationId = decrypt($locationId);
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
        $id       = decrypt($id);
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
        $bill = ZmBill::with('user')->find(decrypt($id));
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
        $bill = ZmBill::find(decrypt($billId));
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
}
