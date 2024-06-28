<?php

namespace App\Traits;

use App\Enum\GeneralConstant;
use App\Enum\TaxVerificationStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\RiskIndicator;
use Exception;
use App\Models\Verification\TaxVerification;
use Illuminate\Support\Facades\Log;

trait TaxVerificationTrait
{
    use WorkflowProcesssingTrait, RiskIndicators;

    public function initiateVerificationApproval($return)
    {
        if ($return->verification && !$return->verification->marking) {
            $verification = $return->verification;
            try {
                $this->registerWorkflow(get_class($verification), $verification->id);
                $this->doTransition('start', []);
                $verification->status = TaxVerificationStatus::PENDING;
                $verification->save();
            } catch (Exception $e) {
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    public function triggerTaxVerifications($taxReturn, $authenticatedUser)
    {
        if ($taxReturn->return == null || $authenticatedUser == null) {
            throw new Exception('Return Object or Authenticated User Object is null');
        }

        // Check for risk indicators in $taxReturn
        $riskIndicators = $this->checkRiskIndicators($taxReturn);

        // Create tax verification only if there are risk indicators
        if (count($riskIndicators) > 0) {
            $childReturn = $taxReturn->return;
            $data = [
                'tax_return_id' => $childReturn->id ?? '',
                'tax_return_type' => get_class($childReturn),
                'business_id' => $childReturn->business_id,
                'location_id' => $childReturn->business_location_id ?? null,
                'tax_type_id' => $childReturn->tax_type_id,
                'created_by_id' => $authenticatedUser->id ?? null,
                'created_by_type' => get_class($authenticatedUser),
                'status' => 'pending',
            ];

            try {
                $verification = TaxVerification::create($data);
                $verification->riskIndicators()->attach($riskIndicators);

                if ($taxReturn->total_amount == GeneralConstant::ZERO_INT){
                    $this->initiateVerificationApproval($taxReturn);
                }
            } catch (Exception $e) {
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }
}
