<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use App\Models\TaxAgent;
use App\Models\TaxType;
use App\Models\WithholdingAgent;
use App\Models\ZmBill;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QRCodeCheckController extends Controller
{
    public function withholdingAgentCertificate($id)
    {
        $whagent = WithholdingAgent::findOrFail(decrypt($id));

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
        $location   = BusinessLocation::with('business', 'business.taxpayer')->findOrFail($locationId);
        $tax        = TaxType::findOrFail($taxTypeId);

        $code = [
            'ZIN'           => $location->zin,
            'Business Name' => $location->business->name,
            'Tax Type'      => $tax->name,
            'Location'      => "{$location->street}, {$location->district->name}, {$location->region->name}",
        ];
         
        return view('qr-check.index', ['code' => $code]);
    }
    
    
    public function taxAgentsCertificate($id)
    {
        $id       = decrypt($id);
        $taxagent = TaxAgent::with('taxpayer')->findOrFail($id);
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
        $bill = ZmBill::with('user')->findOrFail(decrypt($id));
        $name = $bill->user->full_name ?? '';

        $code = [
            "shortCode" => "001001",
            "bill Reference" => $bill->control_number,
            "amount" => $bill->amount,
            "bill Currency" => $bill->currency,
            "bill Expiry Date" => $bill->expire,
            "bill Payment Option" => "1",
            "billRsv01" => "ZANZIBAR REVENUE AUTHORITY | {$name}"
        ];
        

        return view('qr-check.index', ['code' => $code]);
    }

    public function transfer($billId)
    {
        $bill = ZmBill::findOrFail(decrypt($billId));
        $name = $bill->payer_name;

        $code = [
            "Option Type" => $bill->payment_option,
            "short Code" => "001001",
            "bill Reference" => $bill->control_number,
            "amount" => $bill->amount,
            "bill Currency" => $bill->currency,
            "bill Expiry Date" => $bill->expire,
            "billRsv01" => "ZANZIBAR REVENUE AUTHORITY | {$name}"
        ];
        
        return view('qr-check.index', ['code' => $code]);
    }
}
