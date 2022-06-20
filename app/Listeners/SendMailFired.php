<?php

namespace App\Listeners;

use App\Events\SendMail;
use App\Jobs\SendOTPEmail;
use App\Models\UserOtp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMailFired
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
     * @param  \App\Events\SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        if($event->service == 'otp'){
            $token = UserOtp::find($event->tokenId);
            SendOTPEmail::dispatch($token->code, $token->user->email, $token->user->fullname());
        }
       
       
    }
}
