<?php

namespace App\Http\Controllers\Debt;

use App\Http\Controllers\Controller;

class AssessmentDebtController extends Controller
{

    public function index()
    {
        return view('debts.assessments.index');
    }

    
}
