<?php

namespace App\Mail\Extension;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExtensionRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $extension;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject("ZRA - Extension Rejected ({$this->extension->business->name})")
            ->markdown('emails.extension.rejected');
    }
}
