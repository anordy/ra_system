<?php

namespace App\Http\Controllers\Claims;

use App\Enum\TaxClaimStatus;
use App\Http\Controllers\Controller;
use App\Models\Claims\TaxClaim;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;

class ClaimsController extends Controller
{
    public function index(){
        return view('claims.index');
    }

    public function show($claimId){
        $claimId = decrypt($claimId);
        $claim = TaxClaim::findOrFail($claimId);
        $return = $claim->oldReturn;

        if ($return instanceof StampDutyReturn){
            $returnView = 'returns.stamp-duty.details';
            return view('claims.show', compact('claim', 'returnView'));
        }

        if ($return instanceof VatReturn){
            $returnView = 'returns.vat_returns.details';
            return view('claims.show', compact('claim', 'returnView'));
        }
    }
}
