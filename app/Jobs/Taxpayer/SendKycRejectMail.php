<?php

namespace App\Jobs\Taxpayer;

use App\Mail\Taxpayer\KycReject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendKycRejectMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $kyc;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kyc)
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
            Mail::to($this->kyc->email)->send(new KycReject($this->kyc));
        }
    }
}
