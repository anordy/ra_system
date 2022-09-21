<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaxRegionController extends Controller
{
    public function index()
    {
        return view('settings.tax-regions');
    }
}
