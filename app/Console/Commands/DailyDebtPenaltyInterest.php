<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateAssessmentDebtControlNo;
use App\Jobs\Debt\GenerateControlNo;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyForDebt;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyDebtPenaltyInterest extends Command
{
    use PaymentsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:debt-penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Debt penalties and interest collection and calculations';

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
        Log::channel('dailyJobs')->info('Daily Debt penalties and interest collection and calculations started');
        $this->generateReturnsDebtPenalty();
        $this->generateAssessmentDebtPenalty();
        Log::channel('dailyJobs')->info('Daily Debt penalties and interest collection and calculations ended');
    }

    public function generateReturnsDebtPenalty()
    {
        /**
         * Get tax returns 
         * CONDITION 1: Return category is either debt or overdue (This qualifies to be penalty calculated)
         * CONDITION 2: Payment status is not complete
         * CONDITION 3: The current payment_due_date has reached
         * MONTHS_BETWEEN(date1, date2) - If date1 is greater than date2, then the result is positive & vice      versa is true
         */
        $tax_returns = TaxReturn::selectRaw('
            tax_returns.*, 
            ROUND(MONTHS_BETWEEN(CURRENT_DATE, CAST(filing_due_date as date))) as periods, 
            ROUND(MONTHS_BETWEEN(CAST(curr_payment_due_date as date), CURRENT_DATE)) as penatableMonths
        ')
            ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereRaw("CURRENT_DATE - CAST(curr_payment_due_date as date) > 0") // This determines if the payment due date has reached
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE]) // Get all non paid returns
            ->get();

        if ($tax_returns) {

            foreach ($tax_returns as $tax_return) {
                // Lumpsum return is handled differently
                if ($tax_return->return_type != LumpSumReturn::class) {
                    DB::beginTransaction();
                    try {
                        // Generate penalty
                        PenaltyForDebt::generateReturnsPenalty($tax_return);

                        // Cancel previous latest bill if exists
                        if ($tax_return->latestBill) {
                            CancelBill::dispatch($tax_return->latestBill, 'Debt Penalty Increment');
                        }

                        GenerateControlNo::dispatch($tax_return);
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        Log::error($e);
                    }
                }
            }
        }
    }

    public function generateAssessmentDebtPenalty()
    {
        /**
         * Get tax returns 
         * CONDITION 1: Return category is either debt or overdue (This qualifies to be penalty calculated)
         * CONDITION 2: Payment status is not complete
         * CONDITION 3: The current next payment_due_date has reached
         */
        $tax_assessments = TaxAssessment::selectRaw('
            tax_assessments.*, 
            ROUND(MONTHS_BETWEEN(CURRENT_DATE, CAST(curr_payment_due_date as date))) as periods, 
            ROUND(MONTHS_BETWEEN(CAST(curr_payment_due_date as date), CURRENT_DATE)) as penatableMonths
        ')
            ->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereRaw("CURRENT_DATE - CAST(curr_payment_due_date as date) > 0") // This determines if the payment due date has reached
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get();

        if ($tax_assessments) {

            foreach ($tax_assessments as $tax_assessment) {
                DB::beginTransaction();
                try {
                    // Generate penalty
                    PenaltyForDebt::generateAssessmentsPenalty($tax_assessment);
                    DB::commit();

                    // Cancel previous latest bill if exists
                    if ($tax_assessment->latestBill) {
                        CancelBill::dispatch($tax_assessment->latestBill, 'Debt Penalty Increment');
                    }

                    GenerateAssessmentDebtControlNo::dispatch($tax_assessment);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e);
                }
            }
        }
    }
}
