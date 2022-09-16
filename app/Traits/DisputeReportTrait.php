<?php
namespace App\Traits;

use App\Models\Disputes\Dispute;
use App\Models\TaxType;

trait DisputeReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['tax_type_id'] == 'all') {
            $model = Dispute::query();
        } else {
            $tax_type = TaxType::find($parameters['tax_type_id']);
            $model = Dispute::query()->where('category', $tax_type->name);
        }

        return $this->getSelectedRecords($model,$parameters);
    }

    public function getSelectedRecords($model,$parameters)
    {
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $model->orderBy("disputes.created_at", 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $model->orderBy("disputes.created_at", 'asc');
        }

        return $model->whereBetween("disputes.created_at", [$dates['startDate'], $dates['endDate']])->orderBy("disputes.created_at", 'asc');
    }
}
