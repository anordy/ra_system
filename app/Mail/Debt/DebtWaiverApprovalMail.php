<?php

namespace App\Mail\Debt;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DebtWaiverApprovalMail extends Mailable
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
        return $this->markdown('emails.debt.waiver.approval')->subject("CRDB BANK PLC Authority(ZRA) Debt Waiver Approval - " . strtoupper($this->payload['debt']->business->name));
    }
}
