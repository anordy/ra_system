<?php

namespace App\Console\Commands;

use App\Models\DateConfiguration;
use App\Models\Debts\Debt;
use App\Traits\PenaltyForDebt;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyDebtPenaltyInterest extends Command
{
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
        
        $this->getDebtAfterDueDate();
        
        Log::channel('debtCollection')->info('Daily Debt penalties and interest collection and calculations ended');
    }

    public function getDebtAfterDueDate(){
        $debts = Debt::where('curr_due_date', '<', Carbon::now())->get();
        dd($debts);

        // dd(count($debts));
        foreach ($debts as $keyDebt => $debt) {
            $dueDate = Carbon::parse($debt->curr_due_date);
            $dateDiff = $dueDate->diffInDays(Carbon::now());
            $validDays = DateConfiguration::where('code', 'validDays')->value('value');
            // dd($dateDiff);
            $mod = $dateDiff% $validDays;
            $times = ($dateDiff-$mod)/$validDays;

            dd($dueDate->month());
            
            PenaltyForDebt::getTotalPenalties($dueDate->month(), $dueDate, $debt->total);

            for ($i=0; $i < $times; $i++) { 
                # code...
            }
        }
    }
}
