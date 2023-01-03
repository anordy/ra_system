<?php

namespace App\Jobs\Business\Taxtype;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\Business\Taxtype\ChangeTaxType;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendTaxTypeSMS implements ShouldQueue
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
        $send_to = $this->payload['tax_change']->business->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');

        $customer_message = "According to your tax type request submission, from {$this->payload['tax_change']->effective_date} you will be changed from {$this->payload['tax_change']->fromTax->name} to {$this->payload['tax_change']->toTax->name}";
        
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
