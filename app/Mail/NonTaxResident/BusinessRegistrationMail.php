<?php

namespace App\Mail\NonTaxResident;

use App\Models\Ntr\NtrBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BusinessRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $business, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NtrBusiness $business, $message)
    {
        $this->business = $business;
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
            ->subject('CRDB BANK PLC Authority(ZRA) Business Registration');
    }
}
