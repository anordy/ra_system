<?php

namespace App\Jobs\Branch;

use App\Http\Controllers\v1\SMSController;
use App\Models\BusinessLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBranchRegisteredSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'branch-registration';

    private $location;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BusinessLocation $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->location->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your ZRA branch registration for ". strtoupper($this->location->name) ." was received successfully. We will notify you once its approved. This approval process may take up to approximately two (2) working days";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
