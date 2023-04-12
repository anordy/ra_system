<?php

namespace App\Mail\Installment;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstallmentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $installment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($installment)
    {
        $this->installment = $installment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("ZRA - Installment Rejected ({$this->installment->business->name})")
            ->markdown('emails.installment.rejected');
    }
}
