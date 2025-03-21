<?php

namespace App\Mail\Business\Updates;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessInformationConsultantApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.business.updates.business-information-consultant')->subject("CRDB BANK PLC Authority(ZRA) Consultant Appointment - " . strtoupper($this->payload['business']->name));
    }
}
