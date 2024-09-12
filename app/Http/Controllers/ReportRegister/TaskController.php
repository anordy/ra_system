<?php

namespace App\Http\Controllers\ReportRegister;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        if (!Gate::allows('report-register-view-task')) {
            abort(403);
        }

        return view('report-register.task.index');
    }

    public function show($id)
    {
        if (!Gate::allows('report-register-view-task')) {
            abort(403);
        }

        return view('report-register.task.show', compact('id'));
    }
    
}
