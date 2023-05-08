<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WithholdingAgentRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $full_name;
    public $institution_name;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($_full_name, $_institution_name, $_email)
    {
        $this->full_name = $_full_name;
        $this->institution_name = $_institution_name;
        $this->email = $_email;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.withholding_agent_registration',[
            'url' => config('modulesconfig.admin_url')
        ]);
    }
}
