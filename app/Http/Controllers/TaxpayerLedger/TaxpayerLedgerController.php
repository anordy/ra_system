<?php

namespace App\Http\Controllers\TaxpayerLedger;

use App\Enum\Currencies;
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

            $tzsLedgers = TaxpayerLedger::select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at')
                ->where('business_location_id', $businessLocationId)
                ->where('tax_type_id', $taxTypeId)
                ->where('currency', Currencies::TZS)
                ->orderBy('source_type', 'ASC')
                ->orderBy('source_id', 'ASC')
                ->orderBy('financial_month_id', 'ASC')
                ->get();

            $usdLedgers = TaxpayerLedger::select('id', 'source_type', 'source_id', 'zm_payment_id', 'business_location_id', 'financial_month_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at')
                ->where('business_location_id', $businessLocationId)
                ->where('tax_type_id', $taxTypeId)
                ->where('currency', Currencies::USD)
                ->orderBy('source_type', 'ASC')
                ->orderBy('source_id', 'ASC')
                ->orderBy('financial_month_id', 'ASC')
                ->get();

            $ledgers = [
                'USD' => $usdLedgers,
                'TZS' => $tzsLedgers
            ];

            $locationName = BusinessLocation::findOrFail($businessLocationId, ['name'])->name;
            $taxTypeName = TaxType::findOrFail($taxTypeId, ['name'])->name;

            $tzsCreditSum = $ledgers['TZS']->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $tzsDebitSum = $ledgers['TZS']->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;
            $usdCreditSum = $ledgers['USD']->where('transaction_type', TransactionType::CREDIT)->sum('total_amount') ?? 0;
            $usdDebitSum = $ledgers['USD']->where('transaction_type', TransactionType::DEBIT)->sum('total_amount') ?? 0;

            $summations = [
              'TZS' => ['credit' => $tzsCreditSum, 'debit' => $tzsDebitSum],
              'USD' => ['credit' => $usdCreditSum, 'debit' => $usdDebitSum],
            ];

            return view('taxpayer-ledger.show', compact('ledgers', 'summations', 'locationName', 'taxTypeName'));
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

            $tzsLedgers = $this->getLedgerByCurrency(Currencies::TZS, $location->business_id);
            $usdLedgers = $this->getLedgerByCurrency(Currencies::USD, $location->business_id);

            $ledgers = [
                'TZS' => $this->joinLedgers($tzsLedgers['debitLedgers'], $tzsLedgers['creditLedgers']),
                'USD' => $this->joinLedgers($usdLedgers['debitLedgers'], $usdLedgers['creditLedgers']),
            ];

            $tzsCreditSum = $tzsLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
            $tzsDebitSum = $tzsLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;
            $usdCreditSum = $usdLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
            $usdDebitSum = $usdLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;

            $summations = [
                'TZS' => ['credit' => $tzsCreditSum, 'debit' => $tzsDebitSum],
                'USD' => ['credit' => $usdCreditSum, 'debit' => $usdDebitSum],
            ];

            return view('taxpayer-ledger.summary', compact('ledgers', 'summations', 'location'));
        } catch (\Exception $exception) {
            Log::error('TAXPAYER-LEDGER-CONTROLLER', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }

    private function getLedgerByCurrency($currency, $businessId) {
        $debitLedgers = TaxpayerLedger::select(
            'source_type',
            'source_id',
            'business_location_id',
            'tax_type_id',
            'currency',
            DB::raw('SUM(total_amount) as total_debit_amount')
        )
            ->where('business_id', $businessId)
            ->where('transaction_type', TransactionType::DEBIT)
            ->where('currency', $currency)
            ->groupBy('source_type', 'source_id', 'business_location_id', 'tax_type_id', 'currency')
            ->get();

        $creditLedgers = TaxpayerLedger::select(
            'source_type',
            'source_id',
            'business_location_id',
            'tax_type_id',
            'currency',
            DB::raw('SUM(total_amount) as total_credit_amount')
        )
            ->where('business_id', $businessId)
            ->where('transaction_type', TransactionType::CREDIT)
            ->where('currency', $currency)
            ->groupBy('source_type', 'source_id', 'business_location_id', 'tax_type_id', 'currency')
            ->get();

        return [
            'debitLedgers' => $debitLedgers,
            'creditLedgers' => $creditLedgers,
        ];
    }

    private function joinLedgers($debitLedgers, $creditLedgers) {
        $joinedLedgers = collect();

        $debitLedgers->each(function ($item) use ($joinedLedgers, $creditLedgers) {
            // Extract key values
            $sourceType = $item->source_type;
            $sourceId = $item->source_id;
            $businessLocationId = $item->business_location_id;
            $taxTypeId = $item->tax_type_id;
            $currency = $item->currency;

            // Check if the key combination already exists in the joined collection
            $existingItem = $creditLedgers->where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->where('business_location_id', $businessLocationId)
                ->where('tax_type_id', $taxTypeId)
                ->where('currency', $currency)
                ->first();

            // If the key combination doesn't exist, create a new item with default values
            if ($existingItem) {
                $existingItem = (object)[
                    'source_type' => $sourceType,
                    'source_id' => $sourceId,
                    'business_location_id' => $businessLocationId,
                    'tax_type_name' => $item->taxtype->name,
                    'tax_type_id' => $taxTypeId,
                    'currency' => $currency,
                    'total_debit_amount' => $item->total_debit_amount, // Set default value
                    'total_credit_amount' => $existingItem->total_credit_amount, // Set default value
                ];
            } else {
                $existingItem = (object)[
                    'source_type' => $sourceType,
                    'source_id' => $sourceId,
                    'business_location_id' => $businessLocationId,
                    'tax_type_name' => $item->taxtype->name,
                    'tax_type_id' => $taxTypeId,
                    'currency' => $currency,
                    'total_debit_amount' => $item->total_debit_amount, // Set default value
                    'total_credit_amount' => 0, // Set default value
                ];
            }

            $joinedLedgers->push($existingItem);
        });

        return $joinedLedgers;
    }


}
