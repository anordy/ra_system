<?php
namespace App\Traits;

use App\Enum\ReturnCategory;
use App\Models\Debts\DebtWaiver;
use App\Models\Returns\TaxReturn;
use App\Models\Debts\DemandNotice;
use App\Models\Installment\Installment;
use App\Models\TaxAssessments\TaxAssessment;

trait DebtReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['report_type'] == 'Waiver') {
            $model = DebtWaiver::query();
        } else if ($parameters['report_type'] == 'Assessments') {
            $model = TaxAssessment::query();
        }else if ($parameters['report_type'] == 'Installment') {
            $model = Installment::query();
        }else if ($parameters['report_type'] == 'Demand-Notice') {
            $model = DemandNotice::query();
        }else if ($parameters['report_type'] == 'Returns') {
            $model = TaxReturn::query()->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE]);
        }

        return $this->getSelectedRecords($model,$parameters);
    }

    public function getSelectedRecords($model,$parameters)
    {
        if ($parameters['range_start'] == [] || $parameters['range_end'] == []) {
            return $model->orderBy("created_at", 'asc');
        }
        if ($parameters['range_start'] == null || $parameters['range_end'] == null) {
            return $model->orderBy("created_at", 'asc');
        }

        return $model->whereBetween("created_at", [$parameters['range_start'], $parameters['range_end']])->orderBy("created_at", 'asc');
    }
}
