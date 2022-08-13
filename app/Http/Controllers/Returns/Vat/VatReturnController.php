<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatReturnController extends Controller
{
    use ReturnCardReport;
    
    public function index()
    {
        $data = $this->returnCardReport(VatReturn::class, 'vat', 'vat_return');

        $vars['totalSubmittedReturns'] = VatReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = VatReturn::where('status', 'complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = VatReturn::where('status', '!=', 'complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'vat_returns.financial_month_id', 'financial_months.id')
            ->where('vat_returns.created_at', '>', 'financial_months.due_date')
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('vat_returns')
            ->join('zm_bills', 'vat_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->where('zm_bills.billable_type', VatReturn::class)
            ->where('vat_returns.status', 'complete')
            ->where('vat_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();
            
        return view('returns.vat_returns.index', compact('vars', 'data'));
    }
    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
        return view('returns.vat_returns.show', compact('return', 'id'));
    }
}
