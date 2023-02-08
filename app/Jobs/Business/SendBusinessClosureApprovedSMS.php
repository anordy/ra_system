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

class SendBusinessClosureApprovedSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $closure;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->closure->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "ZRA inform that, your ZRA temporary business closure for {$this->closure->business->name} has been approved. You are required/obliged to submit NIL return to ZRA within the closure period.";

        if ($this->closure->location) {
            $customer_message = "ZRA inform that, your ZRA temporary business closure for {$this->closure->business->name}, {$this->closure->location->name} has been approved. You are required/obliged to submit NIL return to ZRA within the closure period.";
        }

        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
