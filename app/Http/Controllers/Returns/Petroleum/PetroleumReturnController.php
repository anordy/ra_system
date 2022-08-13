<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetroleumReturnController extends Controller
{
    public function index()
    {
        $vars['totalSubmittedReturns'] = PetroleumReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = PetroleumReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = PetroleumReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('petroleum_returns')
                    ->join('financial_months', 'petroleum_returns.financial_month_id','financial_months.id')
                    ->where('petroleum_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('petroleum_returns')
                    ->join('zm_bills','petroleum_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',PetroleumReturn::class)
                    ->where('petroleum_returns.status','complete')
                    ->where('petroleum_returns.created_at','>','zm_payments.trx_time')
                    ->count();
        return view('returns.petroleum.filing.index',compact('vars'));
    }

    public function create(Request $request)
    {
        $location = $request->location;
        $tax_type = $request->tax_type;
        $business = $request->business;
        return view('returns.petroleum.filing.filing', compact('location', 'tax_type', 'business'));
    }


    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PetroleumReturn::findOrFail($returnId);
        return view('returns.petroleum.filing.show', compact('return'));
    }

    public function edit($return)
    {
        return view('returns.petroleum.filing.edit', compact('return'));
    }
}
