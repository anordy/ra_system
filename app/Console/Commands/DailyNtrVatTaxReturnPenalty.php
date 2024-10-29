<?php

namespace App\Console\Commands;

use App\Enum\GeneralConstant;
use App\Jobs\Bill\CancelBill;
use App\Jobs\NonTaxResident\GenerateNtrControlNo;
use App\Models\Debts\DebtPenalty;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\Ntr\Returns\NtrVatReturn;
use App\Models\PenaltyRate;
use App\Models\Returns\ReturnStatus;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyForDebt;
use App\Traits\PenaltyTrait;
use App\Traits\TaxpayerLedgerTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyNtrVatTaxReturnPenalty extends Command
{
    use PaymentsTrait, PenaltyTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:debt-ntr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Non Tax Resident Vat Return Debt penalties and interest collection and calculations';

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
        Log::channel('dailyJobs')->info('Daily Debt penalties and interest collection and calculations started');
        $this->generateReturnsDebtPenalty();
        Log::channel('dailyJobs')->info('Daily Debt penalties and interest collection and calculations ended');
    }

    public function generateReturnsDebtPenalty()
    {
        $returns = NtrVatReturn::query()
            ->select('id', 'business_id', 'filed_by_type', 'filed_by_id', 'currency', 'tax_type_id', 'financial_year_id', 'financial_month_id', 'edited_count', 'status', 'payment_status', 'return_category', 'principal', 'penalty', 'interest', 'total_amount_due', 'total_amount_due_with_penalties', 'paid_at', 'filing_due_date', 'payment_due_date', 'curr_payment_due_date', 'created_at')
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE, ReturnStatus::NILL])
            ->whereDate('curr_payment_due_date', '>', now())
            ->get();

        foreach ($returns as $return) {
            try {
                DB::beginTransaction();

                self::generateReturnsPenalty($return);

                DB::commit();

                // Cancel previous latest bill if exists
                if ($return->latestBill) {
                    CancelBill::dispatch($return->latestBill, 'Debt Penalty Increment');
                }
                $return = NtrVatReturn::find($return->id, ['id', 'business_id', 'filed_by_type', 'filed_by_id', 'currency', 'tax_type_id', 'financial_year_id', 'financial_month_id', 'edited_count', 'status', 'payment_status', 'return_category', 'principal', 'penalty', 'interest', 'total_amount_due', 'total_amount_due_with_penalties', 'paid_at', 'filing_due_date', 'payment_due_date', 'curr_payment_due_date', 'created_at']);
                GenerateNtrControlNo::dispatch($return);
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('DAILY-NTR-VAT-RETURN-PENALTY-INCREMENT', [$e]);
            }
        }
    }

    public static function generateReturnsPenalty($return)
    {
        // Join return penalties & Debt penalties
        $return->penalties = $return->penalties->concat($return->penalties)->sortBy('tax_amount');

        if (count($return->penalties) > 0) {
            $outstanding_amount = $return->penalties->last()->penalty_amount;
        } else {
            $outstanding_amount = $return->principal;
        }

        $curr_payment_due_date = Carbon::create($return->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

        if (!$year) {
            throw new \Exception("JOB FAILED TO RUN, NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
        }

        $interestRate = InterestRate::where('year', $year->code)->first();

        if (!$interestRate) {
            throw new \Exception("JOB FAILED TO RUN, NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', PenaltyRate::LATE_PAYMENT_AFTER)
            ->first();

        if (!$latePaymentAfterRate) {
            throw new \Exception("JOB FAILED TO RUN, NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $period = self::getIterations($return->month);
        $period = abs(round($period));

        if ($period <= 0) {
            $period = 1;
        }

        $outstanding_amount = roundOff($outstanding_amount, $return->currency);

        $penaltableAmount = $outstanding_amount;

        $latePaymentAmount = 0;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = roundOff(self::calculateInterest($penaltableAmount, $interestRate->rate, $period), $return->currency);
        $penaltableAmount = roundOff($penaltableAmount + $interestAmount, $return->currency);

        $start_date = Carbon::create($return->curr_payment_due_date)->addDay()->startOfDay();
        $end_date = Carbon::create($return->curr_payment_due_date)->addDays(30)->endOfDay();

        try {
            $previous_debt_penalty_id = $return->latestPenalty->id ?? null;

            $debtPenalty = DebtPenalty::create([
                'debt_id' => $return->id,
                'debt_type' => NtrVatReturn::class,
                'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
                'tax_amount' => $outstanding_amount,
                'late_filing' => GeneralConstant::ZERO_INT,
                'late_payment' => GeneralConstant::ZERO_INT,
                'rate_percentage' => $interestRate->rate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'start_date' => $start_date->startOfDay(),
                'end_date' => $end_date,
                'currency' => $return->currency,
                'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($return->currency)
            ]);

            $return->principal = roundOff($return->principal, $return->currency);
            $return->penalty = roundOff($return->penalty + $debtPenalty->late_payment, $return->currency);
            $return->interest = roundOff($return->interest + $debtPenalty->rate_amount, $return->currency);
            $return->curr_payment_due_date = $end_date;
            $return->total_amount_due = round($penaltableAmount, 2);
            $return->total_amount_due_with_penalties = round($penaltableAmount, 2);
            $return->save();

//            TaxpayerLedgerTrait::recordLedgerDebt(TaxReturn::class, $return->id, $return->interest, $return->penalty, $return->total_amount);

            (new PenaltyForDebt)->sign($return);

            self::recordReturnDebtState($return, $previous_debt_penalty_id, $debtPenalty->id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function getIterations($financialMonth)
    {
        try {
            $currentFinancialMonth = $this->getCurrentFinancialMonth();

            $diffInMonths = FinancialMonth::query()
                ->select(['id'])
                ->whereBetween('due_date', [$financialMonth->due_date, $currentFinancialMonth->due_date])
                ->count();

            $penaltyIterations = $diffInMonths - 1;

            if (Carbon::today() < $currentFinancialMonth->due_date) {
                $penaltyIterations = $penaltyIterations - 1;
            }

            return $penaltyIterations;

        } catch (Exception $exception) {
            Log::error('TRAITS-PENALTY-TRAIT-GET-ITERATIONS', [$exception]);
            throw $exception;
        }
    }



}
