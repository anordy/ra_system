<?php

namespace App\Http\Controllers\VatReturn;

use App\Http\Controllers\Controller;
use App\Models\VatReturn\VatReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VatReturnController extends Controller
{
    public function index()
    {
        return view('vat-return.index');

    }

    public function requests(Request $request)
    {
        return view('vat-return.requests',[
            'year'=>$request->year,
            'month'=>$request->month,
        ]);
    }

    public function rates()
    {
		return view('vat-return.rates-config');
    }

    public function show($id)
    {
        $return = VatReturn::where('created_by', Auth::id())->latest()->first();
        return view('vat-return.show', compact('return'));
    }
}
