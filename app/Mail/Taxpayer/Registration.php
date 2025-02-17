<?php

namespace App\Mail\Taxpayer;

use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public $taxpayer, $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Taxpayer $taxpayer, $code)
    {
        $this->taxpayer = $taxpayer;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.taxpayer.registration')
            ->subject('CRDB BANK PLC Authority(ZRA) Taxpayer Registration');
    }
}
