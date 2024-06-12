<?php

namespace App\Http\Controllers\Reports\TaxPayer;

use App\Http\Controllers\Controller;

class TaxPayerReportController extends Controller
{

    public  function  index(){
        return view('reports.taxpayer.index');
    }

}