<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $full_name;
    public $username;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_full_name, $_username, $_password)
    {
        $this->full_name = $_full_name;
        $this->username = $_username;
        $this->password = $_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user_registration',[
            'url' => 'https://192.231.237.29:8888/zpc/public'
        ]);
    }
}
