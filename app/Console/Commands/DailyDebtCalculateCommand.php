<?php

namespace App\Console\Commands;

use App\Enum\ApplicationStep;
use App\Enum\ReturnCategory;
use App\Models\Debts\Debt;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Traits\PenaltyTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyDebtCalculateCommand extends Command
{
    use PenaltyTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:debt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily debt Calculations';

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
        Log::channel('debtCollection')->info('Daily Debt Marking process started');
        $financialMonth = $this->getCurrentFinancialMonth();
        $this->markReturnAsDebt($financialMonth);
        $this->markAssessmentAsDebt();
        Log::channel('debtCollection')->info('Daily Debt Marking process ended');
    }

    /**
     * Mark normal return as debt OR Mark debt return as overdue
     */
    protected function markReturnAsDebt($financialMonth)
    {
        Log::channel('debtCollection')->info("Daily Debt collection for financial month " . $financialMonth->name . " with due date " . $financialMonth->due_date . " process started");
        DB::beginTransaction();

        /**
         * Get all tax returns which are normal
         * CONDITION 1: For a return to be debt the filing due date must exceed 30 days to now
         * CONDITION 2: The return is not be paid at all
         * TODO: filing due date is not okay
         */
        $tax_returns = TaxReturn::selectRaw('tax_returns.*, TIMESTAMPDIFF(DAY, tax_returns.filing_due_date, CURDATE()) as days_passed')
            ->whereIn('return_category', [ReturnCategory::NORMAL, ReturnCategory::DEBT])
            ->whereRaw("TIMESTAMPDIFF(DAY, tax_returns.filing_due_date, CURDATE()) > 60") // Since filing due date is of last month
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get();

        try {
            foreach ($tax_returns as $tax_return) {
                /**
                 * Mark return process as debt if days_passed is less than 30
                 * 1. return_category from normal to debt
                 * 2. application_step from filing to debt
                 */
                if ($tax_return->days_passed < 60) {
                    $tax_return->update([
                        'return_category' => ReturnCategory::DEBT,
                        'application_step' => ApplicationStep::DEBT
                    ]);
                } else {
                    /**
                     * Mark return process as overdue if days_passed is greater than 30 days (Meaning 30 days as debt and another 30 days makes it an overdue)
                     * 1. return_category from debt to overdue
                     * 2. application_step from debt to overdue
                     */
                    $tax_return->update([
                        'return_category' => ReturnCategory::OVERDUE,
                        'application_step' => ApplicationStep::OVERDUE
                    ]);
                }
            }

            DB::commit();
            Log::channel('debtCollection')->info("Daily Debt collection for financial month " . $financialMonth->name . " with due date " . $financialMonth->due_date . " process ended");
        } catch (Exception $e) {
            Log::channel('debtCollection')->info('Daily Debt calculation process ended with error');
            Log::channel('debtCollection')->error($e);
            DB::rollBack();
        }
    }

    /**
     * Mark normal assessment_step as debt or overdue
     */
    protected function markAssessmentAsDebt()
    {
        Log::channel('debtCollection')->info("Daily Debt marking for assessment process started");
        DB::beginTransaction();

        $tax_assessments = TaxAssessment::selectRaw('tax_assessments.*, TIMESTAMPDIFF(DAY, tax_assessments.payment_due_date, CURDATE()) as days_passed')
            ->whereIn('assessment_step', [ReturnCategory::NORMAL, ReturnCategory::DEBT])
            ->whereRaw("TIMESTAMPDIFF(DAY, tax_assessments.payment_due_date, CURDATE()) > 30")
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get();

        try {
            foreach ($tax_assessments as $tax_assessment) {
                /**
                 * Mark assessment process as debt if days_passed is less than 30
                 */
                if ($tax_assessment->days_passed < 60) {
                    $tax_assessment->update([
                        'assessment_step' => ApplicationStep::DEBT
                    ]);
                } else {
                    /**
                     * Mark assessment process as overdue if days_passed is greater than 30 days (Meaning 30 days as debt and another 30 days makes it an overdue)
                     */
                    $tax_assessment->update([
                        'assessment_step' => ApplicationStep::OVERDUE
                    ]);
                }
            }

            DB::commit();
            Log::channel('debtCollection')->info("Daily Debt marking for assessment process ended");
        } catch (Exception $e) {
            Log::channel('debtCollection')->info('Daily Debt marking for assessment process ended with error');
            Log::channel('debtCollection')->error($e);
            DB::rollBack();
        }
    }

    public function convertDate($date)
    {
        return Carbon::create($date)->format('d-M-Y');
    }
}
