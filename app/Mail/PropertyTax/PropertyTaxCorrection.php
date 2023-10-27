<?php

namespace App\Mail\PropertyTax;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyTaxCorrection extends Mailable
{
    use Queueable, SerializesModels;

    public $property;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.property-tax.correction')->subject("Property Tax Registration Correction");
    }
}
