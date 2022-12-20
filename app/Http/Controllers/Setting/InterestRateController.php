<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class InterestRateController extends Controller
{
    public function index(){
        if (!Gate::allows('setting-interest-rate-view')) {
            abort(403);
       }
        return view('settings.interest-rate');
    }
}
