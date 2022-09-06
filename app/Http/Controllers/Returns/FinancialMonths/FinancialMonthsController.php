<?php

namespace App\Http\Controllers\Returns\FinancialMonths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialMonthsController extends Controller
{
    public function index()
    {
        return view('returns.financial_months.index');
    }
}
