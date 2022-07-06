<?php

namespace App\Listeners;

use App\Events\SendSms;
use App\Jobs\Business\SendBusinessApprovedSMS;
use App\Jobs\SendTaxAgentApprovalSMS;
use App\Jobs\Taxpayer\SendRegistrationSMS;
use App\Models\Business;
use App\Models\TaxAgent;
use App\Models\Taxpayer;
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
        } else if ($event->service === 'taxpayer-registration'){
            // Token ID is $taxpayerId
            $taxpayer = Taxpayer::find($event->tokenId);
            SendRegistrationSMS::dispatch($taxpayer->mobile, $taxpayer->reference_no, $event->extra['code']);
        } else if ($event->service === 'business-registration-approved'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessApprovedSMS::dispatch($business);
        } else if ($event->service === 'business-registration-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessApprovedSMS::dispatch($business);
        }
		else if ($event->service == 'tax-agent-registration-approval') {
			$taxpayer = Taxpayer::find($event->tokenId);
			SendTaxAgentApprovalSMS::dispatch($taxpayer);
		}
    }
}
