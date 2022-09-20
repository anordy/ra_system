<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateAssessmentDebtControlNo;
use App\Jobs\Debt\GenerateControlNo;
use App\Models\Debts\Debt;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyForDebt;
use Carbon\Carbon;
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
        Log::channel('debtCollection')->info('Daily Debt penalties and interest collection and calculations started');
        $this->generateReturnsDebtPenalty();
        $this->generateAssessmentDebtPenalty();
        Log::channel('debtCollection')->info('Daily Debt penalties and interest collection and calculations ended');
    }

    public function generateReturnsDebtPenalty()
    {
        $now = Carbon::now();
        /**
         * Get tax returns 
         * CONDITION 1: Return category is either debt or overdue (This qualifies to be penalty calculated)
         * CONDITION 2: Payment status is not complete
         * CONDITION 3: The current next payment_due_date has reached
         */
        $tax_returns = TaxReturn::selectRaw('tax_returns.*, TIMESTAMPDIFF(month, filing_due_date, NOW()) as periods, TIMESTAMPDIFF(month, curr_payment_due_date, NOW()) as penatableMonths')
            ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereRaw("TIMESTAMPDIFF(DAY, tax_returns.curr_payment_due_date, CURDATE()) = 0")
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get();

        if ($tax_returns) {

            DB::beginTransaction();
            try {

                foreach ($tax_returns as $tax_return) {
                    // Generate penalty
                    PenaltyForDebt::generateReturnsPenalty($tax_return);

                    // Cancel previous bill if exists
                    if ($tax_return->bill) {
                        CancelBill::dispatch($tax_return->bill, 'Debt Penalty Increment')->delay($now->addSeconds(2));
                    }

                    GenerateControlNo::dispatch($tax_return)->delay($now->addSeconds(10));
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        }

    }

    public function generateAssessmentDebtPenalty()
    {
        $now = Carbon::now();
        /**
         * Get tax returns 
         * CONDITION 1: Return category is either debt or overdue (This qualifies to be penalty calculated)
         * CONDITION 2: Payment status is not complete
         * CONDITION 3: The current next payment_due_date has reached
         */
        $tax_assessments = TaxAssessment::selectRaw('tax_assessments.*, TIMESTAMPDIFF(month, curr_payment_due_date, NOW()) as periods, TIMESTAMPDIFF(month, curr_payment_due_date, NOW()) as penatableMonths')
            ->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereRaw("TIMESTAMPDIFF(DAY, tax_assessments.curr_payment_due_date, CURDATE()) = 0")
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get();

        if ($tax_assessments) {

            DB::beginTransaction();
            try {

                foreach ($tax_assessments as $tax_assessment) {
                    // Generate penalty
                    PenaltyForDebt::generateAssessmentsPenalty($tax_assessment);

                    // Cancel previous bill if exists
                    if ($tax_assessment->bill) {
                        CancelBill::dispatch($tax_assessment->bill, 'Debt Penalty Increment')->delay($now->addSeconds(2));
                    }

                    GenerateAssessmentDebtControlNo::dispatch($tax_assessment)->delay($now->addSeconds(10));
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
            }
        }

    }
}
