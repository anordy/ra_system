<?php

namespace App\Jobs\Audit;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationLetterSmsToTaxPayer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * This method sends a notification SMS to the taxpayer regarding the tax audit process.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->payload['mobile']; // The mobile number of the taxpayer
        $sendToName = $this->payload['first_name']; // The first name of the taxpayer
        $source = config('modulesconfig.smsheader'); // The source of the SMS header
        $customer_message = "Hello $sendToName, your business has been selected for a tax audit. Please note that all relevant documents must be uploaded within seven days for the audit process. Log in to CRDB system to submit documents. Thank you."; // The message to be sent to the taxpayer
        $sms_controller->sendSMS($send_to, $source, $customer_message); // Send the SMS to the taxpayer
    }
}
