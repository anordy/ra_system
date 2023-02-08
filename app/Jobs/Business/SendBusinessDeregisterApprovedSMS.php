<?php

namespace App\Jobs\Business;

use App\Http\Controllers\v1\SMSController;
use App\Models\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBusinessDeregisterApprovedSMS implements ShouldQueue
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
        $customer_message = "ZRA inform that, you have de-registered {$this->deregister->business->name}
        and no longer an active taxpayer for this business.";

        if ($this->deregister->location) {
            $customer_message = "ZRA inform that, you have de-registered {$this->deregister->business->name}, {$this->deregister->location->name}
            and no longer an active taxpayer for this business.";
        }

        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
