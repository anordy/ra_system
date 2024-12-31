<?php

namespace App\Console\Commands\Bills;

use App\Enum\BillStatus;
use App\Models\ZmBill;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkExpiredBills extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark Report Register as Breached and Remind unresolved Tasks';

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
        Log::channel('dailyJobs')->info('Start of bill marking process');
        $this->markExpired();
        Log::channel('dailyJobs')->info('End of bill marking process');

    }

    public function markExpired()
    {
        ZmBill::query()
            ->select('id', 'status', 'expire_date', 'created_at', 'updated_at')
            ->whereRaw("CURRENT_DATE - CAST(expire_date as date) > 0")
            ->where('status', BillStatus::PENDING)
            ->chunk(100, function ($bills) {
                foreach ($bills as $bill) {
                    try {
                        $expireDate = Carbon::create($bill->expire_date);

                        if (Carbon::now() > $expireDate) {
                            $bill->status = 'cancelled';
                            $bill->save();
                        }

                    } catch (Exception $exception) {
                        Log::channel('dailyJobs')->error($exception);
                    }

                    unset($bill);

                }
            });

    }


}
