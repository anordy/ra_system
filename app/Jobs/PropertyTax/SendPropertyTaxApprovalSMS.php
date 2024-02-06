<?php

namespace App\Jobs\PropertyTax;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPropertyTaxApprovalSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $property;

    const SERVICE = 'property-tax-approved';

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
        $customer_message = "You have successful registered your property with unit registration number {$this->property->urn} for property tax. Your will receive payment control number shortly.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
