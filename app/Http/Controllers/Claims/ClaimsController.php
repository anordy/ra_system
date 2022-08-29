<?php

namespace App\Http\Controllers\Claims;

use App\Enum\TaxClaimStatus;
use App\Http\Controllers\Controller;
use App\Models\Claims\TaxClaim;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Support\Facades\Gate;

class ClaimsController extends Controller
{
    public function index(){
        if (!Gate::allows('tax-claim-view')) {
            abort(403);
        }
        return view('claims.index');
    }

    public function show($claimId){
        if (!Gate::allows('tax-claim-view')) {
            abort(403);
        }
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

        if ($return instanceof HotelReturn){
            $returnView = 'returns.hotel.details';
            return view('claims.show', compact('claim', 'returnView'));
        }
    }
}
