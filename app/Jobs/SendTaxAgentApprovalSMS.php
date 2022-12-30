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
		if ($this->taxpayer->taxagent->status == 'approved')
		{
			$message = "Your application as tax consultant has been approved successfully";
		}

		else
		{
			$message = "Your application as tax consultant has been rejected, Please apply again ";

		}
	    $source = config('modulesconfig.smsheader');
	    $sms_controller->sendSMS($this->taxpayer->mobile, $source, $message);
    }
}
