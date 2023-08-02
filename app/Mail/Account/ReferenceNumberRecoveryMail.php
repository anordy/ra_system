<?php

namespace App\Mail\Account;

use App\Models\Taxpayer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferenceNumberRecoveryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $taxpayer;

    public function __construct(Taxpayer $taxpayer)
    {
        $this->taxpayer = $taxpayer;
    }

    public function build()
    {
        return $this->markdown('emails.account.reference-number-recovery', [
            'url' => config('modulesconfig.taxpayer_url')
        ]);
    }
}
