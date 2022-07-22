<?php

namespace App\Listeners;

use App\Events\SendSms;
use App\Models\UserOtp;
use App\Jobs\SendOTPSMS;
use App\Models\Business;
use App\Models\TaxAgent;
use App\Models\Taxpayer;
use App\Models\WithholdingAgent;
use App\Models\WaResponsiblePerson;
use App\Jobs\SendTaxAgentApprovalSMS;
use App\Jobs\Taxpayer\SendRegistrationSMS;
use App\Jobs\Business\Taxtype\SendTaxTypeSMS;
use App\Jobs\Business\SendBusinessApprovedSMS;
use App\Jobs\SendWithholdingAgentRegistrationSMS;
use App\Jobs\Business\SendBusinessClosureApprovedSMS;
use App\Jobs\Business\SendBusinessClosureCorrectionSMS;
use App\Jobs\Business\SendBusinessDeregisterApprovedSMS;
use App\Jobs\Business\SendBusinessDeregisterCorrectionSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateSMS;

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
        if(config('app.env') == 'local'){
            return true;
        }
        if ($event->service == 'otp') {
            $token = UserOtp::find($event->tokenId);
            SendOTPSMS::dispatch($token->code, $token->user->fullname(), $token->user->phone);
        } else if ($event->service == 'withholding_agent_registration') {
            /** TokenId is withholding agent id id */
            $withholding_agent = WaResponsiblePerson::find($event->tokenId);
            SendWithholdingAgentRegistrationSMS::dispatch($withholding_agent->taxpayer->fullname(), $withholding_agent->withholdingAgent->institution_name, $withholding_agent->taxpayer->mobile);
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
		} else if ($event->service === 'business-closure-approval'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessClosureApprovedSMS::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-closure-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessClosureCorrectionSMS::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-deregister-approval'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessDeregisterApprovedSMS::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-deregister-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            SendBusinessDeregisterCorrectionSMS::dispatch($business, $business->taxpayer);
        }else if ($event->service === 'change-tax-type-approval'){
            // Token ID is payload data having all notification details
            SendTaxTypeSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateSMS::dispatch($event->tokenId);
        }
    }
}
