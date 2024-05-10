<?php

namespace App\Mail\Audit;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendNotificationLetterToTaxPayer extends Mailable
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
     * Build the message for sending the notification letter to the taxpayer.
     *
     * @return $this
     */
    public function build()
    {
        // Get the audit details from the payload
        $audit = $this->payload[1];

        // Create a new email message using the specified markdown template and subject
        $email = $this->markdown('emails.audit.notification-letter-to-taxpayer')->subject('Notification of Tax Audit');

        // Retrieve the notification letter file from the storage
        $notificationLetter = Storage::disk('local')->response($audit->notification_letter);

        // If the notification letter exists, prepare it as an attachment
        if ($notificationLetter) {
            $attachments = [
                Storage::disk('local')->path($audit->notification_letter) => [
                    'as' => 'exit-minute-report.pdf',
                    'mime' => 'application/pdf',
                ],
            ];

            // Attach the notification letter file to the email
            foreach ($attachments as $filePath => $fileParameters) {
                $email->attach($filePath, $fileParameters);
            }
        }

        // Return the email message
        return $email;
    }
}
