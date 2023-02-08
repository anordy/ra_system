<?php

namespace App\Jobs;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendKYCRegistrationSMS implements ShouldQueue
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
        $message = "Your application has been accepted, please visit ZRA office for biometric registration (fingerprint) before {$this->kyc->created_at->addMonth()->toFormattedDateString()}";
        $source = config('modulesconfig.smsheader');
        $sms_controller->sendSMS($this->kyc->mobile, $source, $message);
    }
}
