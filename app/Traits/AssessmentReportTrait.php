<?php
namespace App\Traits;

use App\Models\TaxAssessments\TaxAssessment;

trait AssessmentReportTrait
{
    public function getRecords($parameters)
    {
        if (!isset($parameters['dates']) ||
            !isset($parameters['tax_type_id'])) {
            throw new \InvalidArgumentException("Missing required parameters");
        }

        if ($parameters['tax_type_id'] == 'all') {
            $model = TaxAssessment::query();
        } else {
            $model = TaxAssessment::query()->where('tax_type_id', $parameters['tax_type_id']);
        }

        $model->select([
            'id',
            'business_id',
            'location_id',
            'tax_type_id',
            'assessment_id',
            'currency',
            'assessment_type',
            'paid_amount',
            'interest_amount',
            'penalty_amount',
            'principal_amount',
            'payment_method',
            'total_amount',
            'outstanding_amount',
        ]);

        return $this->getSelectedRecords($model,$parameters);
    }

    public function getSelectedRecords($model,$parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $model->orderBy("tax_assessments.created_at", 'asc');
        }

        if (!isset($dates['startDate']) ||
            !isset($dates['endDate'])) {
            throw new \InvalidArgumentException("Missing required parameters");
        }

        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $model->orderBy("tax_assessments.created_at", 'asc');
        }

        return $model->whereBetween("tax_assessments.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("tax_assessments.created_at", 'asc');
    }
}
