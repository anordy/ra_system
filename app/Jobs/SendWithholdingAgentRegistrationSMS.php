<?php

namespace App\Jobs;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWithholdingAgentRegistrationSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $full_name;
    private $phone_number;
    private $institution_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($full_name, $institution_name, $phone_number)
    {
        $this->full_name = $full_name;
        $this->institution_name = $institution_name;
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
        $message = 'Hello '. $this->full_name .', You are successful registered as a Withholding Agent for '. $this->institution_name . '. Kindly login with your reference number to view the information.';
        $sms_controller->sendSMS($send_to, $source, $message);
    }
}
