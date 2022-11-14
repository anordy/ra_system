<?php

namespace App\Console\Commands;

use App\Enum\ApplicationStatus;
use App\Enum\BillStatus;
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
    protected $description = 'Update installments and tax return(debt) state. This command should be run on daily basis.';

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
        Log::channel('dailyJobs')->info('Installment update process started');
        foreach (Installment::where('status', InstallmentStatus::ACTIVE)->get() as $installment){
            $this->updateInstallment($installment);
        }
        Log::channel('dailyJobs')->info('Installment update process ended');
        return 1;
    }

    public function updateInstallment($installment){
        $itemsCount = $installment->items()->where('status', BillStatus::COMPLETE)->count();

        if ($itemsCount >= $installment->installment_count){
            Log::channel('dailyJobs')->info('Manually marking installment as complete.');
            $installment->update([
                'status' => InstallmentStatus::COMPLETE
            ]);

            $installment->installable->update([
                'payment_status' => ReturnStatus::COMPLETE
            ]);

            $installment->installable->return->update([
                'status' => ReturnStatus::PAID_BY_DEBT
            ]);

        } elseif($installment->getNextPaymentDate() && $installment->getNextPaymentDate()->lessThan(Carbon::today())) {
            $installment->update([
                'status' => InstallmentStatus::CANCELLED,
                'cancellation_reason' => "Installment was skipped, cancelled by system."
            ]);

            $installment->installable->update([
                'application_status' => ApplicationStatus::NORMAL,
                'payment_method' => PaymentMethod::FULL
            ]);
        }

        Log::channel('dailyJobs')->info($itemsCount);
    }
}
