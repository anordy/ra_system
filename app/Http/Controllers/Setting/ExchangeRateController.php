<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExchangeRateController extends Controller
{
     //
     public function index()
     {
          if (!Gate::allows('setting-exchange-rate-view')) {
               abort(403);
          }

          return view('settings.exchange-rate');
     }
}
