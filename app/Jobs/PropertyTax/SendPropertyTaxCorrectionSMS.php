<?php

namespace App\Jobs\PropertyTax;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPropertyTaxCorrectionSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $property;

    const SERVICE = 'property-tax-correction';

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
        $send_to = $this->property->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your property registration for {$this->property->name} requires correction. Please login to your account to update your registration.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
