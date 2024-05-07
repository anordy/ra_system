<?php

namespace App\Console\Commands;

use App\Enum\AssistantStatus;
use App\Enum\TransactionType;
use App\Models\Business;
use App\Models\BusinessAssistant;
use App\Models\Returns\TaxReturn;
use App\Models\Taxpayer;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\ZmPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $this->line('Recording tax returns ledgers');

            // TAX RETURNS
            $taxReturns = TaxReturn::get();

            foreach ($taxReturns as $return) {
                $ledger = TaxpayerLedger::updateOrCreate(
                    [
                      'source_type' => TaxReturn::class,
                      'source_id' => $return->id
                    ],
                    [
                    'source_type' => TaxReturn::class,
                    'source_id' => $return->id,
                    'tax_type_id' => $return->tax_type_id,
                    'taxpayer_id' => $return->filed_by_id,
                    'financial_month_id' => $return->financial_month_id,
                    'transaction_type' => TransactionType::DEBIT,
                    'business_id' => $return->business_id,
                    'business_location_id' => $return->business_location_id,
                    'currency' => $return->currency,
                    'transaction_date' => Carbon::now(),
                    'principal_amount' => $return->principal,
                    'interest_amount' => $return->interest,
                    'penalty_amount' => $return->penalty,
                    'total_amount' => $return->total_amount
                ]);

                if (!$ledger) throw new \Exception('Failed to save ledger');
            }

            // PAYMENTS
            $payments = ZmPayment::get();

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
                        'business_location_id' => $payment->bill->billable->business_location_id ?? null,
                        'currency' => $payment->currency,
                        'transaction_date' => Carbon::now(),
                        'principal_amount' => 0,
                        'interest_amount' => 0,
                        'penalty_amount' => 0,
                        'total_amount' => $payment->bill->paid_amount
                    ]);

                if (!$ledger) throw new \Exception('Failed to save ledger');

            }

            // PROPERTY TAX

            // TAX ASSESSMENTS

            $this->info('Completed recording tax returns ledger');
            DB::commit();
        } catch (\Exception $exception){
            DB::rollBack();
            $this->error($exception->getMessage());
        }
        return 0;
    }

}
