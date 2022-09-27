<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Enum\ReturnCategory;
use Illuminate\Console\Command;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use App\Jobs\Debt\SendDebtBalanceSMS;
use App\Jobs\Debt\SendDebtBalanceMail;
use App\Models\TaxAssessments\TaxAssessment;

class DailyZeroDebtBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:debt-zero-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Debt Zero Balance';

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
        Log::channel('debtZeroBalance')->info('Daily Debt Zero Balance process started');
        // TODO: Don't send notification if already sent
        $this->runReturnsCheck();
        $this->runAssessmentsCheck();
        Log::channel('debtZeroBalance')->info('Daily Debt Zero Balance process ended');
    }

    protected function runReturnsCheck()
    {
        Log::channel('debtZeroBalance')->info("Daily Debt Zero Balance for tax returns");
        $tax_returns = TaxReturn::query()->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->where('payment_status', ReturnStatus::COMPLETE)
            ->where('tax_returns.outstanding_amount', 0)
            ->get();

        foreach ($tax_returns as $tax_return) {
                $this->sendNotification($tax_return);
        }
    }

    protected function runAssessmentsCheck()
    {
        Log::channel('debtZeroBalance')->info("Daily Debt Zero Balance for tax returns");
        $tax_assessments = TaxAssessment::query()->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->where('payment_status', ReturnStatus::COMPLETE)
            ->where('tax_assessments.outstanding_amount', 0)
            ->get();

        foreach ($tax_assessments as $tax_assessment) {
                $this->sendNotification($tax_assessment);
        }
    }


    public function sendNotification($debt)
    {
        $payload = [
            'debt' => $debt,
        ];

        $now = Carbon::now();

        SendDebtBalanceMail::dispatch($payload)->delay($now->addSeconds(30));
        SendDebtBalanceSMS::dispatch($payload)->delay($now->addSeconds(45));

    }
}
