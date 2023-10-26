<?php

namespace App\Jobs\PropertyTax;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPropertyTaxPaymentReminderApprovalSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $property;

    const SERVICE = 'property-tax-payment-reminder';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->property->responsible->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Hello {$this->property->taxpayer->fullname()}, This is a reminder for your property tax {$this->property->urn} payment.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
