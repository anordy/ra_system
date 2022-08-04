<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Http\Request;

class CertificateQuantityController extends Controller
{
    public function index()
    {
        return view('returns.petroleum.certificateQuantity.index');
    }

    
}
