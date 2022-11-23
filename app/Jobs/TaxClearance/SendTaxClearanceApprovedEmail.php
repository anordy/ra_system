<?php

namespace App\Jobs\TaxClearance;

use App\Mail\TaxClearance\TaxClearanceApproved;
use App\Mail\UserRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTaxClearanceApprovedEmail implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new job instance.
     *
     * @param $_password
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
        $taxpayer = $this->payload[1]->business->taxpayer;
        if ($taxpayer->email) {
            Mail::to($taxpayer->email)->send(new TaxClearanceApproved($this->payload));
        }
    }
}
