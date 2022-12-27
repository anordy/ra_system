<?php

namespace App\Jobs\Payments;

use App\Enum\BankReconStatus;
use App\Models\BankRecon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FinalizeBankRecon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Perform changes on all recons with PENDING STATUS
        $recons  = BankRecon::with('bill')->where('recon_status', BankReconStatus::PENDING)->get();
        foreach ($recons as $recon) {
            Log::info('recon for ' . $recon->id);

            if (!$recon->bill){
                // Update to indicate recon not found.
                $recon->update([
                    'is_reconciled' => true,
                    'recon_status' => BankReconStatus::NOT_FOUND
                ]);
                continue;
            }

            if ($recon->credit_amount < $recon->bill->amount){
                // Update to indicate recon amount mismatch.
                $recon->update([
                    'is_reconciled' => true,
                    'recon_status' => BankReconStatus::AMOUNT_MISMATCH
                ]);
                $recon->bill->update(['bank_recon_status' => BankReconStatus::AMOUNT_MISMATCH]);
                continue;
            }

            if ($recon->credit_amount >= $recon->bill->amount){
                // Update recon to success
                $recon->update([
                    'is_reconciled' => true,
                    'recon_status' => BankReconStatus::SUCCESS
                ]);
                $recon->bill->update(['bank_recon_status' => BankReconStatus::SUCCESS]);
                continue;
            }

            $recon->update([
                'is_reconciled' => true,
                'recon_status' => BankReconStatus::FAILED
            ]);
            $recon->bill->update(['bank_recon_status' => BankReconStatus::FAILED]);
        }
    }
}
