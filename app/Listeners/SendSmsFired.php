<?php

namespace App\Listeners;

use App\Events\SendSms;
use App\Models\UserOtp;
use App\Jobs\SendOTPSMS;
use App\Models\WithholdingAgent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\SendWithholdingAgentRegistrationSMS;

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
        } else if ($event->service == 'withholding_agent_registration') {
            /** TokenId is withholding agent id id */
            $withholding_agent = WithholdingAgent::find($event->tokenId);
            SendWithholdingAgentRegistrationSMS::dispatch($withholding_agent->taxpayer->fullname(), $withholding_agent->institution_name, $withholding_agent->taxpayer->mobile);
        }
    }
}
