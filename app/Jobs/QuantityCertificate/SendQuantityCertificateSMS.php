<?php

namespace App\Jobs\QuantityCertificate;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendQuantityCertificateSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $certificate;

    const SERVICE = 'quantity-certificate-generated';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->certificate->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your Petroleum certificate with number {$this->certificate->certificate_no} has been generated, please login into the system to verify Quantity of Certificate product, further guidelines have been sent to your email";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
