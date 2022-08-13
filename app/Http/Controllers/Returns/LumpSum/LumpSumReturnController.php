<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Returns\LumpSum\LumpSumReturns;
use App\Models\BusinessLocation;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LumpSumReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        
        $data = $this->returnCardReport(LumpSumReturn::class, 'lump_sum', 'lump_sum');

        $vars['totalSubmittedReturns'] = LumpSumReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = LumpSumReturn::where('status', 'complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = LumpSumReturn::where('status', '!=', 'complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('lump_sum_returns')
                    ->join('financial_months', 'lump_sum_returns.financial_month_id', 'financial_months.id')
                    ->where('lump_sum_returns.created_at', '>', 'financial_months.due_date')
                    ->count();
        
        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('lump_sum_returns')
                    ->join('zm_bills', 'lump_sum_returns.id', 'zm_bills.billable_id')
                    ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
                    ->where('zm_bills.billable_type', LumpSumReturn::class)
                    ->where('lump_sum_returns.status', 'complete')
                    ->where('lump_sum_returns.created_at', '>', 'zm_payments.trx_time')
                    ->count();

        return view('returns.lumpsum.history', compact('vars', 'data'));
    }

    public function create(Request $request)
    {
        $location         = $request->location_id;
        $tax_type         = $request->tax_type_code;
        $business         = $request->business;
        $filling_month_id = $request->filling_month_id;
        $location         = BusinessLocation::findOrFail(decrypt($location));

        return view('returns.lump-sum.lump-sum', compact('location', 'tax_type', 'business', 'filling_month_id'));
    }
    
    public function history()
    {
        return view('returns.lumpsum.history');
    }

    public function view($row)
    {
        $row = decrypt($row);
        $id  = $row->id;
        
        $return = LumpSumReturn::findOrFail($id);

        return view('returns.lumpsum.view', compact('return'));
    }
}
