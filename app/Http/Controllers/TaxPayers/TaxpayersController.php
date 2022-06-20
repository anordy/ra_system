<?php

namespace App\Http\Controllers\Taxpayers;

use App\Http\Controllers\Controller;
use App\Models\TaxPayer;
use Illuminate\Http\Request;

class TaxpayersController extends Controller
{
    public function index(){
        return view('taxpayers.index');
    }

    public function show($taxPayerId){
        $taxPayer = TaxPayer::findOrFail($taxPayerId);

        return view('taxpayers.show', compact('taxPayer'));
    }
}
