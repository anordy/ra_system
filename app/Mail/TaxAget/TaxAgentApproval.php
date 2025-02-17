<?php

namespace App\Mail\TaxAget;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaxAgentApproval extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
	public $fullname;
	public $email;
    public $status;
    public $reference_number;
	public function __construct($fullname, $email, $status, $reference_number)
	{
		$this->fullname = $fullname;
		$this->email = $email;
		$this->status = $status;
        $this->reference_number = $reference_number;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->markdown('emails.tax_agent.approval')
	      ->subject('CRDB BANK PLC Authority(ZRA) Tax Agent Application');
    }
}
