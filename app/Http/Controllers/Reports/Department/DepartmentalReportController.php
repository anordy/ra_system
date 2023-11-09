<?php

namespace App\Http\Controllers\Reports\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentalReportController extends Controller
{
    public function index(){
        if (!Gate::allows('managerial-departmental-report-view')) {
            abort(403);
        }
        return view('reports.departmental.index');
    }
}
