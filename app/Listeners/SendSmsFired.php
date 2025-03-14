<?php

namespace App\Listeners;

use App\Jobs\Account\SendReferenceNumberMail;
use App\Jobs\Account\SendReferenceNumberSMS;
use App\Jobs\PropertyTax\SendPaymentExtensionApprovalSMS;
use App\Jobs\PropertyTax\SendPropertyTaxApprovalSMS;
use App\Jobs\PropertyTax\SendPropertyTaxCorrectionSMS;
use App\Jobs\PropertyTax\SendPropertyTaxPaymentReminderApprovalSMS;
use App\Jobs\SendCustomSMS;
use App\Jobs\SendZanMalipoSMS;
use App\Jobs\Vfms\ClientNotificationSMS;
use App\Models\KYC;
use App\Events\SendSms;
use App\Jobs\Audit\SendNotificationLetterSmsToTaxPayer;
use App\Models\UserOtp;
use App\Jobs\SendOTPSMS;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\WaResponsiblePerson;
use App\Jobs\SendKYCRegistrationSMS;
use App\Jobs\Audit\SendSmsToTaxPayer;
use App\Jobs\Debt\SendDebtBalanceSMS;
use App\Jobs\SendTaxAgentApprovalSMS;
use App\Jobs\Taxpayer\SendKycRejectSMS;
use App\Jobs\Vetting\SendVettedReturnSMS;
use App\Jobs\Taxpayer\SendRegistrationSMS;
use App\Jobs\Branch\SendBranchRegisteredSMS;
use App\Jobs\Business\Taxtype\SendTaxTypeSMS;
use App\Jobs\TaxClearance\RequestFeedbackJob;
use App\Jobs\Business\SendBusinessApprovedSMS;
use App\Jobs\Configuration\SendPenaltyRateSMS;
use App\Jobs\Configuration\SendExchangeRateSMS;
use App\Jobs\Configuration\SendInterestRateSMS;
use App\Jobs\Vetting\SendToCorrectionReturnSMS;
use App\Jobs\Business\SendBusinessCorrectionSMS;
use App\Jobs\Configuration\SendFinancialYearSMS;
use App\Jobs\Extension\SendExtensionApprovedSMS;
use App\Jobs\Extension\SendExtensionRejectedSMS;
use App\Jobs\Configuration\SendFinancialMonthSMS;
use App\Jobs\SendWithholdingAgentRegistrationSMS;
use App\Jobs\Business\Branch\SendBranchApprovalSMS;
use App\Jobs\Debt\Waiver\SendDebtWaiverApprovalSMS;
use App\Jobs\Debt\Waiver\SendDebtWaiverRejectedSMS;
use App\Jobs\Installment\SendInstallmentApprovedSMS;
use App\Jobs\Installment\SendInstallmentRejectedSMS;
use App\Jobs\Business\Branch\SendBranchCorrectionSMS;
use App\Jobs\Business\SendBusinessClosureApprovedSMS;
use App\Jobs\Business\SendBusinessClosureRejectedSMS;
use App\Jobs\TaxClaim\SendTaxClaimRequestFeedbackSMS;
use App\Jobs\Business\SendBusinessClosureCorrectionSMS;
use App\Jobs\DualControl\User\UserInformationUpdateSMS;
use App\Jobs\Taxpayer\TaxpayerAmendmentNotificationSMS;
use App\Jobs\Business\SendBusinessDeregisterApprovedSMS;
use App\Jobs\Business\SendBusinessDeregisterRejectedSMS;
use App\Jobs\Business\SendBusinessDeregisterCorrectionSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateRejectedSMS;
use App\Jobs\DriversLicense\SendFreshApplicationSubmittedSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateCorrectionSMS;
use App\Jobs\User\SendRegistrationSMS as UserSendRegistrationSMS;
use App\Jobs\Business\Updates\SendBusinessUpdateApprovalConsultantSMS;
use App\Jobs\QuantityCertificate\SendQuantityCertificateSMS;

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
        if (config('app.env') == 'local') {
            return true;
        }
        if ($event->service == 'otp') {
            $token = UserOtp::find($event->tokenId);
            if (is_null($token)) {
                abort(404);
            }
            SendOTPSMS::dispatch($event->extra['code'], $token->user->fullname(), $token->user->phone)->onQueue('sms');
        } else if ($event->service == 'withholding_agent_registration') {
            /** TokenId is withholding agent id */
            $withholding_agent = WaResponsiblePerson::find($event->tokenId);
            if (is_null($withholding_agent)) {
                abort(404);
            }
            SendWithholdingAgentRegistrationSMS::dispatch($withholding_agent->taxpayer->fullname(), $withholding_agent->withholdingAgent->institution_name, $withholding_agent->taxpayer->mobile);
        } else if ($event->service === 'taxpayer-registration') {
            // Token ID is $taxpayerId
            $taxpayer = Taxpayer::find($event->tokenId);
            if (is_null($taxpayer)) {
                abort(404);
            }
            SendRegistrationSMS::dispatch($taxpayer->mobile, $taxpayer->reference_no, $event->extra['code']);
        } else if ($event->service == 'kyc-registration') {
            // token is kyc id
            $kyc = KYC::find($event->tokenId);
            SendKYCRegistrationSMS::dispatch($kyc);
        } else if ($event->service === 'business-registration-approved') {
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            if (is_null($business)) {
                abort(404);
            }
            SendBusinessApprovedSMS::dispatch($business);
        } else if ($event->service === 'business-registration-correction') {
            // Token ID is $businessId
            $business = Business::find($event->tokenId);
            if (is_null($business)) {
                abort(404);
            }
            SendBusinessCorrectionSMS::dispatch($business, $event->extra['message']);
        } else if ($event->service == 'tax-agent-registration-approval') {
            $taxpayer = Taxpayer::find($event->tokenId);
            if (is_null($taxpayer)) {
                abort(404);
            }
            SendTaxAgentApprovalSMS::dispatch($taxpayer);
        } else if ($event->service === 'business-closure-approval') {
            // Token ID is closure data
            $closure = $event->tokenId;
            SendBusinessClosureApprovedSMS::dispatch($closure);
        } else if ($event->service === 'business-closure-correction') {
            // Token ID is $closure
            $closure = $event->tokenId;
            SendBusinessClosureCorrectionSMS::dispatch($closure);
        } else if ($event->service === 'business-closure-rejected') {
            // Token ID is $closure
            $closure = $event->tokenId;
            SendBusinessClosureRejectedSMS::dispatch($closure);
        } else if ($event->service === 'business-deregister-approval') {
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterApprovedSMS::dispatch($deregister);
        } else if ($event->service === 'business-deregister-correction') {
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterCorrectionSMS::dispatch($deregister);
        } else if ($event->service === 'business-deregister-rejected') {
            // Token ID is $deregister
            $deregister = $event->tokenId;
            SendBusinessDeregisterRejectedSMS::dispatch($deregister);
        } else if ($event->service === 'change-tax-type-approval') {
            // Token ID is payload data having all notification details
            SendTaxTypeSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-approval') {
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-correction') {
            // Token ID is payload data having all notification details
            SendBusinessUpdateCorrectionSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-information-rejected') {
            // Token ID is payload data having all notification details
            SendBusinessUpdateRejectedSMS::dispatch($event->tokenId);
        } else if ($event->service === 'change-business-consultant-information-approval') {
            // Token ID is payload data having all notification details
            SendBusinessUpdateApprovalConsultantSMS::dispatch($event->tokenId);
        } else if ($event->service === 'branch-approval') {
            // Token ID is payload data having all notification details
            SendBranchApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === 'branch-correction') {
            // Token ID is payload data having all notification details
            SendBranchCorrectionSMS::dispatch($event->tokenId);
        } else if ($event->service === 'license-application-submitted') {
            SendFreshApplicationSubmittedSMS::dispatch($event->tokenId);
        } else if ($event->service === 'debt-waiver-approval') {
            SendDebtWaiverApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === 'debt-waiver-rejected') {
            SendDebtWaiverRejectedSMS::dispatch($event->tokenId);
        } else if ($event->service === 'debt-balance') {
            SendDebtBalanceSMS::dispatch($event->tokenId);
        } else if ($event->service === 'audit-notification-to-taxpayer') {
            SendSmsToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'notification-letter-to-taxpayer') {
            SendNotificationLetterSmsToTaxPayer::dispatch($event->tokenId);
        } else if ($event->service === 'tax-clearance-feedback-to-taxpayer') {
            RequestFeedbackJob::dispatch($event->tokenId);
        } else if ($event->service === 'kyc-reject') {
            SendKycRejectSMS::dispatch($event->tokenId);
        } else if ($event->service === 'financial-month') {
            SendFinancialMonthSMS::dispatch($event->tokenId);
        } else if ($event->service === 'financial-year') {
            SendFinancialYearSMS::dispatch($event->tokenId);
        } else if ($event->service === 'interest-rate') {
            SendInterestRateSMS::dispatch($event->tokenId);
        } else if ($event->service === 'penalty-rate') {
            SendPenaltyRateSMS::dispatch($event->tokenId);
        } else if ($event->service === 'exchange-rate') {
            SendExchangeRateSMS::dispatch($event->tokenId);
        } else if ($event->service === 'tax-claim-feedback') {
            SendTaxClaimRequestFeedbackSMS::dispatch($event->tokenId);
        } else if ($event->service === 'dual-control-update-user-info-notification') {
            UserInformationUpdateSMS::dispatch($event->tokenId);
        } else if ($event->service === 'user_add') {
            UserSendRegistrationSMS::dispatch($event->tokenId);
        } else if ($event->service === 'taxpayer-amendment-notification') {
            TaxpayerAmendmentNotificationSMS::dispatch($event->tokenId);
        } else if ($event->service === SendExtensionApprovedSMS::SERVICE) {
            SendExtensionApprovedSMS::dispatch($event->tokenId);
        } else if ($event->service === SendExtensionRejectedSMS::SERVICE) {
            SendExtensionRejectedSMS::dispatch($event->tokenId);
        } else if ($event->service === SendInstallmentApprovedSMS::SERVICE) {
            SendInstallmentApprovedSMS::dispatch($event->tokenId);
        } else if ($event->service === SendInstallmentRejectedSMS::SERVICE) {
            SendInstallmentRejectedSMS::dispatch($event->tokenId);
        } else if ($event->service === SendVettedReturnSMS::SERVICE) {
            SendVettedReturnSMS::dispatch($event->tokenId);
        } else if ($event->service === SendToCorrectionReturnSMS::SERVICE) {
            SendToCorrectionReturnSMS::dispatch($event->tokenId);
        } else if ($event->service === SendBranchRegisteredSMS::SERVICE) {
            SendBranchRegisteredSMS::dispatch($event->tokenId);
        } else if ($event->service === SendZanMalipoSMS::SERVICE) {
            SendZanMalipoSMS::dispatch($event->extra['mobile_no'], $event->extra['message']);
        }  else if ($event->service === ClientNotificationSMS::SERVICE){
            ClientNotificationSMS::dispatch($event->tokenId);
        } else if ($event->service === SendReferenceNumberMail::SERVICE) {
            SendReferenceNumberSMS::dispatch($event->tokenId);
        } else if ($event->service === SendQuantityCertificateSMS::SERVICE) {
            SendQuantityCertificateSMS::dispatch($event->tokenId);
        } else if ($event->service === SendPropertyTaxApprovalSMS::SERVICE) {
            SendPropertyTaxApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === SendPropertyTaxPaymentReminderApprovalSMS::SERVICE) {
            SendPropertyTaxPaymentReminderApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === SendPropertyTaxCorrectionSMS::SERVICE) {
            SendPropertyTaxCorrectionSMS::dispatch($event->tokenId);
        } else if ($event->service === SendPaymentExtensionApprovalSMS::SERVICE) {
            SendPaymentExtensionApprovalSMS::dispatch($event->tokenId);
        } else if ($event->service === SendCustomSMS::SERVICE) {
            SendCustomSMS::dispatch($event->extra['phone'], $event->extra['message']);
        }
    }
}
