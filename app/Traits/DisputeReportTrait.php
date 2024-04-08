<?php

namespace App\Traits;

use App\Enum\ReportStatus;
use App\Models\Disputes\Dispute;
use App\Models\TaxType;

trait DisputeReportTrait
{
    public function getRecords($parameters)
    {
        if (!isset($parameters['tax_type_id'])) {
            throw new \Exception('Missing tax_type_id key in parameters on DisputeReportTrait in getRecords()');
        }

        try {
            if ($parameters['tax_type_id'] == ReportStatus::all) {
                $model = Dispute::query();
            } else {
                $tax_type = TaxType::findOrFail($parameters['tax_type_id']);
                $model = Dispute::query()->where('category', $tax_type->name);
            }

            return $this->getSelectedRecords($model, $parameters);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getSelectedRecords($model, $parameters)
    {
        if (!isset($parameters['dates'])) {
            throw new \Exception('Missing dates key in parameters on DisputeReportTrait in getSelectedRecords()');
        }

        try {
            $dates = $parameters['dates'];
            if ($dates == []) {
                return $model->orderBy("disputes.created_at", 'asc');
            }

            if (!array_key_exists('startDate', $dates) && !array_key_exists('endDate', $dates)) {
                throw new \Exception('Missing startDate and endDate keys in parameters on DisputeReportTrait in getSelectedRecords()');
            }

            if ($dates['startDate'] == null || $dates['endDate'] == null) {
                return $model->orderBy("disputes.created_at", 'asc');
            }

            return $model->whereBetween("disputes.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("disputes.created_at", 'asc');
        } catch (\Exception $exception) {
            throw $exception;
        }

    }
}
