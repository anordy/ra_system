<?php

namespace App\Http\Controllers\TaxpayerLedger;

use App\Enum\CustomMessage;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaxpayerLedgerController extends Controller
{

    public function search()
    {
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }
        return view('taxpayer-ledger.search');
    }

    public function show($businessLocationId, $taxTypeId)
    {
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }

        try {
            $businessLocationId = decrypt($businessLocationId);
            $taxTypeId = decrypt($taxTypeId);
            $ledgers = TaxpayerLedger::select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at')
                ->where('business_location_id', $businessLocationId)
                ->where('tax_type_id', $taxTypeId)
                ->orderBy('source_type', 'ASC')
                ->orderBy('source_id', 'ASC')
                ->orderBy('financial_month_id', 'ASC')
                ->get();

            $locationName = BusinessLocation::findOrFail($businessLocationId, ['name'])->name;
            $taxTypeName = TaxType::findOrFail($taxTypeId, ['name'])->name;
            $creditSum = $ledgers->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $debitSum = $ledgers->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;
            return view('taxpayer-ledger.show', compact('ledgers', 'creditSum', 'debitSum', 'locationName', 'taxTypeName'));
        } catch (\Exception $exception) {
            Log::error('TAXPAYER-LEDGER-CONTROLLER', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }

    public function summary($businessLocationId)
    {
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }

        try {
            $businessLocationId = decrypt($businessLocationId);

            $location = BusinessLocation::findOrFail($businessLocationId);

            $ledgers = TaxpayerLedger::select('source_type', 'source_id', 'business_location_id', 'tax_type_id', 'currency', 'transaction_type',  'total_amount',
                DB::raw('(SELECT SUM(total_amount) FROM taxpayer_ledgers WHERE tax_type_id = t.tax_type_id) as total_debit_amount'),
                DB::raw('(SELECT SUM(total_amount) FROM taxpayer_ledgers WHERE tax_type_id = t.tax_type_id AND transaction_type = t.transaction_type) as total_credit_amount')
            )
                ->from(DB::raw('taxpayer_ledgers t'))
                ->where('business_id', $location->business_id)
                ->groupBy('source_type', 'source_id', 'business_location_id', 'tax_type_id', 'currency', 'transaction_type', 'total_amount')
                ->get();

            $creditSum = $ledgers->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $debitSum = $ledgers->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;
            return view('taxpayer-ledger.summary', compact('ledgers', 'creditSum', 'debitSum', 'location'));
        } catch (\Exception $exception) {
            dd($exception);
            Log::error('TAXPAYER-LEDGER-CONTROLLER', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }

}
