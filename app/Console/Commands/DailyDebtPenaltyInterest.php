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

    public function getDebtAfterDueDate()
    {
        $now = Carbon::now();
        $debts = Debt::selectRaw('debts.*, TIMESTAMPDIFF(month, filing_due_date, NOW()) as periods, TIMESTAMPDIFF(month, curr_due_date, NOW()) as months')
            ->where('curr_due_date', '<', $now)
            ->whereNotIn('status', ['complete', 'paid-by-debt'])
            ->get();
     
        dd($debts);

        if ($debts) {
            foreach ($debts as $key => $debt) {
                $dueDate = Carbon::parse($debt->curr_due_date);
                if ($debt->period > 0) {
                    $penaltyReturn = PenaltyForDebt::getTotalPenalties($debt->id, $dueDate, $debt->outstanding_amount, $debt->period);
                    // Cancel return bill if it exists

                    if ($debt->debt->bill) {
                        CancelBill::dispatch($debt->debt->bill, 'Debt Penalty Increment')->delay($now->addSeconds(2));
                    }

                    // Cancel debt bill if exists otherwise generate control no.
                    if ($debt->bill) {
                        CancelBill::dispatch($debt->bill, 'Debt Penalty Increment')->delay($now->addSeconds(5));
                        GenerateControlNo::dispatch($debt)->delay($now->addSeconds(10));
                    } else {
                        GenerateControlNo::dispatch($debt)->delay($now->addSeconds(10));
                    }

                    $debtUpdate = Debt::find($debt->id);

                    
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
}
