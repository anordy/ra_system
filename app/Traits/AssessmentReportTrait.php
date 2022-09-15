<?php
namespace App\Traits;

use App\Models\TaxAssessments\TaxAssessment;

trait AssessmentReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['tax_type_id'] == 'all') {
            $model = TaxAssessment::query();
        } else {
            $model = TaxAssessment::query()->where('tax_type_id', $parameters['tax_type_id']);
        }

        return $this->getSelectedRecords($model,$parameters);
    }

    public function getSelectedRecords($model,$parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $model->orderBy("tax_assessments.created_at", 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $model->orderBy("tax_assessments.created_at", 'asc');
        }

        return $model->whereBetween("tax_assessments.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("tax_assessments.created_at", 'asc');
    }
}
