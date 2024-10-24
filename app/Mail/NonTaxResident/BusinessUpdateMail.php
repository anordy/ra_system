<?php

namespace App\Mail\NonTaxResident;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formattedInfo, $businessName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($formattedInfo, $businessName)
    {
        $this->formattedInfo = $formattedInfo;
        $this->businessName = $businessName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.non-tax-resident.updates')
            ->subject("ZRA - Non Tax Resident Business Update for - " . strtoupper($this->businessName));
    }
}
