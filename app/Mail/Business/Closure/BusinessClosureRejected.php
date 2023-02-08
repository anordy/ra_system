<?php

namespace App\Mail\Business\Closure;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessClosureRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $closure;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($closure)
    {
        $this->closure = $closure;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.business.closure.rejected')
            ->subject("Zanzibar Revenue Authority(ZRA) Temporary Business Closure- " . strtoupper($this->closure->business->name));
    }
}
