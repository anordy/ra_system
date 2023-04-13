<?php

namespace App\Jobs\Vetting;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\v1\SMSController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendToCorrectionReturnSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'vetted-to-correction';

    public $tax_return;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tax_return)
    {
        $this->tax_return = $tax_return;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->tax_return->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "Your {$this->tax_return->taxtype->name} for filing month of {$this->tax_return->financialMonth->name} / {$this->tax_return->financialMonth->year->code} for {$this->tax_return->business->name} {$this->tax_return->location->name} have been rejected for correction. Please login to make required corrections";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
