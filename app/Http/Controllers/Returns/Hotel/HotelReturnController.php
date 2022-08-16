<?php

namespace App\Http\Controllers\Returns\Hotel;

use Carbon\Carbon;
use App\Models\TaxType;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\HotelLevyCardReport;

class HotelReturnController extends Controller
{
    use HotelLevyCardReport;

    public function index()
    {
        $tax = TaxType::where('code', TaxType::HOTEL)->first();
        $summary = $this->getSummary($tax->id);
        $vars = $summary['vars'];
        $data = $summary['data'];
        return view('returns.hotel.index', compact('vars', 'data'));
    }

    public function tour(){
        $tax = TaxType::where('code', TaxType::TOUR_OPERATOR)->first();
        $summary = $this->getSummary($tax->id);
        $vars = $summary['vars'];
        $data = $summary['data'];
        return view('returns.hotel.tour', compact('vars', 'data'));
    }

    public function restaurant(){
        $tax = TaxType::where('code', TaxType::RESTAURANT)->first();
        $summary = $this->getSummary($tax->id);
        $vars = $summary['vars'];
        $data = $summary['data'];
        return view('returns.hotel.restaurant', compact('vars', 'data'));
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

    public function getSummary($tax_type_id)
    {

        $data = $this->hotelLevyCardReport(HotelReturn::class, 'hotel', 'hotel_return', $tax_type_id);

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->where('hotel_returns.tax_type_id', $tax_type_id)
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->where('hotel_returns.tax_type_id', $tax_type_id)
            ->where('hotel_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('hotel_returns')
            ->join('businesses', 'businesses.id', 'hotel_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->where('hotel_returns.tax_type_id', $tax_type_id)
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('hotel_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'hotel_returns.financial_month_id', 'financial_months.id')
            ->where('hotel_returns.tax_type_id', $tax_type_id)
            ->where('hotel_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('hotel_returns')
            ->join('financial_months', 'hotel_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'hotel_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->where('hotel_returns.tax_type_id', $tax_type_id)
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', HotelReturn::class)
            ->where('hotel_returns.status', 'complete')
            ->where('hotel_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        return ['vars' => $vars, 'data' => $data];
    }
}
