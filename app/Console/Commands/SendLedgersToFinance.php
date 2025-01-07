<?php

namespace App\Console\Commands;

use App\Enum\TransactionType;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\TaxpayerLedgerTrait;
use Illuminate\Console\Command;

class SendLedgersToFinance extends Command
{

    use TaxpayerLedgerTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:ledgers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send taxpayer ledgers';

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
//      $this->loadDebitNo();
        $this->sendDebits();
//        $this->sendCredits();
    }

    public function sendDebits()
    {
        try {
            $this->line('Sending debit ledgers');

            TaxpayerLedger::query()
                ->select(['id', 'source_type', 'source_id', 'financial_month_id', 'zm_payment_id', 'business_id', 'business_location_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'description', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at', 'updated_at', 'deleted_at', 'outstanding_amount', 'debit_no'])
                ->where('transaction_type', TransactionType::DEBIT)
                ->where('sent_to_finance', 0)
                ->chunk(100, function ($ledgers) {
                    foreach ($ledgers as $ledger) {
                        $response = $this->postDebit($ledger);

                        if (isset($response['status']) && $response['status']) {
                            $ledger->sent_to_finance = 1;
                            $ledger->save();
                        }

                        unset($return);
                    }
                });

            $this->info('Completed sending debit ledger');


        } catch (\Exception $exception) {
            $this->error($exception);
        }
    }

    public function sendCredits()
    {
        $this->line('Sending Credit ledgers');
        TaxpayerLedger::query()
            ->select(['id', 'source_type', 'source_id', 'financial_month_id', 'zm_payment_id', 'business_id', 'business_location_id', 'taxpayer_id', 'tax_type_id', 'transaction_date', 'transaction_type', 'currency', 'description', 'principal_amount', 'interest_amount', 'penalty_amount', 'total_amount', 'created_at', 'updated_at', 'deleted_at', 'outstanding_amount', 'debit_no'])
            ->where('transaction_type', TransactionType::CREDIT)
            ->where('sent_to_finance', 0)
            ->chunk(100, function ($ledgers) {
                foreach ($ledgers as $ledger) {
                    $response = $this->postCredit($ledger->zm_payment_id, $ledger);

                    if (isset($response['status']) && $response['status']) {
                        $ledger->sent_to_finance = 1;
                        $ledger->save();
                    }

                    unset($ledger);
                }
            });
    }

    public function loadDebitNo()
    {
        TaxpayerLedger::query()
            ->where('transaction_type', TransactionType::CREDIT)
            ->whereNull('debit_no')
            ->chunk(100, function ($ledgers) {
                foreach ($ledgers as $ledger) {

                    $debitLedger = TaxpayerLedger::query()
                        ->select('debit_no')
                        ->where('source_type', $ledger->source_type)
                        ->where('source_id', $ledger->source_id)
                        ->first();

                    if ($debitLedger) {
                        $ledger->debit_no = $debitLedger->debit_no;
                        $ledger->save();
                    }

                    unset($ledger);
                }
            });
    }


}
