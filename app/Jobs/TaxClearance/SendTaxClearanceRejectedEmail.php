<?php

namespace App\Jobs\TaxClearance;

use App\Mail\TaxClearance\TaxClearanceApproved;
use App\Mail\TaxClearance\TaxClearanceRejected;
use App\Mail\UserRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaxClearanceRejectedEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $send_to;
    public $message;

    /**
     * Create a new job instance.
     *
     * @param $_password
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->send_to = $payload[0];
        $this->message = $payload[1];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->send_to) {
            Mail::to($this->send_to)->send(new TaxClearanceRejected($this->message));
        } else {
            Log::error("Tax Clearance Reject Feedback: { $this->send_to } Invalid Email");
        }
    }
}
