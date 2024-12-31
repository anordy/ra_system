<?php

namespace App\Console\Commands\PropertyTax;

use App\Enum\BillStatus;
use App\Jobs\Bill\CancelBill;
use App\Jobs\PropertyTax\GeneratePropertyTaxControlNo;
use App\Models\FinancialYear;
use App\Models\PropertyTax\PropertyPayment;
use App\Traits\PropertyTaxTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PropertyTaxBillPaymentReminder extends Command
{
    use PropertyTaxTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:property-tax-bill-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate property reminder for property tax bill monthly upto 3 months';

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
        Log::channel('property-tax')->info('Sending Property Tax Bill Payment Reminder');
        $this->updateDueDate();
        //$this->calculateInterest();
        Log::channel('property-tax')->info('Completed Sending Property Tax Bill Payment Reminder');
    }

    public function generateBill()
    {
        $payments = PropertyPayment::where('payment_status', '!=', BillStatus::COMPLETE)->get();

        foreach ($payments ?? [] as $payment) {
            $currentPaymentDate = Carbon::parse($payment->curr_payment_date);

            $hasDueDateExpired = Carbon::now()->gt($currentPaymentDate);

            if ($hasDueDateExpired) {
                $newPaymentDate = Carbon::now()->addDays(30);
                $payment->curr_payment_date = $newPaymentDate;
                $payment->save();

                GeneratePropertyTaxControlNo::dispatch($payment);

            }

        }
    }

    public function updateDueDate()
    {
        $currentFinancialYear = FinancialYear::select('id', 'code')
            ->where('code', 2024)
            ->firstOrFail();

        $payments = PropertyPayment::query()
            ->where('financial_year_id', $currentFinancialYear->id)
            ->where('payment_status', '!=', BillStatus::COMPLETE)->get();

        $i = 0;
        foreach ($payments ?? [] as $payment) {
            $i++;
            $currentPaymentDate = Carbon::parse($payment->curr_payment_date);

            $hasDueDateExpired = Carbon::now()->gt($currentPaymentDate);

            if ($hasDueDateExpired) {
                $newPaymentDate = Carbon::now()->endOfYear()->endOfDay();

                $payment->curr_payment_date = $newPaymentDate;
                $payment->payment_date = $newPaymentDate;
                $payment->save();

                GeneratePropertyTaxControlNo::dispatch($payment);

            }

        }

        $this->line('Finished updating due date');
        $this->line($i);

    }

    public function calculateInterest()
    {
        $payments = PropertyPayment::where('payment_status', '!=', BillStatus::COMPLETE)->get();

        foreach ($payments ?? [] as $payment) {
            $currentPaymentDate = Carbon::parse($payment->curr_payment_date);

            if (Carbon::now()->gt($currentPaymentDate)) {
                try {
                    $this->incrementInterest($payment);
                } catch (Exception $exception) {
                    Log::error($exception);
                    throw new Exception($exception);
                }
            }
        }
    }


    protected function incrementInterest($propertyPayment)
    {
        $incrementedPropertyPayment = $this->generateMonthlyInterest($propertyPayment);

        if ($incrementedPropertyPayment && $propertyPayment->latestBill) {
            CancelBill::dispatch($propertyPayment->latestBill, 'Property Tax Interest Increment');
        }

        $propertyPayment = PropertyPayment::find($propertyPayment->id);

        GeneratePropertyTaxControlNo::dispatch($propertyPayment);

    }


}
