<?php

namespace App\Mail\Business;

use App\Models\Business;
use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessCorrection extends Mailable
{
    public $business, $taxpayer, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Business $business, Taxpayer $taxpayer, $message)
    {
        $this->business = $business;
        $this->taxpayer = $taxpayer;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.business.correction')
            ->subject("ZRB Business Registration Correction - " . strtoupper($this->business->name));
    }
}
