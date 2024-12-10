<?php

namespace App\Jobs\TaxVerification;

use App\Mail\TaxVerification\VerificationSendNotificationLetterToTaxPayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PDF;

class VerificationNotificationLetterToTaxPayer implements ShouldQueue
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
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->payload[0]->email;
        if ($email) {
            Mail::to($email)->send(new VerificationSendNotificationLetterToTaxPayer($this->payload));
        }
    }
}
