<?php

namespace App\Mail\Vfms;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message, $business_name, $user_name, $user_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        //
        $this->message = $payload['message'];
        $this->business_name = $payload['business_name'];
        $this->user_name = $payload['user_name'];
        $this->user_type = $payload['user_type'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = $this->user_type == 'taxpayer' ? "Update Z-Number for ". $this->business_name : "ZIDRAS - VFMS Notification";
        return $this->markdown('emails.vfms.client-notification')->subject($title);
    }
}
