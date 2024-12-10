<?php

namespace App\Mail\NonTaxResident;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeregistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $businessName, $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($businessName, $status)
    {
        $this->businessName = $businessName;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.non-tax-resident.deregistration')
            ->subject("ZRA - Non Tax Resident Business De-registration for - " . strtoupper($this->businessName));
    }
}
