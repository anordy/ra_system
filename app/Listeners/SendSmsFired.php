<?php

namespace App\Listeners;

use App\Events\SendSms;
use App\Jobs\Audit\SendSmsToTaxPayer;
use App\Jobs\Business\Branch\SendBranchApprovalSMS;
use App\Jobs\Business\Branch\SendBranchCorrectionSMS;
use App\Jobs\Business\SendBusinessApprovedSMS;
use App\Jobs\Business\SendBusinessClosureApprovedSMS;
use App\Jobs\Business\SendBusinessClosureCorrectionSMS;
use App\Jobs\Business\SendBusinessClosureRejectedSMS;
use App\Jobs\Business\SendBusinessCorrectionSMS;
use App\Jobs\Business\SendBusinessDeregisterApprovedSMS;
use App\Jobs\Business\SendBusinessDeregisterCorrectionSMS;
use App\Jobs\Business\SendBusinessDeregisterRejectedSMS;
use App\Jobs\Business\Taxtype\SendTaxTypeSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalConsultantSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateCorrectionSMS;
use App\Jobs\TaxClearance\RequestFeedbackJob;
use App\Jobs\Business\Updates\SendBusinessUpdateRejectedSMS;
use App\Jobs\Debt\SendDebtBalanceSMS;
use App\Jobs\Debt\Waiver\SendDebtWaiverApprovalSMS;
use App\Jobs\Debt\Waiver\SendDebtWaiverRejectedSMS;
use App\Jobs\DriversLicense\SendFreshApplicationSubmittedSMS;
use App\Jobs\SendOTPSMS;
use App\Jobs\SendTaxAgentApprovalSMS;
use App\Jobs\SendWithholdingAgentRegistrationSMS;
use App\Jobs\Taxpayer\SendKycRejectSMS;
use App\Jobs\Taxpayer\SendRegistrationSMS;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\UserOtp;
use App\Models\WaResponsiblePerson;

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
            SendBusinessCorrectionSMS::dispatch($business, $event->extra['message']);
        }
		else if ($event->service == 'tax-agent-registration-approval') {
			$taxpayer = Taxpayer::find($event->tokenId);
			SendTaxAgentApprovalSMS::dispatch($taxpayer);
		} else if ($event->service === 'business-closure-approval'){
            // Token ID is closure data
            $closure = $event->tokenId;
            SendBusinessClosureApprovedSMS::dispatch($closure);
        } else if ($event->service === 'business-closure-correction'){
            // Token ID is $closure
            $closure = $event->tokenId;
            SendBusinessClosureCorrectionSMS::dispatch($closure);
        } else if ($event->service === 'business-closure-rejected'){
            // Token ID is $closure
            $closure = $event->tokenId;
            SendBusinessClosureRejectedSMS::dispatch($closure);
        } else if ($event->service === 'business-deregister-approval'){
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterApprovedSMS::dispatch($deregister);
        } else if ($event->service === 'business-deregister-correction'){
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterCorrectionSMS::dispatch($deregister);
        } else if ($event->service === 'business-deregister-rejected'){
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterRejectedSMS::dispatch($deregister);
        } else if ($event->service === 'change-tax-type-approval'){
            // Token ID is payload data having all notification details
            SendTaxTypeSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-approval'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalSMS::dispatch($event->tokenId);
        }else if ($event->service === 'change-business-information-correction'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateCorrectionSMS::dispatch($event->tokenId);
        }else if ($event->service === 'change-business-information-rejected'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateRejectedSMS::dispatch($event->tokenId);
        }else if ($event->service === 'change-business-consultant-information-approval'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalConsultantSMS::dispatch($event->tokenId);
        } else if ($event->service === 'branch-approval'){
            // Token ID is payload data having all notification details
            SendBranchApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === 'branch-correction'){
            // Token ID is payload data having all notification details
            SendBranchCorrectionSMS::dispatch($event->tokenId);
        }else if ($event->service === 'license-application-submitted'){
            SendFreshApplicationSubmittedSMS::dispatch($event->tokenId);
        }else if ($event->service === 'debt-waiver-approval'){
            SendDebtWaiverApprovalSMS::dispatch($event->tokenId);
        }else if ($event->service === 'debt-waiver-rejected'){
            SendDebtWaiverRejectedSMS::dispatch($event->tokenId);
        }else if ($event->service === 'debt-balance'){
            SendDebtBalanceSMS::dispatch($event->tokenId);
        } else if ($event->service === 'audit-notification-to-taxpayer'){
            SendSmsToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'tax-clearance-feedback-to-taxpayer'){
            RequestFeedbackJob::dispatch($event->tokenId);
        } else if ($event->service === 'kyc-reject'){
            SendKycRejectSMS::dispatch($event->tokenId);
        }
    }
}
