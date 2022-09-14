<?php

namespace App\Traits;

use App\Enum\TaxVerificationStatus;
use Exception;
use App\Models\Verification\TaxVerification;
use Illuminate\Support\Facades\Log;

trait TaxVerificationTrait
{
    use WorkflowProcesssingTrait;

    /**
     * Trigger audit.
     *
     * @param $taxReturn
     * @param $authenticatedUser
     * @return array
     * @throws Exception
     */
    public function triggerTaxVerifications($taxReturn, $authenticatedUser)
    {
        if ($taxReturn == null || $authenticatedUser == null) {
            throw new Exception('Return Object or Authenticated User Object is null');
        } else {

            $data = [
                'tax_return_id' => $taxReturn->id ?? '',
                'tax_return_type' => get_class($taxReturn),
                'business_id' => $taxReturn->business_id,
                'location_id' => $taxReturn->business_location_id ?? null,
                'tax_type_id' => $taxReturn->tax_type_id,
                'created_by_id' => $authenticatedUser->id ?? null,
                'created_by_type' => get_class($authenticatedUser),
                'status' => 'pending',
            ];

            try {
                $verification = TaxVerification::create($data);
                $this->registerWorkflow(get_class($verification), $verification->id);
                $this->doTransition('start', []);
                $verification->status = TaxVerificationStatus::PENDING;
                $verification->save();
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

    public function initiateVerificationApproval($return){
        if ($return->verification && !$return->verification->marking){
            $verification = $return->verification;
            try {
                $this->registerWorkflow(get_class($verification), $verification->id);
                $this->doTransition('start', []);
                $verification->status = TaxVerificationStatus::PENDING;
                $verification->save();
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }
}
