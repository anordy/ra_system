<?php
namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Enum\ReportStatus;
use App\Models\Business;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\PublicServiceReturn;
use Carbon\Carbon;

trait PublicServiceReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['report_type'] == ReportStatus::PS_REG_REPORT) {
            if ($parameters['reg_type'] == ReportStatus::All) {
                $model = Business::with('motors')->whereHas('motors', function ($query) {
                    $query->where('status', PublicServiceMotorStatus::REGISTERED);
                });
            } else {
                $model = Business::with('motors')->whereHas('motors', function ($query) use ($parameters) {
                    $query->where('status', PublicServiceMotorStatus::REGISTERED)
                        ->where('mvr_registration_type_id', $parameters['reg_type']);
                });
            }
        } else if ($parameters['report_type'] == ReportStatus::PS_PAYMENT_REPORT) {
            if ($parameters['payment_type'] == ReportStatus::All) {
                $paymentStatus = [BillStatus::COMPLETE, BillStatus::CN_GENERATING, BillStatus::CN_GENERATED];
            } else {
                $paymentStatus = $parameters['payment_type'] === ReportStatus::PAID ? [BillStatus::COMPLETE] : [BillStatus::CN_GENERATED, BillStatus::CN_GENERATING];
            }
            $model = PublicServiceReturn::query()->whereIn('payment_status', $paymentStatus);
        } else {
            throw new \Exception('Invalid report type selected on Public Service Report Trait Get Records');
        }

        return $this->getSelectedRecords($model, $parameters);
    }

    public function getSelectedRecords($model,$parameters)
    {
        if (isset($parameters['year']) && $parameters['year'] == ReportStatus::all){
            return $model;
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::ANNUAL){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::MONTHLY){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->startOfMonth(),
                Carbon::parse($parameters['year'] . "-" . $parameters['month'])->endOfMonth()
            ]);
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::QUARTERLY){
            return $model->whereBetween('created_at', [
                Carbon::parse($parameters['year'])->startOfYear(),
                Carbon::parse($parameters['year'])->endOfYear()
            ]);
        }

        if (isset($parameters['period']) && $parameters['period'] == ReportStatus::SEMI_ANNUAL){
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
