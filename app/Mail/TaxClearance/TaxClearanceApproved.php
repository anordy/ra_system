<?php

namespace App\Mail\TaxClearance;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class TaxClearanceApproved extends Mailable
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
        $location = $this->payload[0];
        $taxClearanceRequest = $this->payload[1];
        $pdf = PDF::loadView('tax-clearance.includes.certificate', compact('location', 'taxClearanceRequest'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $this->markdown('emails.taxclearance.taxclearanceapproved')->attachData($pdf->output(), "tax_clearance_certificate.pdf");;
    }
}
