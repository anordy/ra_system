<?php

namespace App\Http\Controllers\Reports\Returns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnReportController extends Controller
{
    public function index(){
        return view('reports.returns.index');
    }
}
