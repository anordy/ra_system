<?php

namespace App\Console\Commands;

use App\Models\Debts\Debt;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\HotelReturns\HotelReturn;
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
        Log::info('Daily Debt calculation process started');
        /**
         * Get current return month
         * Get current return month due date
         * Select data from return with current due date
         * Insert into debt all unpaid and update status of return to debt
         */
        DB::beginTransaction();
        try {
            $financialYear = FinancialYear::firstWhere('code', date('Y'));
            $month = Carbon::now()->month;
            $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', $month)->first();

            $hoteReturn = HotelReturn::select(
                'id as debt_type_id',
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
                ->where('status', '!=', 'complete')
                ->whereIn('application_status', ['self_assessment', 'adjusted', 'submitted'])
                ->where('filing_due_date', '<', $financialMonth->due_date)
                ->orWhere('payment_due_date', '<', $financialMonth->due_date)
                ->get();


            $data = $hoteReturn->map(function ($return) {
                $return->debt_type = HotelReturn::class;
                $return->origin = 'job';
                $return->logged_date = Carbon::now()->toDateTimeString();
                return $return;
            });

            $dataToInsert = $data->toArray();

            Debt::upsert($dataToInsert, ['debt_type_id', 'dept_type']);
            DB::commit();
            Log::info('Daily Debt calculation process ended');
        } catch (Exception $e) {
            Log::info('Daily Debt calculation process ended with error');
            Log::error($e);
            DB::rollBack();
        }
    }
}
