<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TwoFactorAuth extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_code, $_username)
    {
        $this->code = $_code;
        $this->username = $_username;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.two_factor_auth',[
            'url' => 'https://192.231.237.29:8888/zpc/public'
        ]);
    }
}
