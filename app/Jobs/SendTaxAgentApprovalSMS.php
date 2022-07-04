<?php

namespace App\Jobs;

use App\Http\Controllers\v1\SMSController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaxAgentApprovalSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public $taxpayer;
    public function __construct($taxpayer)
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
		if ($this->taxpayer->taxagent->is_verified== 1)
		{
			$message = "Your application as tax agent has been approved successfully use this control number 99306474554 to pay for the service ";
		}

		else
		{
			$message = "Your application as tax agent has been rejected please apply again ";

		}
	    $source = config('modulesconfig.smsheader');
	    $sms_controller->sendSMS($this->taxpayer->mobile, $source, $message);
    }
}
