<?php

namespace App\Traits;

use App\Enum\ReportStatus;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;

trait ReturnReportTrait
{
    public function getRecords($parameters)
    {
        try {
            if (!array_key_exists('tax_type_id', $parameters) && !array_key_exists('tax_type_code', $parameters)) {
                throw new \Exception('Missing tax_type_id or tax_type_code key in parameters on ReturnReportTrait in getRecords()');
            }

            if ($parameters['tax_type_id'] == ReportStatus::all) {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id');
            } elseif ($parameters['tax_type_code'] == ReportStatus::Vat) {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                    ->leftJoin('vat_returns', 'vat_returns.id', 'tax_returns.return_id')
                    ->where('tax_returns.tax_type_id', $parameters['tax_type_id']);

                if (array_key_exists('vat_type', $parameters) && $parameters['vat_type'] != ReportStatus::All) {
                    $model->where('tax_returns.sub_vat_id', $parameters['vat_type']);
                }

            } else {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')->where('tax_returns.tax_type_id', $parameters['tax_type_id']);
            }

            if (!array_key_exists('type', $parameters)) {
                throw new \Exception('Missing type key in parameters on ReturnReportTrait in getRecords()');
            }

            if ($parameters['type'] == ReportStatus::FILING) {
                if (!array_key_exists('filing_report_type', $parameters)) {
                    throw new \Exception('Missing filling_report_type key in parameters on ReturnReportTrait in getRecords()');
                }

                if ($parameters['filing_report_type'] == ReportStatus::ON_TIME_FILINGS) {
                    $returns = $model->whereRaw("CAST(tax_returns.filing_due_date as date) - CAST(tax_returns.created_at as date) >= 0");

                } elseif ($parameters['filing_report_type'] == ReportStatus::LATE_FILINGS) {
                    $returns = $model->whereRaw("CAST(tax_returns.created_at as date) - CAST(tax_returns.filing_due_date as date) > 0");

                } elseif ($parameters['filing_report_type'] == ReportStatus::ALL_FILINGS) {
                    $returns = $model;
                } elseif ($parameters['filing_report_type'] == ReportStatus::TAX_CLAIMS) {
                    $returns = $model->where('tax_returns.has_claim', true);

                } elseif ($parameters['filing_report_type'] == ReportStatus::NILL_RETURNS) {
                    $returns = $model->where('tax_returns.is_nill', true);
                }
            } elseif ($parameters['type'] == ReportStatus::PAYMENT) {
                if (!array_key_exists('payment_report_type', $parameters)) {
                    throw new \Exception('Missing payment_report_type key in parameters on ReturnReportTrait in getRecords()');
                }
                if ($parameters['payment_report_type'] == ReportStatus::ON_TIME_PAID_RETURNS) {
                    $returns = $model->whereRaw("CAST(tax_returns.payment_due_date as date) - CAST(tax_returns.paid_at as date) >= 0");

                } elseif ($parameters['payment_report_type'] == ReportStatus::LATE_PAID_RETURNS) {
                    $returns = $model->where('tax_returns.payment_status', 'complete')->whereRaw("CAST(tax_returns.paid_at as date) - CAST(tax_returns.payment_due_date as date) > 0");

                } elseif ($parameters['payment_report_type'] == ReportStatus::UNPAID_RETURNS) {
                    $returns = $model->where('tax_returns.payment_status', '!=', 'complete');

                } elseif ($parameters['payment_report_type'] == ReportStatus::ALL_PAID_RETURNS) {
                    $returns = $model->where('tax_returns.payment_status', 'complete');
                }
            }

            if (!array_key_exists('tax_regions', $parameters) && !array_key_exists('region', $parameters)) {
                throw new \Exception('Missing tax_regions or region key in parameters on ReturnReportTrait in getRecords()');
            }

            //get tax regions
            $returns->whereIn('business_locations.tax_region_id', $parameters['tax_regions']);
            //get physical location
            if ($parameters['region'] !== ReportStatus::all) {
                $returns->where('business_locations.region_id', $parameters['region']);
                if (array_key_exists('district', $parameters) && $parameters['district'] !== ReportStatus::all) {
                    $returns->where('business_locations.district_id', $parameters['district']);
                    if (array_key_exists('ward', $parameters) && $parameters['ward'] !== ReportStatus::all) {
                        $returns->where('business_locations.ward_id', $parameters['ward']);
                    }
                }
            }

            return $this->getSelectedRecords($returns, $parameters);
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

    public function getSelectedRecords($returns, $parameters)
    {
        try {
            if (!array_key_exists('range_start', $parameters) && !array_key_exists('range_end', $parameters)) {
                throw new \Exception('Missing range_start or range_end key in parameters on ReturnReportTrait in getRecords()');
            }

            $start = Carbon::parse($parameters['range_start'])->startOfDay();
            $end = Carbon::parse($parameters['range_end'])->startOfDay();
            $returns->whereDate('tax_returns.created_at', '>=', $start);
            $returns->whereDate('tax_returns.created_at', '<=', $end)->orderBy('tax_returns.created_at', 'asc');
            return $returns;
        } catch (\Exception $exception) {
            throw $exception;
        }

    }


    public function exportPdfReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }
        $this->customAlert('success', 'Exporting Pdf File');

        return redirect()->route('reports.returns.download.pdf', encrypt(json_encode($parameters)));
    }

    public function previewReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->hasData = false;
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        } else {
            $this->hasData = true;
        }
    }
}
