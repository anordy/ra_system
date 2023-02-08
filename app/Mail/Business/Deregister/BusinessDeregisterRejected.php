<?php

namespace App\Mail\Business\Deregister;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessDeregisterRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $deregister;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($deregister)
    {
        $this->deregister = $deregister;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.business.deregister.rejected')
            ->subject("Zanzibar Revenue Authority(ZRA) Business De-registration - " . strtoupper($this->deregister->business->name));
    }
}
