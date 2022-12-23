<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $full_name;
    public $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_full_name,$_email, $_password)
    {
        $this->full_name = $_full_name;
        $this->email = $_email;
        $this->password = $_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.user-registration',[
            'url' => 'https://192.231.237.29:8888/zrb_admin/public/login'
        ])->subject("ZRB Staff Registration");
    }
}
