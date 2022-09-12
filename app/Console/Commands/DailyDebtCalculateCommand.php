<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Models\Debts\Debt;
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
        $this->returnsDebts($financialMonth);
        Log::channel('debtCollection')->info('Daily Debt Marking process ended');
    }

    protected function returnsDebts($financialMonth)
    {
            Log::channel('debtCollection')->info("Daily Debt collection for financial month " . $financialMonth->name . " with due date " . $financialMonth->due_date . " process started");
            DB::beginTransaction();

            $tax_returns = TaxReturn::where('return_category', ReturnCategory::NORMAL)
                ->whereRaw("TIMESTAMPDIFF(DAY, tax_returns.curr_filing_due_date, CURDATE()) < 30")
                ->get();

            try {
                foreach ($tax_returns as $tax_return) {

                    $curr_filing_due_date = Carbon::create($tax_return->curr_filing_due_date)->addDays(30);
                    $curr_financial_month_due_date = Carbon::create($financialMonth->due_date);

                    if ($curr_financial_month_due_date->greaterThan($curr_filing_due_date)) {
                        $tax_return->update([
                            'return_category' => 'debt'
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

    protected function assessmentDebt($financialMonth)
    {
        $returnModels = [
            TaxAssessment::class,
        ];

        foreach ($returnModels as $model) {
            Log::channel('debtCollection')->info("Daily Debt collection for assessment model " . strval($model) . " for financial month " . $financialMonth->id . " with due date " . $financialMonth->due_date . " process started");
            DB::beginTransaction();
            try {

                $data = $model::select(
                    'id as debt_id',
                    'location_id as business_location_id',
                    'business_id',
                    'currency',
                    'tax_type_id',
                    'principal_amount',
                    'principal_amount as original_principal_amount',
                    'total_amount as original_total_amount',
                    'total_amount',
                    'total_amount as outstanding_amount',
                    'penalty_amount as penalty',
                    'penalty_amount as original_penalty',
                    'interest_amount as interest',
                    'interest_amount as original_interest',
                    'created_at as submitted_at',
                    'payment_due_date as last_due_date',
                    'payment_due_date as curr_due_date'
                )
                    ->doesntHave('payments')
                    ->whereNotIn('status', ['complete', 'paid-by-debt'])
                    ->orWhere('payment_due_date', '<', $financialMonth->due_date)
                    ->get();


                $data = $data->map(function ($return) {
                    $return->debt_type = TaxAssessment::class;
                    $return->origin = 'job';
                    $return->logged_date = Carbon::now()->toDateTimeString();
                    return $return;
                });


                $dataToInsert = $data->toArray();

                Debt::upsert($dataToInsert, ['debt_id', 'dept_type']);
                DB::commit();
                Log::channel('debtCollection')->info("Daily Debt collection for model " . strval($model) . " for financial month " . $financialMonth->id . " with due date " . $financialMonth->due_date . " process ended");
            } catch (Exception $e) {
                Log::channel('debtCollection')->info('Daily Debt calculation process ended with error');
                Log::channel('debtCollection')->error($e);
                DB::rollBack();
            }
        }
    }

    public function convertDate($date)
    {
        return Carbon::create($date)->format('d-M-Y');
    }

}
