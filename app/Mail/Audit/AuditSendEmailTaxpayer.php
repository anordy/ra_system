<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuditSendEmailTaxpayer extends Mailable
{
    use Queueable, SerializesModels;
    public $taxpayerName;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($taxpayerName)
    {
        //
        $this->taxpayerName = $taxpayerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.audit.email-to-taxpayer')->subject('Selection For Tax Audit');
    }
}
