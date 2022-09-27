<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\ZmPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class PaymentsController extends Controller
{
    public function complete(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        $usdDaily = ZmPayment::where('currency', 'USD')
            ->whereDate('trx_time', Carbon::today())
            ->sum('paid_amount');
        $tzsDaily = ZmPayment::where('currency', 'TZS')
            ->whereDate('trx_time', Carbon::today())
            ->sum('paid_amount');
        $tzsWeekly = ZmPayment::where('currency', 'TZS')
            ->whereBetween('trx_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('paid_amount');
        $usdWeekly = ZmPayment::where('currency', 'USD')
            ->whereBetween('trx_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('paid_amount');
        return view('payments.complete', compact('usdDaily', 'tzsDaily', 'tzsWeekly', 'usdWeekly'));
    }

    public function show($paymentId){
        $payment = ZmPayment::findOrFail(decrypt($paymentId));
        return view('payments.show', compact('payment'));
    }
}
