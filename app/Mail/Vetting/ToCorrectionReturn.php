<?php

namespace App\Mail\Vetting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ToCorrectionReturn extends Mailable
{
    use Queueable, SerializesModels;

    public $tax_return;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tax_return)
    {
        $this->tax_return = $tax_return;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.vetting.to-correction')->subject("{$this->tax_return->taxtype->name} Return for {$this->tax_return->financialMonth->name}/{$this->tax_return->financialMonth->year->code} requires corrections");
    }
}
