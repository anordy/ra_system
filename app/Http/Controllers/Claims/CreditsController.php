<?php

namespace App\Http\Controllers\Claims;

use App\Http\Controllers\Controller;
use App\Models\Claims\TaxCredit;
use Illuminate\Http\Request;

class CreditsController extends Controller
{
    public function index(){
        return view('claims.credits.index');
    }

    public function show($creditId){
        $credit = TaxCredit::findOrFail(decrypt($creditId));

        return view('claims.credits.show', compact('credit'));
    }
}
