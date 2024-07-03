<?php

namespace App\Http\Controllers\Returns\Port;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PortReturnController extends Controller
{
    public function airport()
    {
        if (!Gate::allows('return-airport-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.port.air-port-card-one';
            $cardTwo = 'returns.port.air-port-card-two';
            $tableName = 'returns.port.port-return-table';
            return view('returns.port.airport', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('PORT-CONTROLLER-AIRPORT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function seaport()
    {
        if (!Gate::allows('return-seaport-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.port.sea-port-card-one';
            $cardTwo = 'returns.port.sea-port-card-two';
            $tableName = 'returns.port.port-return-table';
            return view('returns.port.seaport', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('PORT-CONTROLLER-SEAPORT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function show($return_id)
    {
        if (!Gate::allows('return-seaport-return-view ')) {
            abort(403);
        }

        try {
            $returnId = decrypt($return_id);
            $return = PortReturn::with(['penalties'])->findOrFail($returnId, ['id', 'business_location_id', 'business_id', 'filed_by_type', 'filed_by_id', 'currency', 'parent', 'tax_type_id', 'financial_year_id', 'edited_count', 'status', 'application_status', 'return_category', 'infrastructure_tax', 'infrastructure_znz_znz', 'infrastructure_znz_tm', 'financial_month_id', 'airport_safety_fee', 'airport_service_charge', 'seaport_service_charge', 'seaport_transport_charge', 'total_amount_due', 'total_amount_due_with_penalties', 'penalty', 'interest', 'submitted_at', 'paid_at', 'filing_due_date', 'payment_due_date', 'created_at', 'updated_at', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.port.show', compact('return'));
        } catch (\Exception $exception) {
            Log::error('PORT-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
