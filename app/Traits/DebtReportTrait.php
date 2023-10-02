<?php
namespace App\Traits;

use App\Enum\ReturnCategory;
use App\Models\Business;
use App\Models\Debts\DebtWaiver;
use App\Models\Returns\TaxReturn;
use App\Models\Debts\DemandNotice;
use App\Models\Installment\Installment;
use App\Models\TaxAssessments\TaxAssessment;
use Carbon\Carbon;

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
        if (isset($parameters['year']) && $parameters['year'] == 'all'){
            return $model->get();
        }

        if (isset($parameters['period']) && $parameters['period'] == 'Annual'){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        if (isset($parameters['period']) && $parameters['period'] == 'Monthly'){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->startOfMonth(),
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->endOfMonth()
            ]);
        }

        // TODO: Implement for semi annual and quarterly correctly
        if (isset($parameters['period']) && $parameters['period'] == 'Quarterly'){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        // TODO: Implement for semi annual and quarterly correctly
        if (isset($parameters['period']) && $parameters['period'] == 'Semi-Annual'){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        if ($parameters['range_start'] == [] || $parameters['range_end'] == []) {
            return $model->orderBy("created_at", 'asc');
        }
        if ($parameters['range_start'] == null || $parameters['range_end'] == null) {
            return $model->orderBy("created_at", 'asc');
        }

        return $model->whereBetween("created_at", [$parameters['range_start'], $parameters['range_end']])->orderBy("created_at", 'asc');
    }
}
