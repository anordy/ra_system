<?php

namespace App\Jobs;

use App\Mail\TaxAget\TaxAgentApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTaxAgentApprovalEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public $fullname, $email, $status;
    public function __construct($fullname, $email, $status)
    {
        $this->fullname = $fullname;
		$this->email = $email;
		$this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		Mail::to($this->email)->send(new TaxAgentApproval($this->fullname, $this->email, $this->status));
    }
}
