<?php

namespace App\Jobs\PropertyTax;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPaymentExtensionApprovalSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $extensionPayload;

    const SERVICE = 'property-tax-payment-extension-approved';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extensionPayload)
    {
        $this->extensionPayload = $extensionPayload;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        if ($this->extensionPayload['phone']){
            $sms_controller = new SMSController;
            $send_to = $this->extensionPayload['phone'];
            $source = config('modulesconfig.smsheader');
            $customer_message = "Hello ".$this->extensionPayload['name'].", ".$this->extensionPayload['message'];
            $sms_controller->sendSMS($send_to, $source, $customer_message);
        }
    }
}
