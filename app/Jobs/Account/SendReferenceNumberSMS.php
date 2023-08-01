<?php

namespace App\Jobs\Account;

use App\Http\Controllers\v1\SMSController;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReferenceNumberSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const SERVICE = 'send-reference-number';
    private $taxpayer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Taxpayer $taxpayer)
    {
        $this->taxpayer = $taxpayer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms_controller = new SMSController;
        $send_to = $this->taxpayer->mobile;
        $source = config('modulesconfig.smsheader');
        $customer_message = "The Reference Number for your account is ". strtoupper($this->taxpayer->reference_no) .". Use this to log into your ZRA Account.";
        $sms_controller->sendSMS($send_to, $source, $customer_message);
    }
}
