<?php

namespace App\Mail\Branch;

use App\Models\BusinessLocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $location;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BusinessLocation $location)
    {
        $this->location = $location;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.branch.registered')
            ->subject("Zanzibar Revenue Authority(ZRA) Branch Registration - " . strtoupper($this->location->name));
    }
}
