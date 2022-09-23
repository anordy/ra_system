<?php

namespace App\Jobs\Business;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBusinessDeregisterCorrectionSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $deregister;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($deregister)
    {
        $this->deregister = $deregister;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->deregister->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');

        $customer_message = "Your ZRB business registration for {$this->deregister->business->name} requires additional corrections. Please login into your account for more details.";

        if ($this->deregister->location) {
            $customer_message = "Your ZRB business registration for {$this->deregister->business->name}, {$this->deregister->location->name} requires additional corrections. Please login into your account for more details.";
        }

        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
