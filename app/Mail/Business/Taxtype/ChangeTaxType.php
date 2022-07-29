<?php

namespace App\Mail\Business\Taxtype;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeTaxType extends Mailable
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
        return $this->markdown('emails.business.taxtypes.change')->subject("ZRB Change Tax Type Request - " . strtoupper($this->payload['business']->name));
    }
}
