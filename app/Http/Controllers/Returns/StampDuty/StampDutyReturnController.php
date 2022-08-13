<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){

        $data = $this->returnCardReport(StampDutyReturn::class, 'stamp_duty', 'stamp_duty_return');

        $vars['totalSubmittedReturns'] = StampDutyReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = StampDutyReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = StampDutyReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('stamp_duty_returns')
                    ->join('financial_months', 'stamp_duty_returns.financial_month_id','financial_months.id')
                    ->where('stamp_duty_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('stamp_duty_returns')
                    ->join('zm_bills','stamp_duty_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',StampDutyReturn::class)
                    ->where('stamp_duty_returns.status','complete')
                    ->where('stamp_duty_returns.created_at','>','zm_payments.trx_time')
                    ->count();
        return view('returns.stamp-duty.index',compact('vars','data'));
    }

    public function show($returnId){
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }

}
