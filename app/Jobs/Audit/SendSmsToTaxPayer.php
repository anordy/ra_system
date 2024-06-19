<?php

namespace App\Jobs\Audit;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsToTaxPayer implements ShouldQueue
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
     * @return void
     */
    public function handle()
    {
        //
        $sms_controller = new SMSController;
        $send_to = $this->payload->mobile;
        $sendToName = $this->payload->first_name;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Hello {{ $sendToName }}, Your Business has been selected to be audited, two weeks before auditing you will be notified and specified the exact date.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
