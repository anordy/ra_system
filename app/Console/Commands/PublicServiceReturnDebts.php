<?php

namespace App\Console\Commands;

use App\Enum\Currencies;
use App\Enum\ReturnCategory;
use App\Jobs\Bill\CancelBill;
use App\Jobs\PublicService\GeneratePublicServiceControlNo;
use App\Models\Currency;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\PublicService\PublicServiceInterest;
use App\Models\PublicService\PublicServiceReturn;
use App\Models\SystemSetting;
use App\Traits\PenaltyTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublicServiceReturnDebts extends Command
{
    use PenaltyTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ps:return-debts';

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
        // get debts.
        $returns = PublicServiceReturn::query()
            ->whereNull('paid_at')
            ->where('payment_date', '<', Carbon::now())
            ->get();

        foreach ($returns as $return) {
            $return = $this->updateWithInterest($return);

            if ($return){
                if ($return && $return->latestBill) {
                    CancelBill::dispatch($return->latestBill, 'Public service return payment interest increment');
                }

                $return = PublicServiceReturn::find($return->id);
                GeneratePublicServiceControlNo::dispatch($return);
            }
        }

        return 0;
    }

    public function updateWithInterest($return){

        // Check if payment due date diff in days btn now is 30 days
        $currentPaymentDate = Carbon::parse($return->curr_payment_date);
        $paymentDate = Carbon::parse($return->payment_date);
        $currentDate = Carbon::now();

        $period = floor($currentDate->floatDiffInMonths($paymentDate) ?: 1);
        $principalTaxAmount = $return->amount;

        // TODO: Add condition to calculate interest whenever viable but not everyday
        if ($currentPaymentDate->greaterThan($currentDate) && floor($paymentDate->diffInDays($currentDate) / 30)){
            try {
                DB::beginTransaction();
                for ($i = 1; $i <= $period; $i++) {
                    // Calculate interest and add it to current payment with new total amount
                    $interest = roundOff($this->calculatePaymentInterest($principalTaxAmount, $i), Currencies::TZS);
                    $totalAmount = $return->principal + $interest;
                    $newPaymentDate = $currentPaymentDate->addDays(30);
                    $currFinancialMonth = $this->getFinancialMonthFromDate($newPaymentDate);

                    PublicServiceInterest::create([
                        'public_service_return_id' => $return->id,
                        'financial_year_id' => $currFinancialMonth->year->id,
                        'financial_month_id' => $currFinancialMonth->id,
                        'principal' => $principalTaxAmount,
                        'interest' => $interest,
                        'amount' => $totalAmount,
                        'payment_date' => $newPaymentDate,
                        'period' => $i,
                    ]);

                    $principalTaxAmount = $totalAmount;
                }

                // Record interest in payment interests table
                $return->interest = $interest;
                $return->amount = $totalAmount;
                $return->curr_payment_date = $newPaymentDate;
                $return->return_category = ReturnCategory::DEBT;
                $return->save();

                DB::commit();
                return $return;
            } catch (\Exception $exception) {
                DB::rollBack();
                $this->error($exception);
                throw $exception;
            }
        }

        return false;
    }

    // Check
    private function calculatePaymentInterest($taxAmount, $period){
        $rate = SystemSetting::where('code', SystemSetting::PROPERTY_TAX_INTEREST_RATE)->firstOrFail()->value;
        $numberOfTimesInterestIsCompounded = SystemSetting::where('code', SystemSetting::NUMBER_OF_TIMES_INTEREST_IS_COMPOUNDED_IN_PROPERTY_TAX_PER_YEAR)->firstOrFail()->value;
        return $taxAmount * pow((1 + ($rate/$numberOfTimesInterestIsCompounded)), ($period)) - $taxAmount;
    }
}
