<?php

namespace App\Traits;

use App\Exports\ReturnReportExport;
use App\Models\Returns\TaxReturn;
use Maatwebsite\Excel\Facades\Excel;

trait ReturnReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['tax_type_id'] == 'all') {
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id');
        } else {
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')->where('tax_returns.tax_type_id', $parameters['tax_type_id']);
        }
        if ($parameters['type'] == 'Filing') {
            if ($parameters['filing_report_type'] == 'On-Time-Filings') {
                $returns = clone $model->where('tax_returns.filing_due_date', '>=', 'tax_returns.created_at');
            } elseif ($parameters['filing_report_type'] == 'Late-Filings') {
                $returns = clone $model->where('tax_returns.filing_due_date', '<', 'tax_returns.created_at');
            } elseif ($parameters['filing_report_type'] == 'All-Filings') {
                $returns = clone $model;
            } elseif ($parameters['filing_report_type'] == 'Tax-Claims') {
                $returns = clone $model->where('tax_returns.has_claim', true);
            } elseif ($parameters['filing_report_type'] == 'Nill-Returns') {
                $returns = clone $model->where('tax_returns.is_nill', true);
            }
        } elseif ($parameters['type'] == 'Payment') {
            if ($parameters['payment_report_type'] == 'On-Time-Paid-Returns') {
                $returns = clone $model->whereNotNull('tax_returns.paid_at');
                $returns = $returns->where('tax_returns.payment_due_date', '>=', 'tax_returns.paid_at');
            } elseif ($parameters['payment_report_type'] == 'Late-Paid-Returns') {
                $returns = clone $model->whereNotNull('tax_returns.paid_at');
                $returns = $returns->where('tax_returns.payment_due_date', '<', 'tax_returns.paid_at', );
            } elseif ($parameters['payment_report_type'] == 'Unpaid-Returns') {
                $returns = clone $model->whereNull('tax_returns.paid_at');
            } elseif ($parameters['payment_report_type'] == 'All-Paid-Returns') {
                $returns = clone $model->whereNotNull('tax_returns.paid_at');
            }
        }

        //get tax regions
        $returns->whereIn('business_locations.tax_region_id', $parameters['tax_regions']);
        //get physical location
        if ($parameters['region'] !== 'all') {
            $returns->where('business_locations.region_id', $parameters['region']);
            if ($parameters['district'] !== 'all') {
                $returns->where('business_locations.district_id', $parameters['district']);
                if ($parameters['ward'] !== 'all') {
                    $returns->where('business_locations.ward_id', $parameters['ward']);
                }
            }
        }

        return $this->getSelectedRecords($returns, $parameters);
    }

    public function getSelectedRecords($returns, $parameters)
    {
        // dd($returns->get() ,$parameters);
        $dates = $parameters['dates'];
        if ($dates == []) {
            return $returns->orderBy('tax_returns.created_at', 'asc');
        }
        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return $returns->orderBy('tax_returns.created_at', 'asc');
        }

        return $returns->whereBetween('tax_returns.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('tax_returns.created_at', 'asc');
    }

    public function exportExcelReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');

            return;
        }

        if ($parameters['year'] == 'all') {
            $fileName = $parameters['tax_type_name'] . '_' . $parameters['filing_report_type'] . '.xlsx';
            $title    = $parameters['filing_report_type'] . ' For' . $parameters['tax_type_name'];
        } else {
            $fileName = $parameters['tax_type_name'] . '_' . $parameters['filing_report_type'] . ' - ' . $parameters['year'] . '.xlsx';
            $title    = $parameters['filing_report_type'] . ' For' . $parameters['tax_type_name'] . ' ' . $parameters['year'];
        }
        $this->alert('success', 'Exporting Excel File');

        return Excel::download(new ReturnReportExport($records, $title, $parameters), $fileName);
    }

    public function exportPdfReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');

            return;
        }
        $this->alert('success', 'Exporting Pdf File');

        return redirect()->route('reports.returns.download.pdf', encrypt(json_encode($parameters)));
    }

    public function previewReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->alert('error', 'No Records Found in the selected criteria');

            return;
        }

        return redirect()->route('reports.returns.preview', encrypt(json_encode($parameters)));
    }
}
