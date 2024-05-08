<?php

namespace App\Http\Controllers\TaxpayerLedger;

use App\Enum\CustomMessage;
use App\Enum\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxType;
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
}
