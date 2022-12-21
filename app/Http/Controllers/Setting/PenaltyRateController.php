<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PenaltyRateController extends Controller
{
     //
     public function index()
     {
          if (!Gate::allows('setting-penalty-rate-view')) {
               abort(403);
          }
          return view('settings.penalty-rate.index');
     }
}
