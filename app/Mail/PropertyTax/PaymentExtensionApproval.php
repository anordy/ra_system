<?php

namespace App\Mail\PropertyTax;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentExtensionApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $message)
    {
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.property-tax.payment-extension')->subject("Property Tax Payment Extension Request");
    }
}
