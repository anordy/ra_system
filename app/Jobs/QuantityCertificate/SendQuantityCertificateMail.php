<?php

namespace App\Jobs\QuantityCertificate;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Mail\QuantityCertificate\QuantityCertificateGenerated;

class SendQuantityCertificateMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $certificate;

    const SERVICE = 'quantity-certificate-generated';


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->certificate->business->taxpayer->email) {
            Mail::to($this->certificate->business->taxpayer->email)->send(new QuantityCertificateGenerated($this->certificate));
        }
    }
}
