<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\ZmPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LoadTaxpayerLedger extends Command
{
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
                            'total_amount' => $return->total_amount
                        ]
                    );

                    if (!$ledger) throw new \Exception('Failed to save ledger');
                }
            });

            $this->line('Recording payments ledgers');

            // PAYMENTS
            ZmPayment::chunk(100, function ($payments) {
                foreach ($payments as $payment) {
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
                            'total_amount' => $payment->bill->paid_amount
                        ]
                    );

                    if (!$ledger) throw new \Exception('Failed to save ledger');
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
                            'total_amount' => $assessment->total_amount
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
                    $ledger->save();
                }
            });


            $this->info('Completed recording tax returns ledger');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
        return 0;
    }
}
