<?php

namespace App\Console\Commands;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\HotelReturns\HotelReturn;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
        /**
         * Get current return month
         * Get current return month due date
         * Select data from return with current due date
         * Insert into debt all unpaid and update status of return to debt
         */
        $financialYear = FinancialYear::firstWhere('code' ,date('Y'));
        $month = Carbon::now()->month;
        $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
            ->where('number', $month)->first();

         $hoteReturn = HotelReturn::where('status', '!=', 'complete')
            ->where('financial_month_id', $financialMonth->id)
            ->where('submitted_at', '>', $financialMonth->due_date)
            ->get();

        dd($hoteReturn);

        

        Log::info('Calculate penalty');
    }
}
