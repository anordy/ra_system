<?php

namespace App\Jobs\Business;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBusinessClosureRejectedSMS implements ShouldQueue
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
        $customer_message = "Your ZRA business closure for {$this->closure->business->name} has been rejected. Please login into your account for more details.";

        if ($this->closure->location) {
            $customer_message = "Your ZRA business closure for {$this->closure->business->name}, {$this->closure->location->name} has been rejected. Please login into your account for more details.";
        }

        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
