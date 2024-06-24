<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

class GeneralReportsController extends Controller
{
    public  function  initial(){
        return view('reports.general.initial');
    }


}