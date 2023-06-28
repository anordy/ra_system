<?php

namespace App\Mail\Vfms;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message, $business_name, $taxpayer_name;
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
        $this->taxpayer_name = $payload['taxpayer_name'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.vfms.client-notification')->subject("Update Z-Number for ". $this->business_name);
    }
}
