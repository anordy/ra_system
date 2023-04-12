<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\SystemSetting;
use App\Models\TaxType;
use App\Models\WithholdingAgent;
use Illuminate\Http\Request;

class QRCodeCheckController extends Controller
{

    public function withholdingAgentCertificate($id)
    {
        $whagent = WithholdingAgent::findOrFail(decrypt($id));
        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        return View('withholding-agent.certificate', compact('whagent', 'signaturePath', 'commissinerFullName'));
    }

    
    public function businessCertificate($locationId,$taxTypeId)
    {
        $locationId = decrypt($locationId);
        $taxTypeId = decrypt($taxTypeId);
        $location = BusinessLocation::with('business', 'business.taxpayer')->findOrFail($locationId);
        $tax = TaxType::findOrFail($taxTypeId);
        $taxType = BusinessTaxType::where('business_id', $location->business->id)->where('tax_type_id', $taxTypeId)->firstOrFail();

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();
        
        return View('business.certificate', compact('location', 'tax', 'taxType', 'certificateNumber', 'signaturePath', 'commissinerFullName'));
    }

    
    public function taxChangeCertificate()
    {
        # code...
        return View('business.tax-change-certificate', compact('location', 'tax', 'dataUri', 'taxType', 'certificateNumber', 'signaturePath', 'commissinerFullName'));
    }

    
    public function taxAgentsCertificate($id)
    {
        $id = decrypt($id);
        $taxagent = TaxAgent::with('taxpayer')->findOrFail($id);
        if ($taxagent->is_first_application == 1) {
            $start_date = $taxagent->app_first_date;
            $end_date = $taxagent->app_expire_date;
            $start = date('d', strtotime($start_date));
            $end = date('d', strtotime($end_date));
            $diff = Carbon::create($end_date)->diffInYears($start_date);

        } else {
            $renew = $taxagent->request->firstOrFail();
            $start_date = $renew->renew_first_date;
            $end_date = $renew->renew_expire_date;
            $start = date('d', strtotime($start_date));
            $end = date('d', strtotime($end_date));
            $diff = Carbon::create($end_date)->diffInYears($start_date);
        }

        $word = $diff > 1 ? 'years' : 'year';

        $superStart = $this->sup($start);
        $superEnd = $this->sup($end);

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();
    }

    
}
