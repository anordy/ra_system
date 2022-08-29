<?php

namespace App\Console\Commands;

use App\Enum\BillStatus;
use App\Enum\DebtPaymentMethod;
use App\Enum\InstallmentStatus;
use App\Enum\PaymentMethod;
use App\Models\Installment\Installment;
use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateInstallmentState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:installment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update installments and debt state.';

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
        Log::channel('installment')->info('Installment update process started');
        foreach (Installment::where('status', InstallmentStatus::ACTIVE)->get() as $installment){
            $this->updateInstallment($installment);
        }
        Log::channel('installment')->info('Installment update process ended');
        return 1;
    }

    public function updateInstallment($installment){
        // get full paid items count
        $itemsCount = $installment->items()->where('status', BillStatus::COMPLETE)->count();

        if ($itemsCount >= $installment->installment_count){
            Log::channel('installment')->info('Manually marking installment as complete.');
            $installment->update([
                'status' => InstallmentStatus::COMPLETE
            ]);
            // Mark debt as complete
            $installment->debt->update([
                'status' => ReturnStatus::COMPLETE
            ]);

            // Mark return as paid by debt
            $installment->debt->debt->update([
                'status' => ReturnStatus::PAID_BY_DEBT
            ]);

        } elseif($installment->getNextPaymentDate() && $installment->getNextPaymentDate()->lessThan(Carbon::today())) {
            // Mark as FORFEIT
            $installment->update([
                'status' => InstallmentStatus::CANCELLED
            ]);

            // Return debt to normal
            $installment->debt->update([
                'app_step' => DebtPaymentMethod::NORMAL
            ]);
        }

        Log::channel('installment')->info($itemsCount);
    }
}
