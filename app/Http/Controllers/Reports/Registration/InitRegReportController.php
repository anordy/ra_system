<?php

namespace App\Http\Controllers\Reports\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InitRegReportController extends Controller
{
    public function init(){
        return view('reports.registration.init');
    }
}
