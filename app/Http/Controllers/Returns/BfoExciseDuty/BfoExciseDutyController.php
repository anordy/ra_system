<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(BfoReturn::class, 'bfo', 'bfo');
        $vars['totalSubmittedReturns'] = BfoReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = BfoReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = BfoReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('bfo_returns')
                    ->join('financial_months', 'bfo_returns.financial_month_id','financial_months.id')
                    ->where('bfo_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('bfo_returns')
                    ->join('zm_bills','bfo_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',BfoReturn::class)
                    ->where('bfo_returns.status','complete')
                    ->where('bfo_returns.created_at','>','zm_payments.trx_time')
                    ->count();

        return view('returns.excise-duty.bfo.index',compact('vars','data'));
    }

    public function show($return_id)
    {
        $return = BfoReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
    }
}
