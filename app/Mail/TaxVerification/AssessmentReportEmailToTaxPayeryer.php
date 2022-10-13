<?php

namespace App\Mail\TaxVerification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class AssessmentReportEmailToTaxPayeryer extends Mailable
{
    use Queueable, SerializesModels;

    public $payload, $taxpayer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->taxpayer = $payload[0];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $audit = $this->payload[1];
        
        $email = $this->markdown('emails.tax-verification.assessment-report-to-taxpayer')->subject('Assessment Notice Report');

        $email->attach(
            Storage::disk('local-admin')->path($audit->assessment_report), 
            [
                'as' => 'assessment-notice.pdf',
                'mime' => 'application/pdf',
            ]
        );
        
        return $email;
    }
}
