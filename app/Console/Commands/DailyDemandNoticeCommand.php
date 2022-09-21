<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Jobs\DemandNotice\SendDemandNoticeEmail;
use App\Models\Debts\Debt;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyDemandNoticeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Demand Notice';

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
        Log::channel('demandNotice')->info('Daily Demand notice process started');
        $this->runReturnsDemandNotices();
        Log::channel('demandNotice')->info('Daily Demand notice process ended');
    }

    protected function runReturnsDemandNotices()
    {
        Log::channel('demandNotice')->info("Daily Demand notice for tax returns");
        $debts = TaxReturn::where('return_category', ReturnCategory::OVERDUE)
            ->get();

        foreach ($debts as $debt) {
            $paid_within_days = 0;
            $next_notify_date = 0;

            if ($debt->demand_notice_count == 0) {
                $paid_within_days = 30;
                $next_notify_date = 14;
            } else if ($debt->demand_notice_count == 1) {
                $paid_within_days = 14;
                $next_notify_date = 7;
            } else if ($debt->demand_notice_count == 2) {
                $paid_within_days = 7;
                $next_notify_date = 7;
            }

            $this->sendDemandNotice($debt, $paid_within_days, $next_notify_date);

        }
    }


    public function sendDemandNotice($debt, $paid_within_days, $next_notify_date)
    {

        $email = $debt->business->taxpayer->email;

        $payload = [
            'email' => $email,
            'debt' => $debt,
            'paid_within_days' => $paid_within_days,
            'next_notify_date' => $next_notify_date
        ];

        $now = Carbon::now();
        $nextSendDate = Carbon::create($debt->next_demand_notice_date);

        if ($now->gt($nextSendDate)) {
            SendDemandNoticeEmail::dispatch($payload)->delay($now->addSeconds(30));
        }
    }
}
