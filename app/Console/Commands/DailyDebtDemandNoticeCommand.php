<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Jobs\DemandNotice\SendDebtDemandNoticeEmail;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyDebtDemandNoticeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:debt-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Debt Demand Notice';

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
        Log::channel('dailyJobs')->info('Daily Demand notice process started');
        $this->runReturnsDemandNotices();
        $this->runAssessmentsDemandNotices();
        Log::channel('dailyJobs')->info('Daily Demand notice process ended');
    }

    protected function runReturnsDemandNotices()
    {
        Log::channel('dailyJobs')->info("Daily Demand notice for tax returns");

        // TODO: Improve this query
        $debts = TaxReturn::with('demandNotices')->where('return_category', ReturnCategory::OVERDUE)
            ->get();

        foreach ($debts as $debt) {

            $paid_within_days = 0;
            $next_notify_days = 0;

            // Send first debt demand notice
            if (count($debt->demandNotices) == 0) {
                $paid_within_days = 30;
                $next_notify_days = 14;
                $this->sendFirstDemandNotice($debt, $paid_within_days, $next_notify_days);

            // Send second debt demand notice
            } else if (count($debt->demandNotices) == 2) {
                $paid_within_days = 14;
                $next_notify_days = 7;
                $this->sendRemainingDemandNotice($debt, $paid_within_days, $next_notify_days);

            // Send final debt demand notice
            } else if (count($debt->demandNotices) == 3) {
                $paid_within_days = 7;
                $next_notify_days = 0;
                $this->sendRemainingDemandNotice($debt, $paid_within_days, $next_notify_days);

            }


        }
    }

    protected function runAssessmentsDemandNotices()
    {
        Log::channel('dailyJobs')->info("Daily Demand notice for tax returns");

        // TODO: Improve this query
        $debts = TaxAssessment::with('demandNotices')->where('assessment_step', ReturnCategory::OVERDUE)
            ->get();

        foreach ($debts as $debt) {

            $paid_within_days = 0;
            $next_notify_days = 0;

            // Send first debt demand notice
            if (count($debt->demandNotices) == 0) {
                $paid_within_days = 30;
                $next_notify_days = 14;
                $this->sendFirstDemandNotice($debt, $paid_within_days, $next_notify_days);

            // Send second debt demand notice
            } else if (count($debt->demandNotices) == 1) {
                $paid_within_days = 14;
                $next_notify_days = 7;
                $this->sendRemainingDemandNotice($debt, $paid_within_days, $next_notify_days);

            // Send final debt demand notice
            } else if (count($debt->demandNotices) == 2) {
                $paid_within_days = 7;
                $next_notify_days = 0;
                $this->sendRemainingDemandNotice($debt, $paid_within_days, $next_notify_days);

            }


        }
    }


    public function sendFirstDemandNotice($debt, $paid_within_days, $next_notify_days)
    {
        $payload = [
            'debt' => $debt,
            'paid_within_days' => $paid_within_days,
            'next_notify_days' => $next_notify_days
        ];

        $now = Carbon::now();

        SendDebtDemandNoticeEmail::dispatch($payload)->delay($now->addSeconds(30));
    }

    public function sendRemainingDemandNotice($debt, $paid_within_days, $next_notify_days)
    {
        $payload = [
            'debt' => $debt,
            'paid_within_days' => $paid_within_days,
            'next_notify_days' => $next_notify_days
        ];

        $now = Carbon::now();

        $nextSendDate = Carbon::create($debt->demandNotices->last()->next_notify_date);

        if ($now->gt($nextSendDate)) {
            SendDebtDemandNoticeEmail::dispatch($payload)->delay($now->addSeconds(30));
        }
    }
}
