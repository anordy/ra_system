<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaxRegionController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-tax-region-view')) {
            abort(403);
        }
        return view('settings.tax-regions');
    }
}
