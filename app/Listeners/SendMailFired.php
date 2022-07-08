<?php

namespace App\Listeners;

use App\Jobs\Business\SendBusinessApprovedMail;
use App\Jobs\Business\SendBusinessCorrectionMail;
use App\Jobs\SendTaxAgentApprovalEmail;
use App\Jobs\Taxpayer\SendRegistrationMail;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\UserOtp;
use App\Events\SendMail;
use App\Jobs\Business\SendBusinessClosureApprovedMail;
use App\Jobs\Business\SendBusinessClosureCorrectionMail;
use App\Jobs\Business\SendBusinessDeregisterApprovedMail;
use App\Jobs\Business\SendBusinessDeregisterCorrectionMail;
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
        } else if ($event->service === 'business-registration-approved'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessApprovedMail::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-registration-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessCorrectionMail::dispatch($business, $business->taxpayer);
        }
        else if ($event->service == 'tax-agent-registration-approval') {
	        $taxpayer = Taxpayer::find($event->tokenId);
			$fullname = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
			$email = $taxpayer->email;
	        $status = $taxpayer->taxagent->is_verified;
	        SendTaxAgentApprovalEmail::dispatch($fullname, $email, $status);
        } else if ($event->service === 'business-closure-approval'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessClosureApprovedMail::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-closure-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessClosureCorrectionMail::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-deregister-approval'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessDeregisterApprovedMail::dispatch($business);
        } else if ($event->service === 'business-deregister-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessDeregisterCorrectionMail::dispatch($business, $business->taxpayer);
        }
    }
}
