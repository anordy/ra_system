<?php

namespace App\Listeners;

use App\Jobs\Taxpayer\SendRegistrationMail;
use App\Models\Taxpayer;
use App\Models\UserOtp;
use App\Events\SendMail;
use App\Models\WithholdingAgent;
use App\Jobs\SendWithholdingAgentRegistrationEmail;
use App\Jobs\SendOTPEmail;

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
     * @return void()
     */
    public function handle(SendMail $event)
    {
        if(config('app.env') == 'local'){
            return true;
        }
        if($event->service == 'otp'){
            $token = UserOtp::find($event->tokenId);
            SendOTPEmail::dispatch($token->code, $token->user->email, $token->user->fullname());
        } else if ($event->service == 'withholding_agent_registration') {
            /** TokenId is withholding agent id id */
            $withholding_agent = WithholdingAgent::find($event->tokenId);
            SendWithholdingAgentRegistrationEmail::dispatch($withholding_agent->taxpayer->fullname(), $withholding_agent->institution_name, $withholding_agent->taxpayer->email);
        } else if ($event->service === 'taxpayer-registration'){
            // Token ID is $taxpayerId
            $taxpayer = Taxpayer::find($event->tokenId);
            SendRegistrationMail::dispatch($taxpayer, $event->extra['code']);
        }
    }
}
