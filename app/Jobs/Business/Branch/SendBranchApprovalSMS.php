<?php

namespace App\Jobs\Business\Branch;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendBranchApprovalSMS implements ShouldQueue
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
        $sms_controller = new SMSController;
        $send_to = $this->payload['branch']->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your ZRB new branch registration with name {$this->payload['branch']->name} for business {$this->payload['branch']->business->name} has been approved.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
