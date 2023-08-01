<?php

namespace App\Listeners;

use App\Jobs\Account\SendReferenceNumberMail;
use App\Models\KYC;
use App\Models\UserOtp;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Jobs\SendOTPEmail;
use App\Models\WaResponsiblePerson;
use App\Jobs\Debt\SendDebtBalanceMail;
use App\Jobs\SendKYCRegistrationEmail;
use App\Jobs\Audit\SendEmailToTaxPayer;
use App\Jobs\SendTaxAgentApprovalEmail;
use App\Jobs\User\TooManyLoginAttempts;
use App\Jobs\Taxpayer\SendKycRejectMail;
use App\Jobs\User\SendRegistrationEmail;
use App\Jobs\Vetting\SendVettedReturnMail;
use App\Jobs\Taxpayer\SendRegistrationMail;
use App\Jobs\Branch\SendBranchRegisteredMail;
use App\Jobs\Business\Taxtype\SendTaxTypeMail;
use App\Jobs\Business\SendBusinessApprovedMail;
use App\Jobs\Configuration\SendPenaltyRateEmail;
use App\Jobs\Vetting\SendToCorrectionReturnMail;
use App\Jobs\Business\SendBusinessCorrectionMail;
use App\Jobs\Configuration\SendExchangeRateEmail;
use App\Jobs\Configuration\SendInterestRateEmail;
use App\Jobs\Extension\SendExtensionApprovedMail;
use App\Jobs\Extension\SendExtensionRejectedMail;
use App\Jobs\audit\AuditApprovedNotificationEmail;
use App\Jobs\Audit\ExitPreliminaryEmailToTaxPayer;
use App\Jobs\Configuration\SendFinancialYearEmail;
use App\Jobs\Configuration\SendFinancialMonthEmail;
use App\Jobs\SendWithholdingAgentRegistrationEmail;
use App\Jobs\Business\Branch\SendBranchApprovedMail;
use App\Jobs\Debt\Waiver\SendDebtWaiverApprovalMail;
use App\Jobs\Debt\Waiver\SendDebtWaiverRejectedMail;
use App\Jobs\Taxpayer\TaxpayerAmendmentNotification;
use App\Jobs\Installment\SendInstallmentApprovedMail;
use App\Jobs\Installment\SendInstallmentRejectedMail;
use App\Jobs\Verification\SendFailedVerificationMail;
use App\Jobs\Business\Branch\SendBranchCorrectionMail;
use App\Jobs\Business\SendBusinessClosureApprovedMail;
use App\Jobs\Business\SendBusinessClosureRejectedMail;
use App\Jobs\TaxClaim\SendTaxClaimRequestFeedbackMAIL;
use App\Jobs\Business\SendBusinessClosureCorrectionMail;
use App\Jobs\DualControl\User\UserInformationUpdateMAIL;
use App\Jobs\TaxClearance\SendTaxClearanceApprovedEmail;
use App\Jobs\TaxClearance\SendTaxClearanceRejectedEmail;
use App\Jobs\Business\SendBusinessDeregisterApprovedMail;
use App\Jobs\Business\SendBusinessDeregisterRejectedMail;
use App\Jobs\Taxpayer\TaxpayerAmendmentNotificationEmail;
use App\Jobs\Business\SendBusinessDeregisterCorrectionMail;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalMail;
use App\Jobs\Business\Updates\SendBusinessUpdateRejectedMail;
use App\Jobs\Business\Updates\SendBusinessUpdateCorrectionMail;
use App\Jobs\DriversLicense\SendFreshApplicationSubmittedEmail;
use App\Jobs\TaxVerification\SendAssessmentReportEmailToTaxPayer;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalConsultantMail;
use App\Jobs\QuantityCertificate\SendQuantityCertificateMail;

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
     * @param SendMail $event
     * @return void()
     */
    public function handle(SendMail $event)
    {
        if(config('app.env') == 'local'){
            return true;
        }
        if($event->service == 'otp'){
            $token = UserOtp::find($event->tokenId);
            if(is_null($token)){
                abort(404);
            }
            SendOTPEmail::dispatch($event->extra['code'], $token->user->email, $token->user->fullname());
        } else if ($event->service == 'withholding_agent_registration') {
            /** TokenId is withholding agent history is */
            $withholding_agent = WaResponsiblePerson::find($event->tokenId);
            if(is_null($withholding_agent)){
                abort(404);
            }
            SendWithholdingAgentRegistrationEmail::dispatch($withholding_agent->taxpayer->fullname(), $withholding_agent->withholdingAgent->institution_name, $withholding_agent->taxpayer->email);
        } else if ($event->service === 'taxpayer-registration'){
            // Token ID is $taxpayerId
            $taxpayer = Taxpayer::find($event->tokenId);
            if(is_null($taxpayer)){
                abort(404);
            }
            SendRegistrationMail::dispatch($taxpayer, $event->extra['code']);
        }  else if ($event->service == 'kyc-registration') {
            $kyc = KYC::find($event->tokenId);
            SendKYCRegistrationEmail::dispatch($kyc);
        }
        else if ($event->service === 'business-registration-approved'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            if(is_null($business)){
                abort(404);
            }
            SendBusinessApprovedMail::dispatch($business, $business->taxpayer);
        } else if ($event->service === 'business-registration-correction'){
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            if(is_null($business)){
                abort(404);
            }
            SendBusinessCorrectionMail::dispatch($business, $business->taxpayer, $event->extra['message']);
        }
        else if ($event->service == 'tax-agent-registration-approval') {
	        $taxpayer = Taxpayer::find($event->tokenId);
            if(is_null($taxpayer)){
                abort(404);
            }
			$fullname = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
			$email = $taxpayer->email;
	        $status = $taxpayer->taxagent->status;
            $reference_no = $taxpayer->taxagent->reference_no;
	        SendTaxAgentApprovalEmail::dispatch($fullname, $email, $status, $reference_no);
        } else if ($event->service === 'business-closure-approval'){
            // Token ID is $closure data
            $closure = $event->tokenId;
            SendBusinessClosureApprovedMail::dispatch($closure);
        } else if ($event->service === 'business-closure-correction'){
            // Token ID is $businessId
            $closure = $event->tokenId;
            SendBusinessClosureCorrectionMail::dispatch($closure);
        } else if ($event->service === 'business-closure-rejected'){
            // Token ID is $businessId
            $closure = $event->tokenId;
            SendBusinessClosureRejectedMail::dispatch($closure);
        } else if ($event->service === 'business-deregister-approval'){
            // Token ID is $deregister data
            $deregister = $event->tokenId;
            SendBusinessDeregisterApprovedMail::dispatch($deregister);
        } else if ($event->service === 'business-deregister-correction'){
            // Token ID is $businessId
            $deregister = $event->tokenId;
            SendBusinessDeregisterCorrectionMail::dispatch($deregister);
        } else if ($event->service === 'business-deregister-rejected'){
            // Token ID is $businessId
            $deregister = $event->tokenId;
            SendBusinessDeregisterRejectedMail::dispatch($deregister);
        } else if ($event->service === 'change-tax-type-approval'){
            // Token ID is payload data having all notification details
            SendTaxTypeMail::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-approval'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalMail::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-correction'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateCorrectionMail::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-rejected'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateRejectedMail::dispatch($event->tokenId);
        }else if ($event->service === 'change-business-consultant-information-approval'){
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalConsultantMail::dispatch($event->tokenId);
        }  else if ($event->service === 'branch-approval'){
            // Token ID is payload data having all notification details
            SendBranchApprovedMail::dispatch($event->tokenId);
        } else if ($event->service === 'branch-correction'){
            // Token ID is payload data having all notification details
            SendBranchCorrectionMail::dispatch($event->tokenId);
        } else if ($event->service === 'tax-clearance-approved'){
            // Token ID is payload data having all notification details
            SendTaxClearanceApprovedEmail::dispatch($event->tokenId);
        } else if ($event->service === 'tax-clearance-rejected'){
            // Token ID is payload data having all notification details
            SendTaxClearanceRejectedEmail::dispatch($event->tokenId);
        } else if ($event->service === 'license-application-submitted'){
            SendFreshApplicationSubmittedEmail::dispatch($event->tokenId);
        }else if ($event->service === 'debt-waiver-approval'){
            SendDebtWaiverApprovalMail::dispatch($event->tokenId);
        }else if ($event->service === 'debt-waiver-rejected'){
            SendDebtWaiverRejectedMail::dispatch($event->tokenId);
        }else if ($event->service === 'debt-balance'){
            SendDebtBalanceMail::dispatch($event->tokenId);
        } else if ($event->service === 'audit-notification-to-taxpayer'){
            SendEmailToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'send-report-to-taxpayer'){
            ExitPreliminaryEmailToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'send-assessment-report-to-taxpayer'){
            SendAssessmentReportEmailToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'audit-approved-notification'){
            AuditApprovedNotificationEmail::dispatch($event->tokenId);
        } else if ($event->service === 'kyc-reject'){
            SendKycRejectMail::dispatch($event->tokenId);
        } else if ($event->service === 'financial-month'){
            SendFinancialMonthEmail::dispatch($event->tokenId);
        } else if ($event->service === 'financial-year'){
            SendFinancialYearEmail::dispatch($event->tokenId);
        } else if ($event->service === 'interest-rate'){
            SendInterestRateEmail::dispatch($event->tokenId);
        } else if ($event->service === 'penalty-rate'){
            SendPenaltyRateEmail::dispatch($event->tokenId);
        } else if ($event->service === 'exchange-rate'){
            SendExchangeRateEmail::dispatch($event->tokenId);
        } else if ($event->service === 'tax-claim-feedback'){
            SendTaxClaimRequestFeedbackMAIL::dispatch($event->tokenId);
        } else if ($event->service === 'dual-control-update-user-info-notification'){
            UserInformationUpdateMAIL::dispatch($event->tokenId);
        } else if ($event->service === 'user_add') {
            SendRegistrationEmail::dispatch($event->tokenId);
        } else if ($event->service === 'taxpayer-amendment-notification') {
            TaxpayerAmendmentNotificationEmail::dispatch($event->tokenId);
        } else if ($event->service === 'too-many-login-attempts'){
            TooManyLoginAttempts::dispatch($event->tokenId);
        } else if ($event->service === 'failed-verification'){
            SendFailedVerificationMail::dispatch($event->tokenId);
        } else if ($event->service === SendExtensionApprovedMail::SERVICE){
            SendExtensionApprovedMail::dispatch($event->tokenId);
        } else if ($event->service === SendExtensionRejectedMail::SERVICE){
            SendExtensionRejectedMail::dispatch($event->tokenId);
        } else if ($event->service === SendInstallmentApprovedMail::SERVICE){
            SendInstallmentApprovedMail::dispatch($event->tokenId);
        } else if ($event->service === SendInstallmentRejectedMail::SERVICE){
            SendInstallmentRejectedMail::dispatch($event->tokenId);
        } else if ($event->service === SendVettedReturnMail::SERVICE){
            SendVettedReturnMail::dispatch($event->tokenId);
        } else if ($event->service === SendToCorrectionReturnMail::SERVICE){
            SendToCorrectionReturnMail::dispatch($event->tokenId);
        } else if ($event->service === SendBranchRegisteredMail::SERVICE){
            SendBranchRegisteredMail::dispatch($event->tokenId);
        } else if ($event->service === SendReferenceNumberMail::SERVICE){
            SendReferenceNumberMail::dispatch($event->tokenId);
        } else if ($event->service === SendQuantityCertificateMail::SERVICE){
            SendQuantityCertificateMail::dispatch($event->tokenId);
        }
    }
}
