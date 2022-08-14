<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\TaxType;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MnoReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){

        $data = $this->returnCardReport(MnoReturn::class, 'mno', 'mno');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('mno_returns')
                    ->join('financial_months','financial_months.id','mno_returns.financial_month_id')
                    ->whereBetween('financial_months.due_date',[$from,$to])
                    ->count();
        
        //total paid returns
        $vars['totalPaidReturns'] = DB::table('mno_returns')
                    ->join('financial_months','financial_months.id','mno_returns.financial_month_id')
                    ->where('mno_returns.status','complete')
                    ->whereBetween('financial_months.due_date',[$from,$to])
                    ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('mno_returns')
                    ->join('businesses','businesses.id','mno_returns.business_id') 
                    ->join('financial_months','financial_months.id','mno_returns.financial_month_id')
                    ->where('businesses.status',BusinessStatus::APPROVED)
                    ->where('mno_returns.status','!=','complete')
                    ->whereBetween('financial_months.due_date',[$from,$to])
                    ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('mno_returns')
                    ->join('financial_months', 'mno_returns.financial_month_id','financial_months.id')
                    ->where('mno_returns.created_at','>','financial_months.due_date')
                    ->whereBetween('financial_months.due_date',[$from,$to])
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('mno_returns')
                    ->join('financial_months', 'mno_returns.financial_month_id','financial_months.id')
                    ->join('zm_bills','mno_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->whereBetween('financial_months.due_date',[$from,$to])
                    ->where('zm_bills.billable_type',MnoReturn::class)
                    ->where('mno_returns.status','complete')
                    ->where('mno_returns.created_at','>','zm_payments.trx_time')
                    ->count();

        return view('returns.excise-duty.mno.index',compact('vars','data'));
    }

    public function show($id){
        $return = MnoReturn::find(decrypt($id));
        return view('returns.excise-duty.mno.show',compact('return'));
    }
}
