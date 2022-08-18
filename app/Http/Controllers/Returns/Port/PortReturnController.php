<?php

namespace App\Http\Controllers\Returns\Port;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Port\PortReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PortReturnController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        $vars = $this->getSummaryData(PortReturn::query());

        $returnQuery = PortReturn::where('status', '!=', 'complete');

        $penaltyDataTZS = PortReturn::where('port_returns.status', '!=', 'complete')->leftJoin('port_return_penalties', 'port_returns.id', '=', 'port_return_penalties.return_id')
            ->where('port_return_penalties.currency', 'USD')
            ->select(DB::raw('SUM(port_return_penalties.late_filing) as totalLateFiling'), DB::raw('SUM(port_return_penalties.late_payment) as totalLatePayment'), DB::raw('SUM(port_return_penalties.rate_amount) as totalRate'))
            ->groupBy('return_id')
            ->get();

        $penaltyDataUSD = PortReturn::where('port_returns.status', '!=', 'complete')->leftJoin('port_return_penalties', 'port_returns.id', '=', 'port_return_penalties.return_id')
                ->where('port_return_penalties.currency', 'USD')
                ->select(DB::raw('SUM(port_return_penalties.late_filing) as totalLateFiling'), DB::raw('SUM(port_return_penalties.late_payment) as totalLatePayment'), DB::raw('SUM(port_return_penalties.rate_amount) as totalRate'))
                ->groupBy('return_id')
                ->get();


        $data = [
            'totalTaxAmountTZS' => $returnQuery->sum('port_returns.total_amount_due_with_penalties_tzs'),
            'totalLateFilingTZS' => $penaltyDataTZS->sum('totalLateFiling'),
            'totalLatePaymentTZS' => $penaltyDataTZS->sum('totalLatePayment'),
            'totalRateTZS' => $penaltyDataTZS->sum('totalRate'),
            'totalTaxAmountUSD' => $returnQuery->sum('port_returns.total_amount_due_with_penalties_usd'),
            'totalLateFilingUSD' => $penaltyDataUSD->sum('totalLateFiling'),
            'totalLatePaymentUSD' => $penaltyDataUSD->sum('totalLatePayment'),
            'totalRateUSD' => $penaltyDataUSD->sum('totalRate'),
        ];
        // return $data;

        return view('returns.port.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PortReturn::findOrFail($returnId);
        return view('returns.port.show', compact('return'));
    }
}
