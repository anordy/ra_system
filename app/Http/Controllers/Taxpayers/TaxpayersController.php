<?php

namespace App\Http\Controllers\Taxpayers;

use App\Http\Controllers\Controller;
use App\Models\Taxpayer;
use Illuminate\Http\Request;

class TaxpayersController extends Controller
{
    public function index(){
        return view('taxpayers.index');
    }

    public function show($taxPayerId){
        $taxPayer = Taxpayer::findOrFail(decrypt($taxPayerId));

        return view('taxpayers.show', compact('taxPayer'));
    }
}
