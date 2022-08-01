<?php

namespace App\Http\Controllers\PortTaxReturn;

use App\Http\Controllers\Controller;
use App\Models\PortTaxReturn\PortTaxCategory;
use Illuminate\Http\Request;

class PortTaxReturnController extends Controller
{
    public function rates()
    {
		return view('port-return.rates-config');
    }
}
