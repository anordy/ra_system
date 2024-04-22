<?php
namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Enum\ReportStatus;
use App\Models\Business;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\PublicServiceReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait PublicServiceReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['report_type'] == ReportStatus::PS_REG_REPORT) {

            $query = PublicServiceMotor::query()
                ->select([
                    'businesses.name',
                    'mvr_registrations.plate_number',
                    'mvr_registration_types.name as registration_type',
                    'public_service_payments.payment_months',
                    'mvr_classes.name as class_name',
                    'public_service_motors.created_at',
                    'public_service_motors.approved_on',
                    'public_service_motors.status as public_service_status',
                    'mvr_registrations.status as mvr_status',
                    'mvr_registration_types.name as registration_type'
                ])
                ->leftJoin('businesses', 'public_service_motors.business_id', 'businesses.id')
                ->leftJoin('public_service_payments', 'public_service_motors.id', 'public_service_payments.public_service_motor_id')
                ->leftJoin('mvr_registrations', 'public_service_motors.mvr_registration_id', 'mvr_registrations.id')
                ->leftJoin('mvr_registration_types', 'mvr_registrations.mvr_registration_type_id', 'mvr_registration_types.id')
                ->leftJoin('mvr_classes', 'mvr_registrations.mvr_class_id', 'mvr_classes.id');

            if ($parameters['reg_type'] != ReportStatus::All) {
                $query->where('mvr_registrations.mvr_registration_type_id', $parameters['reg_type']);
            }

            if (isset($parameters['mvr_status']) && $parameters['mvr_status'] != ReportStatus::All){
                $query->where('mvr_registrations.status', $parameters['mvr_status']);
            }

            if (isset($parameters['public_service_status']) && $parameters['public_service_status'] != ReportStatus::All){
                $query->where('public_service_motors.status', $parameters['public_service_status']);
            }

        } else if ($parameters['report_type'] == ReportStatus::PS_PAYMENT_REPORT) {
            if ($parameters['payment_type'] == ReportStatus::All) {
                $paymentStatus = [BillStatus::COMPLETE, BillStatus::CN_GENERATING, BillStatus::CN_GENERATED];
            } else {
                $paymentStatus = $parameters['payment_type'] === ReportStatus::PAID ? [BillStatus::COMPLETE] : [BillStatus::CN_GENERATED, BillStatus::CN_GENERATING];
            }
            $query = PublicServiceReturn::query()->whereIn('payment_status', $paymentStatus);
        } else {
            throw new \Exception('Invalid report type selected on Public Service Report Trait Get Records');
        }

        return $this->getSelectedRecords($query, $parameters);
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
