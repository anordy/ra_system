<?php

namespace App\Mail\Taxpayer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KycReject extends Mailable
{
    use Queueable, SerializesModels;
    
    public $kyc;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($kyc)
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
        return $this->markdown('emails.taxpayer.kyc_reject')->subject('ZRA Kyc Rejected');;
    }
}
