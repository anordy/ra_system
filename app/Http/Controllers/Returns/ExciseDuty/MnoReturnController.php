<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MnoReturnController extends Controller
{
    public function index(){
        $vars['totalSubmittedReturns'] = MnoReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = MnoReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = MnoReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('mno_returns')
                    ->join('financial_months', 'mno_returns.financial_month_id','financial_months.id')
                    ->where('mno_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('mno_returns')
                    ->join('zm_bills','mno_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',MnoReturn::class)
                    ->where('mno_returns.status','complete')
                    ->where('mno_returns.created_at','>','zm_payments.trx_time')
                    ->count();

        return view('returns.excise-duty.mno.index',compact('vars'));
    }

    public function show($id){
        $return = MnoReturn::find(decrypt($id));
        return view('returns.excise-duty.mno.show',compact('return'));
    }
}
