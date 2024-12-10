<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxpayerLedger\TaxpayerLedgerPayment;
use App\Models\TaxpayerLedger\TaxpayerLedgerPaymentItem;
use App\Models\ZmPayment;
use App\Traits\TaxpayerLedgerTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LoadTaxpayerLedger extends Command
{

    use TaxpayerLedgerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:ledgers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load taxpayer ledgers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->addDebitNumberToCredits();
//        return;
        try {
            $this->line('Recording tax returns ledgers');

            // TAX RETURNS
            TaxReturn::chunk(100, function ($taxReturns) {
                foreach ($taxReturns as $return) {
                    $ledger = TaxpayerLedger::updateOrCreate(
                        [
                            'source_type' => TaxReturn::class,
                            'source_id' => $return->id,
                            'transaction_type' => TransactionType::DEBIT,
                        ],
                        [
                            'source_type' => TaxReturn::class,
                            'source_id' => $return->id,
                            'tax_type_id' => $return->tax_type_id,
                            'taxpayer_id' => $return->filed_by_id,
                            'financial_month_id' => $return->financial_month_id,
                            'transaction_type' => TransactionType::DEBIT,
                            'business_id' => $return->business_id,
                            'business_location_id' => $return->location_id,
                            'currency' => $return->currency,
                            'transaction_date' => Carbon::create($return->created_at),
                            'principal_amount' => $return->principal,
                            'interest_amount' => $return->interest,
                            'penalty_amount' => $return->penalty,
                            'total_amount' => $return->total_amount,
                            'debit_no' => $this->generateDebitNumber()
                        ]
                    );

                    if (!$ledger) throw new \Exception('Failed to save ledger');
                }
            });

            $this->addSourceTypesToPaymentItems();

            $this->line('Recording payments ledgers');

            // PAYMENTS
            ZmPayment::chunk(100, function ($payments) {
                foreach ($payments as $payment) {

                    if ($payment->bill) {
                        if ($payment->bill->billable_type === TaxpayerLedgerPayment::class) {
                            $this->loadPartialPayments($payment);
                        } else {
                            $ledger = TaxpayerLedger::updateOrCreate(
                                [
                                    'source_type' => $payment->bill->billable_type,
                                    'source_id' => $payment->bill->billable_id,
                                    'zm_payment_id' => $payment->id
                                ],
                                [
                                    'source_type' => $payment->bill->billable_type,
                                    'source_id' => $payment->bill->billable_id,
                                    'zm_payment_id' => $payment->id,
                                    'tax_type_id' => $payment->bill->tax_type_id,
                                    'taxpayer_id' => $payment->bill->payer_id,
                                    'financial_month_id' => $payment->bill->billable->financial_month_id ?? null,
                                    'transaction_type' => TransactionType::CREDIT,
                                    'business_id' => $payment->bill->billable->business_id ?? null,
                                    'business_location_id' => $payment->bill->billable->location_id ?? null,
                                    'currency' => $payment->currency,
                                    'transaction_date' => Carbon::create($payment->created_at),
                                    'principal_amount' => 0,
                                    'interest_amount' => 0,
                                    'penalty_amount' => 0,
                                    'total_amount' => $payment->bill->paid_amount,
//                                    'debit_no' => $item->ledger->debit_no
                                ]
                            );
                            if (!$ledger) throw new \Exception('Failed to save ledger');
                        }

                    }

                }
            });

            // TAX ASSESSMENTS
            TaxAssessment::chunk(100, function ($assessments) {
                foreach ($assessments as $assessment) {
                    $ledger = TaxpayerLedger::updateOrCreate(
                        [
                            'source_type' => TaxAssessment::class,
                            'source_id' => $assessment->id,
                            'transaction_type' => TransactionType::DEBIT,
                        ],
                        [
                            'source_type' => TaxAssessment::class,
                            'source_id' => $assessment->id,
                            'tax_type_id' => $assessment->tax_type_id,
                            'taxpayer_id' => $assessment->business->taxpayer_id,
                            'financial_month_id' => $assessment->financial_month_id,
                            'transaction_type' => TransactionType::DEBIT,
                            'business_id' => $assessment->business_id,
                            'business_location_id' => $assessment->location_id,
                            'currency' => $assessment->currency,
                            'transaction_date' => Carbon::create($assessment->created_at),
                            'principal_amount' => $assessment->principal_amount,
                            'interest_amount' => $assessment->interest_amount,
                            'penalty_amount' => $assessment->penalty_amount,
                            'total_amount' => $assessment->total_amount,
                            'debit_no' => $this->generateDebitNumber()
                        ]
                    );

                    if (!$ledger) throw new \Exception('Failed to save ledger');
                }
            });

            // Update outstanding amounts
            TaxpayerLedger::where('transaction_type', TransactionType::DEBIT)->chunk(100, function ($ledgers) {
                foreach ($ledgers as $ledger) {
                    // Collect all the credits and save to debit as paid amount to get outstanding amount
                    $credits = TaxpayerLedger::select('id', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'source_type', 'source_id', 'tax_type_id', 'financial_month_id', 'transaction_type', 'business_id', 'business_location_id', 'currency', 'taxpayer_id')
                        ->where('source_type', $ledger->source_type)
                        ->where('source_id', $ledger->source_id)
                        ->where('transaction_type', TransactionType::CREDIT)
                        ->get();

                    $ledger->outstanding_amount = abs($ledger->total_amount - $credits->sum('total_amount'));

                    if ($ledger->outstanding_amount == 0 && $ledger->payment_status) {
                        $ledger->payment_status = ReturnStatus::COMPLETE;
                    }
                    $ledger->save();
                }
            });

            $this->info('Completed recording tax returns ledger');
        } catch (\Exception $exception) {
            $this->error($exception);
        }
        return 0;
    }

    private function addSourceTypesToPaymentItems() {
        $paymentItems = TaxpayerLedgerPaymentItem::query()->get();

        $count = 0;
        foreach ($paymentItems as $paymentItem) {
            if ($paymentItem->ledger) {
                $paymentItem->update([
                    'source_type' => $paymentItem->ledger->source_type,
                    'source_id' => $paymentItem->ledger->source_id,
                ]);
            } else {
                $count++;
            }
        }
        $this->info("Skipped {$count} payment items as they dont have a linked ledger");

    }

    private function addDebitNumberToCredits() {
        $paymentItems = TaxpayerLedger::query()->where('transaction_type', TransactionType::CREDIT)->get();

        foreach ($paymentItems as $paymentItem) {
                $ledger = TaxpayerLedger::query()->where('source_type', $paymentItem->source_type)
                    ->where('source_id', $paymentItem->source_id)
                    ->first();

                $paymentItem->update([
                   'debit_no' => $ledger->debit_no
                ]);
        }

    }

    private function loadPartialPayments($payment) {
        // Get base payment
        $ledgerPayment = $payment->bill->billable;

        // Get payment items
        $paymentItems = $ledgerPayment->items;

        foreach ($paymentItems as $paymentItem) {
            TaxpayerLedger::updateOrCreate(
                [
                    'source_type' => $paymentItem->source_type,
                    'source_id' => $paymentItem->source_type,
                    'transaction_type' => TransactionType::CREDIT,
                    'zm_payment_id' => $payment->id
                ],
                [
                    'source_type' => $paymentItem->source_type,
                    'source_id' => $paymentItem->source_type,
                    'zm_payment_id' => $payment->id,
                    'tax_type_id' => $paymentItem->tax_type_id,
                    'taxpayer_id' => $payment->bill->payer_id,
                    'financial_month_id' => $paymentItem->ledger->financial_month_id ?? null,
                    'transaction_type' => TransactionType::CREDIT,
                    'business_id' => $paymentItem->ledger->business_id ?? null,
                    'business_location_id' => $paymentItem->ledger->location_id ?? null,
                    'currency' => $paymentItem->currency,
                    'transaction_date' => Carbon::create($payment->created_at),
                    'principal_amount' => 0,
                    'interest_amount' => 0,
                    'penalty_amount' => 0,
                    'total_amount' => $paymentItem->amount
                ]
            );
        }

    }
}
