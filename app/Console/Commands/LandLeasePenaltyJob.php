<?php

namespace App\Console\Commands;

use App\Enum\LeaseStatus;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateControlNo;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\LandLease;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\LeasePaymentPenalty;
use App\Models\PenaltyRate;
use App\Traits\LandLeaseTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandLeasePenaltyJob extends Command
{
    use LandLeaseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landlease:penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Log::channel('dailyJobs')->info('Daily Land Lease Penalty Calculation started');
        DB::beginTransaction();
        $this->penaltyCalculation();
        DB::commit();
        Log::channel('dailyJobs')->info('Daily Land Lease Penalty Calculation ended');
    }

    public function penaltyCalculation()
    {
        $statues = [LeaseStatus::CN_GENERATED, LeaseStatus::CN_GENERATING, LeaseStatus::CN_GENERATION_FAILED, LeaseStatus::DEBT, LeaseStatus::PAID_PARTIALLY, LeaseStatus::PENDING];

        $leasePayments = LeasePayment::whereIn('status', $statues)
            ->where('due_date', '<', Carbon::now())
            ->get();
        foreach ($leasePayments as $key => $leasePayment) {

            $paymentFinancialMonthDueDate = $this->getLeasePaymentFinancialMonth($leasePayment->financial_year_id, $leasePayment->landLease->payment_month);

            if($leasePayment->debt){
                $paymentFinancialMonthDueDate = $leasePayment->debt->curr_due_date;
            }
            
            $penaltyIteration = Carbon::now()->month - Carbon::parse($paymentFinancialMonthDueDate)->month;
            $due_date = Carbon::parse($paymentFinancialMonthDueDate)->endOfMonth();
                
            if ($penaltyIteration > 0) {  
                $total_amount_with_penalties = $this->calculateLeasePenalties($leasePayment, $paymentFinancialMonthDueDate, $penaltyIteration);
                
                $updateLeasePayment = LeasePayment::find($leasePayment->id);
                if(is_null($updateLeasePayment)){
                    abort(404);
                }
                $updateLeasePayment->penalty = $leasePayment->totalPenalties();
                $updateLeasePayment->total_amount_with_penalties = $total_amount_with_penalties;
                $updateLeasePayment->outstanding_amount = $total_amount_with_penalties;
                $updateLeasePayment->status = LeaseStatus::DEBT;
                $updateLeasePayment->save();

                if($leasePayment->debt){

                    $updateDebt = LandLeaseDebt::find($leasePayment->debt->id);
                    if(is_null($updateDebt)){
                        abort(404);
                    }
                    $updateDebt->penalty = $updateLeasePayment->penalty;
                    $updateDebt->total_amount = $updateLeasePayment->total_amount_with_penalties;
                    $updateDebt->outstanding_amount = $updateLeasePayment->outstanding_amount;
                    $updateDebt->last_due_date = $due_date;
                    $updateDebt->curr_due_date = Carbon::now()->endOfMonth();
                    $updateDebt->save();

                } else {

                    LandLeaseDebt::create([
                        'lease_payment_id' => $leasePayment->id,
                        'business_location_id' => $leasePayment->landLease->business_location_id,
                        'original_total_amount' => $leasePayment->total_amount,
                        'penalty' => $updateLeasePayment->penalty,
                        'total_amount' => $updateLeasePayment->total_amount_with_penalties,
                        'outstanding_amount' => $updateLeasePayment->outstanding_amount,
                        'status' => LeaseStatus::PENDING,
                        'last_due_date' => $due_date,
                        'curr_due_date' => Carbon::now()->endOfMonth(),
                    ]);
                    
                }

                //If bill exist
                if ($leasePayment->bill) {
                    CancelBill::dispatch($leasePayment->bill, 'Debt Penalty Increment')->delay(Carbon::now()->addSeconds(2));
                }
            }
        }
    }

    private function calculateLeasePenalties($leasePayment, $paymentFinancialMonthDueDate, $penaltyIteration)
    {
        $currentFinancialYearId = FinancialYear::where('code', Carbon::now()->year)->firstOrFail()->id;
        $penaltyRate = PenaltyRate::where('financial_year_id', $currentFinancialYearId)
            ->where('code', 'LeasePenaltyRate')
            ->firstOrFail()->rate;

        $wholeTotalAmount = 0;
        for ($i = 1; $i <= $penaltyIteration; $i++) {
            $rentRemain = $i == 1 ? $leasePayment->total_amount : $wholeTotalAmount;

            $penaltyAmount = round($rentRemain * $penaltyRate, 2);
            $totalAmount = round($rentRemain + $penaltyAmount, 2);

            LeasePaymentPenalty::create([
                'lease_payment_id' => $leasePayment->id,
                'tax_amount' => $rentRemain,
                'rate_percentage' => $penaltyRate,
                'penalty_amount' => $penaltyAmount,
                'total_amount' => $totalAmount,
                'start_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonthDueDate, $i)[0],
                'end_date' => $this->getFirstLastDateOfMonth($paymentFinancialMonthDueDate, $i)[1],
            ]);

            $wholeTotalAmount = $totalAmount;
        }

        return $wholeTotalAmount;
    }

    public function getFirstLastDateOfMonth($due_date, $i)
    {
        $currentMonth = $due_date->addMonths($i);
        $start_date = clone $currentMonth->startOfMonth();
        $end_date = clone $currentMonth->endOfMonth();
        return [$start_date, $end_date];
    }

    // public function getLeasePaymentFinancialMonth($financial_year_id, $payment_month)
    // {
    //     $paymentFinancialMonth = FinancialMonth::select('id', 'name', 'due_date')
    //         ->where('financial_year_id', $financial_year_id)
    //         ->where('name', $payment_month)
    //         ->firstOrFail();
    //     return $paymentFinancialMonth->due_date;
    // }
}
