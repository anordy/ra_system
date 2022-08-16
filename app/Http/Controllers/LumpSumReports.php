<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LumpSumReports extends Controller
{
    public function index()
    {
        return view('returns.lump-sum.reports');
    }
}
