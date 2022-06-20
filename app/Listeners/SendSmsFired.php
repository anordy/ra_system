<?php

namespace App\Listeners;

use App\Events\SendSms;
use App\Jobs\SendOTPSMS;
use App\Models\UserOtp;
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
        if ($event->service == 'otp') {
            $token = UserOtp::find($event->tokenId);
            SendOTPSMS::dispatch($token->code, $token->user->fullname(), $token->user->phone);
        }
    }
}
