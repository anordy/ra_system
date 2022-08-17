<?php

namespace App\Traits;

use App\Models\TaxAssessments\TaxAssessmentHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

trait TaxAssessmentDisputeTrait
{

    /**
     * Trigger audit.
     *
     * @param  $modal_class ie. User::class, Business::class
     * @param  $event ie. created, updated, deleted
     * @param  $tags ie. Password
     * @param  $auditable_id ie. 1 id of the operated process
     *
     * @return array
     */
    public function addDisputeToAssessment($assessment, $app_status, $principal_amount, $penalty, $interest, $paid_amount)
    {

        if ($app_status == null || $app_status == null) {
            throw new Exception('Assessment Object can not be null');
        } else {

            $data = [
                'principal_amount' => $principal_amount,
                'interest_amount' => $interest,
                'penalty_amount' => $penalty,
                'total_amount' => $principal_amount + $penalty + $interest,
                'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString() ?? null,
                'paid_amount' => $paid_amount,
                'app_status' => $app_status,
            ];



            try {
                TaxAssessmentHistory::create([
                    'tax_assessment_id' => $assessment->id,
                    'principal_amount' => $assessment->principal_amount,
                    'interest_amount' => $assessment->interest_amount,
                    'penalty_amount' => $assessment->penalty_amount,
                    'total_amount' => $assessment->total_amount,
                    'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString() ?? null,
                    'paid_amount' => $assessment->paid_amount,
                    'app_status' => $assessment->app_status,
                ]);

                $assessment->update($data);
            } catch (Exception $e) {
                Log::error($e);
            }
        }
    }

}
