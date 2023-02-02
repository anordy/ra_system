<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Traits\HotelLevyCardReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Traits\ReturnSummaryCardTrait;
use App\Models\Returns\HotelReturns\HotelReturn;

class HotelReturnController extends Controller
{
    use HotelLevyCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-hotel-levy-view')) {
            abort(403);
        }

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

        $cardOne    = 'returns.hotel.restaurant-card-one';
        $cardTwo    = 'returns.hotel.restaurant-card-two';
        $tableName  = 'returns.hotel.restaurant-returns-table';

        return view('returns.hotel.restaurant', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = HotelReturn::findOrFail($returnId);
        $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
        return view('returns.hotel.show', compact('return'));
    }

    public function airbnb()
    {
        if (!Gate::allows('return-hotel-airbnb-levy-view')) {
            abort(403);
        }

        $cardOne    = 'returns.hotel.airbnb-card-one';
        $cardTwo    = 'returns.hotel.airbnb-card-two';
        $tableName  = 'returns.hotel.airbnb-returns-table';

        return view('returns.hotel.airbnb', compact('cardOne', 'cardTwo', 'tableName'));
    }


}
