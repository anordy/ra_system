<?php

namespace App\Mail\NonTaxResident;

use App\Models\Ntr\NtrBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public $business, $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NtrBusiness $business, $code)
    {
        $this->business = $business;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.non-tax-resident.registration')
            ->subject('CRDB BANK PLC Authority(ZRA) Taxpayer Registration');
    }
}
