<?php

namespace App\Listeners;

use App\Events\SendSms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SendSms  $event
     * @return void
     */
    public function handle(SendSms $event)
    {
        //
    }
}
