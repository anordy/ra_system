<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxRegionController extends Controller
{
    public function index(){
        return view('settings.tax-regions');
    }
}
