<?php

namespace App\Jobs\Installment;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInstallmentRejectedSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'installment-rejected';

    private $installment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($installment)
    {
        $this->installment = $installment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->installment->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "ZRA inform you that {$this->installment->taxtype->name} debt $this->installment request for {$this->installment->business->name} at {$this->installment->location->name} has been rejected.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
