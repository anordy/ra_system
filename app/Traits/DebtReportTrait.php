<?php

namespace App\Traits;

use App\Enum\ReportStatus;
use App\Enum\ReturnCategory;
use App\Models\Debts\DebtWaiver;
use App\Models\Debts\DemandNotice;
use App\Models\Installment\Installment;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use Carbon\Carbon;

trait DebtReportTrait
{
    public function getRecords($parameters)
    {
        try {
            if (!isset($parameters['report_type'])) {
                throw new \Exception('Missing report type in parameters on DebtReportTrait getRecords()');
            }

            if ($parameters['report_type'] == ReportStatus::WAIVER) {
                $model = DebtWaiver::query();
            } else if ($parameters['report_type'] == ReportStatus::ASSESSMENTS) {
                $model = TaxAssessment::query();
            } else if ($parameters['report_type'] == ReportStatus::INSTALLMENT) {
                $model = Installment::query();
            } else if ($parameters['report_type'] == ReportStatus::DEMAND_NOTICE) {
                $model = DemandNotice::query();
            } else if ($parameters['report_type'] == ReportStatus::RETURNS) {
                $model = TaxReturn::query()->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE]);
            }

            return $this->getSelectedRecords($model, $parameters);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function getSelectedRecords($model, $parameters)
    {
        if (isset($parameters['year']) && $parameters['year'] == ReportStatus::all) {
            return $model;
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::ANNUAL) {
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::MONTHLY) {
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->startOfMonth(),
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->endOfMonth()
            ]);
        }

        // TODO: Implement for semi annual and quarterly correctly
        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::QUARTERLY) {
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        // TODO: Implement for semi annual and quarterly correctly
        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::SEMI_ANNUAL) {
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
