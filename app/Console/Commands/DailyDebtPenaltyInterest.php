<?php

namespace App\Console\Commands;

use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateControlNo;
use App\Models\DateConfiguration;
use App\Models\Debts\Debt;
use App\Models\Returns\ReturnStatus;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyForDebt;
use Carbon\Carbon;
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
        DB::beginTransaction();
        $this->getDebtAfterDueDate();
        DB::commit();
        Log::channel('debtCollection')->info('Daily Debt penalties and interest collection and calculations ended');
    }

    public function getDebtAfterDueDate(){
        $now = Carbon::now();
        $debts = Debt::where('curr_due_date', '<', $now)
                        ->whereNotIn('status', ['complete', 'paid-by-debt'])
                        ->get();

        if ($debts) {
            
            foreach ($debts as $debt) {
                $dueDate = Carbon::parse($debt->curr_due_date);
                $dateDiff = $dueDate->diffInDays(Carbon::now());
                $validDays = DateConfiguration::where('code', 'validMonthDays')->value('value');
                $mod = $dateDiff% $validDays;
                $penaltyIterations = ($dateDiff-$mod)/$validDays;
                
                $penaltyReturn = PenaltyForDebt::getTotalPenalties($debt->id, $dueDate, $debt->outstanding_amount, $penaltyIterations);
                
                $debtUpdate = Debt::find($debt->id);

                if ($debt->bill) {
                    CancelBill::dispatch($debt->bill, 'Penalty Increment')->delay($now->addSeconds(10));
                    GenerateControlNo::dispatch($debt)->delay($now->addSeconds(10));
                } else {
                    GenerateControlNo::dispatch($debt)->delay($now->addSeconds(10));
                }

                $debtUpdate->penalty = $debt->debtPenalties->sum('late_payment');
                $debtUpdate->interest = $debt->debtPenalties->sum('rate_amount');
                $debtUpdate->curr_due_date = $penaltyReturn[0];
                $debtUpdate->total_amount = $penaltyReturn[1];
                $debtUpdate->outstanding_amount = $penaltyReturn[1];
                $debtUpdate->save();
    
            }

        }
        
    }
}
