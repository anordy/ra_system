<?php

namespace App\Mail\TaxClearance;

use App\Models\SystemSetting;
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

        $signaturePath = SystemSetting::where('code', SystemSetting::GENERAL_COMMISSIONER_SIGN)->where('is_approved', 1)->value('value') ?? null;

        $pdf = PDF::loadView('tax-clearance.includes.online-certificate', compact('location', 'taxClearanceRequest', 'signaturePath'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $this->markdown('emails.taxclearance.taxclearanceapproved')->attachData($pdf->output(), "tax_clearance_certificate.pdf")->subject('Tax Clearance Application');
    }
}
