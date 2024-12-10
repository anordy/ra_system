<?php

namespace App\Traits;


use App\Enum\Currencies;
use App\Enum\TransactionType;
use App\Models\BusinessLocation;
use App\Models\Installment\InstallmentItem;
use App\Models\PartialPayment;
use App\Models\Returns\TaxReturn;
use App\Models\Sequence;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxpayerLedger\TaxpayerLedgerPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TaxpayerLedgerTrait
{

    /**
     * Record transaction in ledger
     * @param $transactionType
     * @param $sourceType
     * @param $sourceId
     * @param $principalAmount
     * @param $interestAmount
     * @param $penaltyAmount
     * @param $totalAmount
     * @param $taxTypeId
     * @param $currency
     * @param $taxPayerId
     * @param $locationId
     * @param $financialMonthId
     * @return void
     * @throws \Exception
     */
    public function recordLedger($transactionType, $sourceType, $sourceId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount, $taxTypeId, $currency, $taxPayerId, $locationId = null, $financialMonthId = null, $description = null)
    {
        try {
            if (!in_array($transactionType, TransactionType::getConstants())) {
                throw new \Exception('Invalid Transaction Type');
            }

            if (!in_array($currency, Currencies::getConstants())) {
                throw new \Exception('Invalid Currency Provided');
            }

            if ($interestAmount < 0 && $penaltyAmount < 0 && $totalAmount < 0) {
                throw new \Exception('Invalid Amount provided');
            }

            if ($sourceType === TaxReturn::class) {
                $hasClaim = TaxReturn::findOrFail($sourceId, ['has_claim'])->has_claim;

                if ($hasClaim) {
                    $principalAmount = 0;
                }
            }

            if ($locationId) {
                $location = BusinessLocation::find($locationId, ['business_id']);

                if (!$location) {
                    throw new \Exception('Associated business in the provided location not found');
                }
            }

            $ledger = TaxpayerLedger::create([
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'tax_type_id' => $taxTypeId,
                'taxpayer_id' => $taxPayerId,
                'financial_month_id' => $financialMonthId,
                'transaction_type' => $transactionType,
                'business_id' => $locationId ? $location->business_id : null,
                'business_location_id' => $locationId,
                'currency' => $currency,
                'transaction_date' => Carbon::now(),
                'principal_amount' => $principalAmount,
                'interest_amount' => $interestAmount,
                'penalty_amount' => $penaltyAmount,
                'total_amount' => $totalAmount,
                'outstanding_amount' => $totalAmount,
                'description' => $description,
                'debit_no' => $transactionType === TransactionType::DEBIT ? $this->generateDebitNumber() : null
            ]);

            if (!$ledger) throw new \Exception('Failed to save ledger transaction');

        } catch (\Exception $exception) {
            Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-RECORD-LEDGER', [$exception]);
            throw $exception;
        }
    }

    public static function recordLedgerDebt($sourceType, $sourceId, $interestAmount, $penaltyAmount, $totalAmount)
    {
        try {

            if ( $interestAmount < 0 || $penaltyAmount < 0 || $totalAmount < 0) {
                throw new \Exception('Invalid Amount provided');
            }

            $ledger = TaxpayerLedger::select('id', 'interest_amount', 'penalty_amount', 'total_amount')
                ->where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->first();

            if ($ledger) {
                $ledger->interest_amount = $interestAmount;
                $ledger->penalty_amount = $penaltyAmount;
                $ledger->total_amount = $totalAmount;
                $ledger->outstanding_amount = $totalAmount;
                $ledger->transaction_date = Carbon::now();

                if (!$ledger->save()) throw new \Exception('Failed to Save Ledger');
            }


        } catch (\Exception $exception) {
            Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-RECORD-LEDGER-DEBT', [$exception]);
            throw $exception;
        }
    }

    /**
     * Record ledger for public service
     * @param $class
     * @param $service
     * @param $fee
     * @param $taxTypeId
     * @return void
     * @throws \Exception
     */
    public function recordDebitLedger($service, $fee, $taxTypeId, $taxpayerId = null){
        // Record ledger transaction
        $this->recordLedger(
            TransactionType::DEBIT,
            get_class($service),
            $service->id,
            $fee,
            0,
            0,
            $fee,
            $taxTypeId,
            Currencies::TZS,
            $taxpayerId ?? $service->taxpayer_id
        );
    }

    public function generateDebitNumber() {
        try {
            $sequence = Sequence::where('name', Sequence::DEBIT_NUMBER)->first();

            if (!$sequence) {
                $sequence = Sequence::create([
                   'name' => Sequence::DEBIT_NUMBER,
                   'prefix' => 'TDN',
                   'next_id' => 1
                ]);
            }

            $currentSequence = now()->format('y') . str_pad($sequence->next_id, 5, "0", STR_PAD_LEFT);

            $sequence->next_id = $sequence->next_id + 1;
            $sequence->save();

            return $currentSequence;

        } catch (\Exception $exception) {
            Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-GENERATE-DEBIT-NUMBER', [$exception]);
            throw $exception;
        }
    }

    public function recordCreditLedger($bill, $paymentId)
    {
        try {

            $billableType = $bill->billable_type;
            $billableId = $bill->billable_id;
            $billable = $bill->billable;

            if ($billableType === PartialPayment::class) {
                $billableType = $billable->payment_type;
                $billableId = $billable->payment_id;
            }

            if($billableType === InstallmentItem::class){
                $billableType = $bill->billable->installment->installable_type;
                $billableId = $bill->billable->installment->installable_id;
            }

            if($billableType === TaxpayerLedgerPayment::class){
                $items = $bill->billable->items;

                foreach ($items as $item) {
                    TaxpayerLedger::updateOrCreate(
                        [
                            'source_type' => $item->ledger->source_type ?? $bill->billable_type,
                            'source_id' => $item->ledger->source_id ?? $bill->billable_id,
                            'transaction_type' => TransactionType::CREDIT,
                            'zm_payment_id' => $paymentId
                        ],
                        [
                            'source_type' => $item->ledger->source_type ?? $billableType,
                            'source_id' => $item->ledger->source_id ?? $billableId,
                            'tax_type_id' => $item->ledger->tax_type_id ?? $bill->tax_type_id,
                            'taxpayer_id' => $item->ledger->taxpayer_id ?? $bill->payer_id,
                            'financial_month_id' => $item->ledger->financial_month_id ?? null,
                            'transaction_type' => TransactionType::CREDIT,
                            'business_id' => $item->ledger->business_id ?? null,
                            'business_location_id' => $item->ledger->business_location_id ?? null,
                            'currency' => $bill->currency,
                            'transaction_date' => Carbon::now(),
                            'principal_amount' => 0,
                            'interest_amount' => 0,
                            'penalty_amount' => 0,
                            'total_amount' => $item->amount,
                            'zm_payment_id' => $paymentId
                        ]);

                    $credits = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                        ->where('source_type', $item->ledger->source_type)
                        ->where('source_id', $item->ledger->source_id)
                        ->where('transaction_type', TransactionType::CREDIT)
                        ->get();

                    $mainDebit = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                        ->where('source_type', $item->ledger->source_type)
                        ->where('source_id', $item->ledger->source_id)
                        ->where('transaction_type', TransactionType::DEBIT)
                        ->first();

                    if ($mainDebit) {
                        $mainDebit->outstanding_amount = abs($mainDebit->total_amount - $credits->sum('total_amount'));
                        $mainDebit->save();
                    }
                }
            } else {
                $ledger = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                    ->where('source_type', $billableType)
                    ->where('source_id', $billableId)
                    ->first();

                $ledger = TaxpayerLedger::updateOrCreate(
                    [
                        'source_type' => $ledger->source_type ?? $bill->billable_type,
                        'source_id' => $ledger->source_id ?? $bill->billable_id,
                        'transaction_type' => TransactionType::CREDIT,
                        'zm_payment_id' => $paymentId
                    ],
                    [
                        'source_type' => $ledger->source_type ?? $billableType,
                        'source_id' => $ledger->source_id ?? $billableId,
                        'tax_type_id' => $ledger->tax_type_id ?? $bill->tax_type_id,
                        'taxpayer_id' => $ledger->taxpayer_id ?? $bill->payer_id,
                        'financial_month_id' => $ledger->financial_month_id ?? null,
                        'transaction_type' => TransactionType::CREDIT,
                        'business_id' => $ledger->business_id ?? null,
                        'business_location_id' => $ledger->business_location_id ?? null,
                        'currency' => $bill->currency,
                        'transaction_date' => Carbon::now(),
                        'principal_amount' => 0,
                        'interest_amount' => 0,
                        'penalty_amount' => 0,
                        'total_amount' => $bill->paid_amount,
                        'zm_payment_id' => $paymentId
                    ]);

                if (!$ledger) {
                    Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-RECORD-LEDGER', ['Failed to save payment to ledger']);
                }

            }

            // Collect all the credits and save to debit as paid amount to get outstanding amount
            $credits = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                ->where('source_type', $billableType)
                ->where('source_id', $billableId)
                ->where('transaction_type', TransactionType::CREDIT)
                ->get();

            $mainDebit = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                ->where('source_type', $billableType)
                ->where('source_id', $billableId)
                ->where('transaction_type', TransactionType::DEBIT)
                ->first();

            if ($mainDebit) {
                $mainDebit->outstanding_amount = abs($mainDebit->total_amount - $credits->sum('total_amount'));
                $mainDebit->save();
            }


        } catch (\Exception $exception) {
            Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-RECORD-LEDGER', [$exception]);
            throw $exception;
        }
    }

}
