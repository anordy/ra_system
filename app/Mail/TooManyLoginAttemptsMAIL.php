<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TooManyLoginAttemptsMAIL extends Mailable
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
        //
        $this->payload = $payload;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.too-many-login-attempts')
            ->subject('Important: Security Alert!');
    }
}
