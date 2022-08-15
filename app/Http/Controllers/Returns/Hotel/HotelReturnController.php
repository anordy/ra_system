<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HotelReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {

        $data = $this->returnCardReport(HotelReturn::class, 'hotel', 'hotel_return');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->where('hotel_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('hotel_returns')
            ->join('businesses', 'businesses.id', 'hotel_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('hotel_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'hotel_returns.financial_month_id', 'financial_months.id')
            ->where('hotel_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'hotel_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'hotel_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', HotelReturn::class)
            ->where('hotel_returns.status', 'complete')
            ->where('hotel_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();
        return view('returns.hotel.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = HotelReturn::findOrFail($returnId);
        return view('returns.hotel.show', compact('return'));
    }

    public function adjust($return_id)
    {
        $returnId = decrypt($return_id);
        return view('returns.hotel.adjust', compact('returnId'));
    }
}
