<?php

namespace App\Mail\Business;

use App\Models\Business;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $business, $taxpayer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Business $business, Taxpayer $taxpayer)
    {
        $this->business = $business;
        $this->taxpayer = $taxpayer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.business.registered')
            ->subject("ZRA(ZIDRAS) Business Registration - " . strtoupper($this->business->name));
    }
}
