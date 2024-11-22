<?php

namespace App\Http\Controllers\ReportRegister;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index()
    {
        return view('report-register.task.index');
    }

    public function show($id)
    {
        return view('report-register.task.show', compact('id'));
    }
    
}
