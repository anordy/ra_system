<?php

namespace App\Jobs;

use App\Mail\KYC\Registration;
use App\Models\KYC;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendKYCRegistrationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $kyc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(KYC $kyc)
    {
        $this->kyc = $kyc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->kyc->email) {
            Mail::to($this->kyc->email)->send(new Registration($this->kyc));
        }
    }
}
