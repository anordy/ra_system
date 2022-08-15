<?php

namespace App\Mail\Business\Taxtype;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

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
        $business = $this->payload['business'];
        $pdf = PDF::loadView('business.certificate', compact('business'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $this->markdown('emails.business.taxtypes.change')->subject("ZRB Change Tax Type Request - " . strtoupper($this->payload['business']->name))->attachData($pdf->output(), "{$this->payload['business']->name}_certificate.pdf");
    }
}
