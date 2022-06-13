<?php

namespace App\Jobs;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendOTPSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $first_name;
    private $phone_number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code, $first_name, $phone_number)
    {
        $this->code = $code;
        $this->first_name = $first_name;
        $this->phone_number = $phone_number;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->phone_number;
        $source = 'UmojaMobile';
        $customer_message = 'Tumia code ' . $this->code . ' kwa ajili ya uthibitisho ya mfumo wa malipo ya bandari';
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
