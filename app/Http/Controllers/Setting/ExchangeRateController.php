<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    //
    public function index()
   {
        return view('settings.exchange-rate');
   }
}
