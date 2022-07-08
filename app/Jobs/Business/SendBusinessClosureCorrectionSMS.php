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

class SendBusinessClosureCorrectionSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $business;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your ZRB business registration for ". strtoupper($this->business->name) ." requires additional corrections. Please login into your account for more details.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
