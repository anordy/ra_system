<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        return view('returns.index');
    }

}
