<?php

namespace App\Http\Controllers\Returns\FinancialYears;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialYearsController extends Controller
{
    public function index()
    {
        return view('returns.financial_years.index');
    }
}
