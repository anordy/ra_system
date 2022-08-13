<?php

namespace App\Http\Controllers\Returns\Port;

use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Support\Facades\DB;

class PortReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $vars['totalSubmittedReturns'] = PortReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = PortReturn::where('status','complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = PortReturn::where('status','!=','complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('port_returns')
                    ->join('financial_months', 'port_returns.financial_month_id','financial_months.id')
                    ->where('port_returns.created_at','>','financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('port_returns')
                    ->join('zm_bills','port_returns.id','zm_bills.billable_id')
                    ->join('zm_payments','zm_payments.zm_bill_id','zm_bills.id')
                    ->where('zm_bills.billable_type',PortReturn::class)
                    ->where('port_returns.status','complete')
                    ->where('port_returns.created_at','>','zm_payments.trx_time')
                    ->count();
                    
        $data = $this->returnCardReport(PortReturn::class, 'port', 'port_return');

        return $data;
        
        return view('returns.port.index',compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PortReturn::findOrFail($returnId);
        return view('returns.port.show', compact('return'));
    }
}
