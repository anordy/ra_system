<?php

namespace App\Jobs\TaxClaim;

use App\Mail\TaxClaim\TaxClaimFeedbackMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaxClaimRequestFeedbackMAIL implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        //
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if ($this->payload['email']) {
            Mail::to($this->payload['email'])->send(new TaxClaimFeedbackMail($this->payload));
        } else {
            Log::error("Tax Claim Feedback: { $this->payload['email'] } Invalid Email");
        }
    }
}
