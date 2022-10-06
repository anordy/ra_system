<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendReportToTaxPayer extends Mailable
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
        $this->taxpayer = $this->payload[0];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $audit = $this->payload[1];
        
        $email = $this->markdown('emails.audit.send-report-to-taxpayer')->subject('Exit Minute and Preliminary Reports');

        $exitPdf = Storage::disk('local-admin')->response($audit->exit_minutes);
        $preliminaryPdf = Storage::disk('local-admin')->response($audit->preliminary_report);
        
        if ($exitPdf && $preliminaryPdf) {
            $attachments = [
                Storage::disk('local-admin')->path($audit->exit_minutes) => [
                    'as' => 'exit-minute-report.pdf',
                    'mime' => 'application/pdf',
                ],
                Storage::disk('local-admin')->path($audit->preliminary_report) => [
                    'as' => 'preliminary-report.pdf',
                    'mime' => 'application/pdf',
                ],
            ];
            
            
            foreach ($attachments as $filePath => $fileParameters) {
                $email->attach($filePath, $fileParameters);
            }
        }
        
        return $email;
    }
}
