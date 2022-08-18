<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\ZmPayment;
use Carbon\Carbon;

class PaymentsController extends Controller
{
    public function complete(){
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
}
