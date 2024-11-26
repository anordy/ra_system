<?php

namespace App\Mail\NonTaxResident;

use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taxpayer, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Taxpayer $taxpayer, $message)
    {
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
        return $this->markdown('emails.non-tax-resident.business-registration')
            ->subject('Zanzibar Revenue Authority(ZRA) Business Registration');
    }
}
