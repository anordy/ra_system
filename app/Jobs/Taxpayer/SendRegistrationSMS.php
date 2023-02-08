<?php

namespace App\Jobs\Taxpayer;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRegistrationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mobile, $code, $reference_no;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile, $reference_no, $code)
    {
        $this->mobile = $mobile;
        $this->reference_no = $reference_no;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Welcome to ZRA, please use the following details to access your account, Reference no: {$this->reference_no} Password: {$this->code}";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
