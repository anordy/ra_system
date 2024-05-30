<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\HotelLevyCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class HotelReturnController extends Controller
{
    use HotelLevyCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-hotel-levy-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.hotel.hotel-card-one';
            $cardTwo = 'returns.hotel.hotel-card-two';
            $tableName = 'returns.hotel.hotel-returns-table';

            return view('returns.hotel.index', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }


    }

    public function tour()
    {
        if (!Gate::allows('return-tour-operation-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.hotel.tour-card-one';
            $cardTwo = 'returns.hotel.tour-card-two';
            $tableName = 'returns.hotel.tour-operator-returns-table';
            return view('returns.hotel.tour', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-RETURN-CONTROLLER-TOUR', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function restaurant()
    {
        if (!Gate::allows('return-restaurant-levy-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.hotel.restaurant-card-one';
            $cardTwo = 'returns.hotel.restaurant-card-two';
            $tableName = 'returns.hotel.restaurant-returns-table';
            return view('returns.hotel.restaurant', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-RETURN-CONTROLLER-RESTAURANT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }

    public function show($return_id)
    {
        if (!Gate::allows('return-hotel-levy-view')) {
            abort(403);
        }

        try {
            $returnId = decrypt($return_id);
            $return = HotelReturn::with(['penalties'])->findOrFail($returnId, ['id', 'business_location_id', 'business_id', 'filed_by_type', 'currency', 'filed_by_id', 'tax_type_id', 'financial_year_id', 'edited_count', 'status', 'application_status', 'claim_status', 'return_category', 'hotel_infrastructure_tax', 'withheld_tax', 'financial_month_id', 'total_amount_due', 'total_amount_due_with_penalties', 'penalty', 'interest', 'submitted_at', 'paid_at', 'filing_due_date', 'payment_due_date', 'created_at', 'updated_at', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.hotel.show', compact('return'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function airbnb()
    {
        if (!Gate::allows('return-hotel-airbnb-levy-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.hotel.airbnb-card-one';
            $cardTwo = 'returns.hotel.airbnb-card-two';
            $tableName = 'returns.hotel.airbnb-returns-table';

            return view('returns.hotel.airbnb', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-RETURN-CONTROLLER-AIRBNB', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }


}
