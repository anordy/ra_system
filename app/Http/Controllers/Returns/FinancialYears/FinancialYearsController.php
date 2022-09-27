<?php

namespace App\Http\Controllers\Returns\FinancialYears;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FinancialYearsController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-financial-year-view')) {
            abort(403);
        }
        return view('returns.financial_years.index');
    }
}
