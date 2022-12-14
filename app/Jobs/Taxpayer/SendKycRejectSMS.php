<?php

namespace App\Jobs\Taxpayer;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendKycRejectSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $kyc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kyc)
    {
        $this->kyc = $kyc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->kyc->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Hello {$this->kyc->first_name}, Your application for zrb reference number has been rejected please re-apply again. Rejection comments: {$this->kyc->comments}";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
