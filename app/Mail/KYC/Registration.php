<?php

namespace App\Mail\KYC;

use App\Models\KYC;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public $kyc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(KYC $kyc)
    {
        $this->kyc = $kyc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.kyc.registration')
            ->subject('ZRA Taxpayer Registration');
    }
}
