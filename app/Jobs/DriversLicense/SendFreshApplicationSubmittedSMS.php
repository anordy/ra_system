<?php

namespace App\Jobs\DriversLicense;

use App\Http\Controllers\v1\SMSController;
use App\Models\DlLicenseApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFreshApplicationSubmittedSMS implements ShouldQueue
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
        $application = DlLicenseApplication::query()->find($this->payload);
        if($application){
            $sms_controller = new SMSController;
            $source = config('modulesconfig.smsheader');
            $customer_message = "Your Competence ID is {$application->competence_number}; Please visit ZRB for taking picture and collecting driving license card";
            $sms_controller->sendSMS($application->taxpayer->mobile, $source, $customer_message);
        }
    }
}
