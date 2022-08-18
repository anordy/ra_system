<?php

namespace App\Http\Controllers\Returns\Hotel;

use Carbon\Carbon;
use App\Models\TaxType;
use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\HotelLevyCardReport;
use App\Traits\ReturnSummaryCardTrait;

class HotelReturnController extends Controller
{
    use HotelLevyCardReport,ReturnSummaryCardTrait;

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

        $vars = $this->getSummaryData(HotelReturn::query()->where('tax_type_id',$tax_type_id));

        return ['vars' => $vars, 'data' => $data];
    }
}
