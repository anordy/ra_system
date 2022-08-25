<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Debts\Debt;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAssessments\TaxAssessment;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyDebtCalculateCommand extends Command
{
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
        Log::channel('debtCollection')->info('Daily Debt collection process started');
        $financialYear = FinancialYear::firstWhere('code', date('Y'));
        $month = Carbon::now()->month;
        $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
            ->where('number', $month)->first();

        $this->returnsDebts($financialMonth);
        $this->assessmentDebt($financialMonth);
        Log::channel('debtCollection')->info('Daily Debt collection ended');
    }

    protected function returnsDebts($financialMonth)
    {
        $returnModels = [
            HotelReturn::class,
            StampDutyReturn::class,
            MnoReturn::class,
            VatReturn::class,
            MmTransferReturn::class,
            PetroleumReturn::class,
            PortReturn::class,
            EmTransactionReturn::class,
            BfoReturn::class,
            LumpSumReturn::class
        ];

        foreach ($returnModels as $model) {
            Log::channel('debtCollection')->info("Daily Debt collection for return model " . strval($model) . " for financial month " . $financialMonth->id . " with due date " . $financialMonth->due_date . " process started");
            DB::beginTransaction();
            try {

                $hoteReturn = $model::select(
                    'id as debt_id',
                    'business_location_id',
                    'business_id',
                    'currency',
                    'tax_type_id',
                    'total_amount_due as principal_amount',
                    'total_amount_due as original_principal_amount',
                    'total_amount_due_with_penalties as original_total_amount',
                    'total_amount_due_with_penalties as total_amount',
                    'total_amount_due_with_penalties as outstanding_amount',
                    'penalty',
                    'penalty as original_penalty',
                    'interest',
                    'interest as original_interest',
                    'submitted_at',
                    'filing_due_date',
                    'payment_due_date as last_due_date',
                    'payment_due_date as curr_due_date'
                )
                    ->doesntHave('payments')
                    ->whereNotIn('status', ['complete', 'paid-by-debt'])
                    ->where('filing_due_date', '<', $financialMonth->due_date)
                    ->get();

                $data = $hoteReturn->map(function ($return) use ($model) {
                    $return->debt_type = $model;
                    $return->origin = 'job';
                    $return->logged_date = Carbon::now()->toDateTimeString();
                    return $return;
                });

                $dataToInsert = $data->toArray();

                Debt::upsert($dataToInsert, ['debt_id', 'dept_type']);
                DB::commit();
                Log::channel('debtCollection')->info("Daily Debt collection for return model " . strval($model) . " for financial month " . $financialMonth->id . " with due date " . $financialMonth->due_date . " process ended");
            } catch (Exception $e) {
                Log::channel('debtCollection')->info('Daily Debt calculation process ended with error');
                Log::channel('debtCollection')->error($e);
                DB::rollBack();
            }
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

}
