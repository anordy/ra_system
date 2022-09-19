<?php

namespace App\Http\Controllers\Returns\FinancialMonths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FinancialMonthsController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-financial-month-view')) {
            abort(403);
        }
        return view('returns.financial_months.index');
    }
}
