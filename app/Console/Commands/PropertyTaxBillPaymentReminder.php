<?php

namespace App\Console\Commands;

use App\Enum\BillStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateControlNo;
use App\Jobs\PropertyTax\GeneratePropertyTaxControlNo;
use App\Jobs\PropertyTax\SendPropertyTaxPaymentReminderApprovalSMS;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\PropertyTax\Property;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\PropertyTax\PropertyPaymentReminder;
use App\Models\Returns\TaxReturn;
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
        $this->sendReminders();
        Log::channel('property-tax')->info('Completed Sending Property Tax Bill Payment Reminder');
    }

    public function sendReminders()
    {
        $payments = PropertyPayment::where('payment_status', '!=', BillStatus::COMPLETE)->get();


        if ($payments) {
            foreach ($payments as $payment) {
                $currentPaymentDate = Carbon::parse($payment->curr_payment_date);
                $diffInDays = Carbon::now()->diffInDays($currentPaymentDate);

                try {
                    if (count($payment->reminders) === 0 && $diffInDays === 30) {
                        $this->sendPaymentReminder($payment);
                    } else if (count($payment->reminders) === 1 && $diffInDays === 60) {
                        $this->sendPaymentReminder($payment);
                    } else if (count($payment->reminders) === 2 && $diffInDays === 90) {
                        $this->sendPaymentReminder($payment);
                    } else {
                        // Don't send anything, we give up, start calculating interest on each month
                        $this->incrementInterest($payment);
                    }
                } catch (Exception $exception) {
                    Log::error($exception);
                    throw new Exception($exception);
                }

            }
        }
    }

    protected function sendPaymentReminder($propertyPayment)
    {
        $reminder = PropertyPaymentReminder::create([
            'property_payment_id' => $propertyPayment->id
        ]);

        if ($reminder) {
            // Dispatch SMS for payment reminder
            SendPropertyTaxPaymentReminderApprovalSMS::dispatch($propertyPayment->payment);
        }
    }

    protected function incrementInterest($propertyPayment) {

        $incrementedPropertyPayment = $this->generateMonthlyInterest($propertyPayment);

        if ($incrementedPropertyPayment && $propertyPayment->latestBill) {
            CancelBill::dispatch($propertyPayment->latestBill, 'Property Tax Interest Increment');
        }

         GeneratePropertyTaxControlNo::dispatch($propertyPayment);

    }


}
