<?php

namespace App\Http\Controllers\Returns\Hotel;

use Carbon\Carbon;
use App\Models\TaxType;
use App\Traits\HotelLevyCardReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Traits\ReturnSummaryCardTrait;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnPenalty;

class HotelReturnController extends Controller
{
    use HotelLevyCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-hotel-levy-view')) {
            abort(403);
        }
        // $tax        = TaxType::where('code', TaxType::HOTEL)->first();
        // $summary    = $this->getSummary($tax->id);
        // $vars       = $summary['vars'];
        // $paidData   = $summary['paidData'];
        // $unpaidData = $summary['unpaidData'];
        $cardOne    = 'returns.hotel.hotel-card-one';
        $cardTwo    = 'returns.hotel.hotel-card-two';
        $tableName  = 'returns.hotel.hotel-returns-table';

        return view('returns.hotel.index', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function tour()
    {
        if (!Gate::allows('return-tour-operation-view')) {
            abort(403);
        }
        // $tax        = TaxType::where('code', TaxType::TOUR_OPERATOR)->first();
        // $summary    = $this->getSummary($tax->id);
        // $vars       = $summary['vars'];
        // $paidData   = $summary['paidData'];
        // $unpaidData = $summary['unpaidData'];
        $cardOne    = 'returns.hotel.tour-card-one';
        $cardTwo    = 'returns.hotel.tour-card-two';
        $tableName  = 'returns.hotel.tour-operator-returns-table';

        return view('returns.hotel.tour', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function restaurant()
    {
        if (!Gate::allows('return-restaurant-levy-view')) {
            abort(403);
        }
        // $tax        = TaxType::where('code', TaxType::RESTAURANT)->first();
        // $summary    = $this->getSummary($tax->id);
        // $vars       = $summary['vars'];
        // $paidData   = $summary['paidData'];
        // $unpaidData = $summary['unpaidData'];
        $cardOne    = 'returns.hotel.restaurant-card-one';
        $cardTwo    = 'returns.hotel.restaurant-card-two';
        $tableName  = 'returns.hotel.restaurant-returns-table';

        return view('returns.hotel.restaurant', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = HotelReturn::findOrFail($returnId);
        $return->penalties = $return->penalties->merge($return->tax_return->penalties);
        return view('returns.hotel.show', compact('return'));
    }

    public function adjust($return_id)
    {
        $returnId = decrypt($return_id);

        return view('returns.hotel.adjust', compact('returnId'));
    }

    // public function getSummary($tax_type_id)
    // {
    //     $paidData   = $this->hotelLevyCardReportForPaidReturns(HotelReturn::class, HotelReturn::getTableName(), HotelReturnPenalty::getTableName(), $tax_type_id);
    //     $unpaidData = $this->hotelLevyCardReportForUnpaidReturns(HotelReturn::class, HotelReturn::getTableName(), HotelReturnPenalty::getTableName(), $tax_type_id);

    //     $vars = $this->getSummaryData(HotelReturn::query()->where('tax_type_id', $tax_type_id));

    //     return ['vars' => $vars, 'paidData' => $paidData, 'unpaidData' => $unpaidData];
    // }
}
