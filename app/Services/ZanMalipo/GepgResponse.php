<?php


namespace App\Services\ZanMalipo;

trait GepgResponse
{
    public function getResponseCodeStatus($code)
    {
        switch ($code) {
            case '7101':
                return ['code' => $code, 'message' => 'Successful'];
                break;
            case '7201':
                return ['code' => $code, 'message' => 'Failure'];
                break;
            case '7202':
                return ['code' => $code, 'message' => 'Required header is not given'];
                break;
            case '7203':
                return ['code' => $code, 'message' => 'Unauthorized'];
                break;
            case '7204':
                return ['code' => $code, 'message' => 'Bill does not exist'];
                break;
            case '7205':
                return ['code' => $code, 'message' => 'Invalid service provider'];
                break;
            case '7206':
                return ['code' => $code, 'message' => 'Service provider is not active'];
                break;
            case '7207':
                return ['code' => $code, 'message' => 'Duplicate payment'];
                break;
            case '7208':
                return ['code' => $code, 'message' => 'Invalid business account'];
                break;
            case '7209':
                return ['code' => $code, 'message' => 'Business account is not active'];
                break;
            case '7210':
                return ['code' => $code, 'message' => 'Collection account balance limit reached'];
                break;
            case '7211':
                return [
                    'code' => $code,
                    'message' => 'Payment service provider code did not match Bill service provider code'
                ];
                break;
            case '7212':
                return ['code' => $code, 'message' => 'Payment currency did not match Bill currency'];
                break;
            case '7213':
                return ['code' => $code, 'message' => 'Bill has expired'];
                break;
            case '7214':
                return ['code' => $code, 'message' => 'Insufficient amount paid'];
                break;
            case '7215':
                return ['code' => $code, 'message' => 'Invalid payment service provider'];
                break;
            case '7216':
                return ['code' => $code, 'message' => 'Payment service provider is not active'];
                break;
            case '7217':
                return ['code' => $code, 'message' => 'No payer email or phone number'];
                break;
            case '7218':
                return ['code' => $code, 'message' => 'Wrong payer identity'];
                break;
            case '7219':
                return ['code' => $code, 'message' => 'Wrong currency'];
                break;
            case '7220':
                return ['code' => $code, 'message' => 'Sub Service provider is inactive'];
                break;

            case '7221':
                return ['code' => $code, 'message' => 'Wrong bill equivalent amount'];
                break;
            case '7222':
                return ['code' => $code, 'message' => 'Wrong bill miscellaneous amount'];
                break;
            case '7223':
                return ['code' => $code, 'message' => 'Invalid or inactive gfs or service type'];
                break;
            case '7224':
                return ['code' => $code, 'message' => 'Wrong bill amount'];
                break;
            case '7225':
                return ['code' => $code, 'message' => 'Invalid bill reference number or code'];
                break;
            case '7226':
                return ['code' => $code, 'message' => 'Duplicate bill information'];
                break;
            case '7227':
                return ['code' => $code, 'message' => 'Blank bill identification number'];
                break;
            case '7228':
                return ['code' => $code, 'message' => 'Invalid sub service provider'];
                break;
            case '7229':
                return ['code' => $code, 'message' => 'Wrong bill item gfs or payment type code'];
                break;
            case '7230':
                return ['code' => $code, 'message' => 'Wrong bill generation date'];
                break;
            case '7231':
                return ['code' => $code, 'message' => 'Wrong bill expiry date'];
                break;
            case '7232':
                return ['code' => $code, 'message' => 'Consumer already started by another process'];
                break;
            case '7233':
                return ['code' => $code, 'message' => 'Consumer already stopped by another process'];
                break;
            case '7234':
                return ['code' => $code, 'message' => 'Wrong bill payment option'];
                break;

            case '7235':
                return ['code' => $code, 'message' => 'Bill creation completed successfully'];
                break;
            case '7236':
                return ['code' => $code, 'message' => 'Bill creation completed with errors'];
                break;

            case '7237':
                return ['code' => $code, 'message' => 'Bill detail creation completed successfully'];
                break;
            case '7238':
                return ['code' => $code, 'message' => 'Bill detail creation completed with errors'];
                break;
            case '7239':
                return ['code' => $code, 'message' => 'No external bill system settings found'];
                break;
            case '7240':
                return ['code' => $code, 'message' => 'Failed to save transaction'];
                break;
            case '7241':
                return ['code' => $code, 'message' => 'Invalid session'];
                break;
            case '7242':
                return ['code' => $code, 'message' => 'Invalid request data'];
                break;
            case '7243':
                return ['code' => $code, 'message' => 'Invalid credit account'];
                break;
            case '7244':
                return ['code' => $code, 'message' => 'Invalid transfer amount'];
                break;
            case '7245':
                return ['code' => $code, 'message' => 'Invalid credit account name'];
                break;
            case '7246':
                return ['code' => $code, 'message' => 'Invalid debit account'];
                break;
            case '7247':
                return ['code' => $code, 'message' => 'Invalid transfer transaction description'];
                break;
            case '7248':
                return ['code' => $code, 'message' => 'Invalid debitor bic'];
                break;
            case '7249':
                return ['code' => $code, 'message' => 'Wrong transfer date'];
                break;
            case '7250':
                return ['code' => $code, 'message' => 'Invalid value in transfer reserved field one'];
                break;
            case '7251':
                return ['code' => $code, 'message' => 'Invalid transfer transaction number'];
                break;
            case '7252':
                return ['code' => $code, 'message' => 'Transfer transaction created successfully'];
                break;
            case '7253':
                return ['code' => $code, 'message' => 'Transfer transaction created with errors'];
                break;
            case '7254':
                return ['code' => $code, 'message' => 'Invalid use payment reference, use "Y" or "N"'];
                break;
            case '7255':
                return ['code' => $code, 'message' => 'Invalid item billed amount'];
                break;
            case '7256':
                return ['code' => $code, 'message' => 'Invalid item equivalent amount'];
                break;
            case '7257':
                return ['code' => $code, 'message' => 'Invalid item miscellaneous amount'];
                break;
            case '7258':
                return [
                    'code' => $code,
                    'message' => 'Total item billed amount mismatches the bill amount'
                ];
                break;
            case '7259':
                return [
                    'code' => $code,
                    'message' => 'Total item equivalent amount mismatches the bill equivalent amount'
                ];
                break;
            case '7260':
                return [
                    'code' => $code,
                    'message' => 'Total item miscellaneous amount mismatches the bill miscellaneous amount'
                ];
                break;
            case '7261':
                return ['code' => $code, 'message' => 'Defect bill saved successfully'];
                break;
            case '7262':
                return ['code' => $code, 'message' => 'Defect bill saved with errors'];
                break;
            case '7263':
                return ['code' => $code, 'message' => 'Defect bill items saved successfully'];
                break;
            case '7264':
                return ['code' => $code, 'message' => 'Defect bill items saved with errors'];
                break;
            case '7265':
                return ['code' => $code, 'message' => 'Bill items saved successfully'];
                break;
            case '7266':
                return ['code' => $code, 'message' => 'Bill items saved with errors'];
                break;
            case '7267':
                return ['code' => $code, 'message' => 'Invalid email address'];
                break;
            case '7268':
                return ['code' => $code, 'message' => 'Invalid phone number'];
                break;
            case '7269':
                return ['code' => $code, 'message' => 'Invalid or inactive Service Provider System Id'];
                break;
            case '7270':
                return ['code' => $code, 'message' => 'Transfer transaction update completed successfully'];
                break;
            case '7271':
                return ['code' => $code, 'message' => 'Transfer transaction update completed with errors'];
                break;
            case '7272':
                return ['code' => $code, 'message' => 'Defect transfer transaction saved successfully'];
                break;
            case '7273':
                return ['code' => $code, 'message' => 'Defect transfer transaction saved with errors'];
                break;
            case '7274':
                return ['code' => $code, 'message' => 'Duplicate transfer transaction'];
                break;
            case '7275':
                return ['code' => $code, 'message' => 'Invalid Service Provider Payer Id'];
                break;
            case '7276':
                return ['code' => $code, 'message' => 'Invalid Service Provider Payer Name'];
                break;
            case '7277':
                return [
                    'code' => $code,
                    'message' => 'Invalid bill description'
                ];
                break;
            case '7278':
                return [
                    'code' => $code,
                    'message' => 'Invalid bill approval user'
                ];
                break;
            case '7279':
                return [
                    'code' => $code,
                    'message' => 'Bill already settled'
                ];
                break;
            case '7280':
                return [
                    'code' => $code,
                    'message' => 'Bill expired and bill move process failed'
                ];
                break;
            case '7281':
                return [
                    'code' => $code,
                    'message' => 'Invalid payment transaction date'
                ];
                break;
            case '7282':
                return [
                    'code' => $code,
                    'message' => 'Invalid payer email or phone number'
                ];
                break;
            case '7283':
                return [
                    'code' => $code,
                    'message' => 'Bill has been cancelled'
                ];
                break;
            case '7284':
                return [
                    'code' => $code,
                    'message' => 'Payment currency did not match collection account currency'
                ];
                break;
            case '7285':
                return [
                    'code' => $code,
                    'message' => 'Invalid bill generation user'
                ];
                break;
            case '7286':
                return [
                    'code' => $code,
                    'message' => 'Bill cancellation process failed'
                ];
                break;
            case '7287':
                return [
                    'code' => $code,
                    'message' => 'Bill reference number does not meet required bill control number specifications'
                ];
                break;
            case '7288':
                return [
                    'code' => $code,
                    'message' => 'Disbursement request did not match signature'
                ];
                break;
            case '7289':
                return [
                    'code' => $code,
                    'message' => 'Invalid batch generated date'
                ];
                break;
            case '7290':
                return [
                    'code' => $code,
                    'message' => 'Total batch amount cannot be zero(0)'
                ];
                break;
            case '7291':
                return [
                    'code' => $code,
                    'message' => 'Total batch amount is not equal to summation of items(transactions)'
                ];
                break;
            case '7292':
                return [
                    'code' => $code,
                    'message' => 'Duplicate disbursement batch'
                ];
                break;
            case '7293':
                return [
                    'code' => $code,
                    'message' => 'Invalid disbursement pay option'
                ];
                break;
            case '7294':
                return [
                    'code' => $code,
                    'message' => 'Invalid disbursement batch scheduled date'
                ];
                break;
            case '7295':
                return [
                    'code' => $code,
                    'message' => 'Invalid disbursement notification template'
                ];
                break;
            case '7296':
                return [
                    'code' => $code,
                    'message' => 'Disbursement notification template is not active'
                ];
                break;
            case '7297':
                return [
                    'code' => $code,
                    'message' => 'Inactive currency'
                ];
                break;
            case '7298':
                return [
                    'code' => $code,
                    'message' => 'Invalid currency for disbursement'
                ];
                break;


            case '7299':
                return [
                    'code' => $code,
                    'message' => 'Batch item(recipients) recipients should not exceed'
                ];
                break;
            case '7301':
                return [
                    'code' => $code,
                    'message' => 'Bill has been paid partially'
                ];
                break;
            case '7302':
                return [
                    'code' => $code,
                    'message' => 'Paid amount is not exact billed amount'
                ];
                break;
            case '7303':
                return [
                    'code' => $code,
                    'message' => 'Invalid Signature'
                ];
                break;
            case '7304':
                return [
                    'code' => $code,
                    'message' => 'Invalid Signature Configuration missing one of parameter
                                     (passphrase, keyalias, filename)'
                ];
                break;
            case '7305':
                return ['code' => $code, 'message' => 'Invalid batch start and end date'];
                break;
            case '7306':
                return ['code' => $code, 'message' => 'Batch has no item(transaction)'];
                break;
            case '7307':
                return [
                    'code' => $code,
                    'message' => 'lbl.message.0107 = Inconsistency batch start, end and generated date'
                ];
                break;
            case '7308':
                return [
                    'code' => $code,
                    'message' => 'Invalid value in transfer reserved field two'
                ];
                break;
            case '7309':
                return [
                    'code' => $code,
                    'message' => 'Invalid value in transfer reserved field three'
                ];
                break;
            case '7310':
                return [
                    'code' => $code,
                    'message' => 'Invalid transfer credit or debit account'
                ];
                break;
            case '7311':
                return [
                    'code' => $code,
                    'message' => 'Invalid GePG configurations missing one of parameter
                                     (gepgKeyFilePath, gepgPassphrase, gepgAlias)'
                ];
                break;
            case '7312':
                return [
                    'code' => $code,
                    'message' => 'Batch does not exist'
                ];
                break;
            case '7313':
                return [
                    'code' => $code,
                    'message' => 'Cancel is only for auto pay batch'
                ];
                break;
            case '7314':
                return [
                    'code' => $code,
                    'message' => 'Batch already on disbursement process, cancellation process failed'
                ];
                break;
            case '7315':
                return [
                    'code' => $code,
                    'message' => 'Batch cancellation process failed'
                ];
                break;
            case '7316':
                return [
                    'code' => $code,
                    'message' => 'Batch already canceled'
                ];
                break;
            case '7317':
                return [
                    'code' => $code,
                    'message' => 'Error on processing request'
                ];
                break;
            case '7318':
                return [
                    'code' => $code,
                    'message' => 'Invalid reconciliation request date'
                ];
                break;
            case '7319':
                return [
                    'code' => $code,
                    'message' => 'Reconciliation request date is out of allowable range'
                ];
                break;
            case '7320':
                return [
                    'code' => $code,
                    'message' => 'Invalid reconciliation request options'
                ];
                break;
            case '7321':
                return [
                    'code' => $code,
                    'message' => 'Request can not completed at this time, try later'
                ];
                break;
            case '7322':
                return [
                    'code' => $code,
                    'message' => 'Inactive communication protocol'
                ];
                break;
            case '7323':
                return [
                    'code' => $code,
                    'message' => 'Invalid code, mismatch of supplied code on information and header'
                ];
                break;
            case '7324':
                return [
                    'code' => $code,
                    'message' => 'No payment(s) found for specified bill control number'
                ];
                break;
            case '7325':
                return [
                    'code' => $code,
                    'message' => 'Request to partner application composed'
                ];
                break;
            case '7326':
                return [
                    'code' => $code,
                    'message' => 'Request sent to partner application(system)'
                ];
                break;
            case '7327':
                return [
                    'code' => $code,
                    'message' => 'Request sent to partner application(system) with no content response'
                ];
                break;
            case '7328':
                return [
                    'code' => $code,
                    'message' => 'Request not received successful with partner application(system)'
                ];
                break;
            case '7329':
                return [
                    'code' => $code,
                    'message' => 'Processing or communication error on partner application(system)'
                ];
                break;
            case '7330':
                return [
                    'code' => $code,
                    'message' => 'Inactive or Unavailable, bill push to pay for specified Payment service Provider'
                ];
                break;
            case '7331':
                return ['code' => $code, 'message' => 'Paid Online Waiting Bank Confirmation'];
                break;
            case '0':
                return ['code' => $code, 'message' => 'Gateway Timed Out'];
                break;
            default:
                return  ['code' => $code, 'message' => 'Something went wrong, Please contact our support desk for help! Try Again'];
        }
    }
}
