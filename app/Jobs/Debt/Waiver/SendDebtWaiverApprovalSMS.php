<?php

namespace App\Jobs\Debt\Waiver;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDebtWaiverApprovalSMS implements ShouldQueue
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
        $send_to = $this->payload['debt']->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "ZRB inform you that {$this->payload['debt']->taxtype->name} debt waiver for {$this->payload['debt']->business->name} at {$this->payload['debt']->location->name} debt has been approved. You will receive a new control number with adjusted amount within a few minutes.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
