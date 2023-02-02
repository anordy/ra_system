<?php

namespace App\Http\Controllers\Reports\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentalReportController extends Controller
{
    public function index(){
        return view('reports.departmental.index');
    }
}
