<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Support\Facades\DB;

class HotelReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){
        
        $data = $this->returnCardReport(HotelReturn::class, 'hotel', 'hotel_return');

        $vars['totalSubmittedReturns'] = HotelReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = HotelReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = HotelReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('mno_returns')
                    ->join('financial_months', 'mno_returns.financial_month_id','financial_months.id')
                    ->where('mno_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('mno_returns')
                    ->join('zm_bills','mno_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',HotelReturn::class)
                    ->where('mno_returns.status','complete')
                    ->where('mno_returns.created_at','>','zm_payments.trx_time')
                    ->count();
        return view('returns.hotel.index',compact('vars','data'));
    }

    public function show($return_id){
        $returnId = decrypt($return_id);
        $return = HotelReturn::findOrFail($returnId);
        return view('returns.hotel.show', compact('return'));
    }

    public function adjust($return_id){
        $returnId = decrypt($return_id);
        return view('returns.hotel.adjust', compact('returnId'));
    }

}
