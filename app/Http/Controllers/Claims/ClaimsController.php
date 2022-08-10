<?php

namespace App\Http\Controllers\Claims;

use App\Http\Controllers\Controller;
use App\Models\Claims\TaxClaim;

class ClaimsController extends Controller
{
    public function index(){
        return view('claims.index');
    }

    public function show($claimId){
        $claimId = decrypt($claimId);
        $claim = TaxClaim::findOrFail($claimId);
        $newReturn = $claim->newReturn;
        $oldReturn = $claim->oldReturn;
        return view('claims.show', compact('claim', 'oldReturn', 'newReturn'));
    }
}
