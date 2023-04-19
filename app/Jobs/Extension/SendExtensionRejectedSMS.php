<?php

namespace App\Jobs\Extension;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExtensionRejectedSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'extension-rejected';

    private $extension;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->extension->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "ZRA inform you that {$this->extension->taxtype->name} debt extension request for {$this->extension->business->name} at {$this->extension->location->name} has been rejected.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
