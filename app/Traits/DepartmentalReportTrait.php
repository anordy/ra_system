<?php

namespace App\Traits;

use App\Exports\ReturnReportExport;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

trait DepartmentalReportTrait
{
    public function getRecords($parameters)
    {
        // dd($parameters);
        if ($parameters['department_type'] == 'non-tax-revenue') {
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                ->join('tax_types', 'tax_types.id', 'tax_returns.tax_type_id')
                ->where('tax_types.category', 'other');
            if ($parameters['non_tax_type_id'] == 'all') {
                $model->whereIn('tax_types.code', ['land-lease', 'airport-service-charge', 'road-license-fee', 'airport-service-charge', 'seaport-service-charge', 'seaport-transport-charge']);
            } else {
                $model->where('tax_types.code', $parameters['non_tax_type_code']);
            }
        } else {
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                ->join('tax_types', 'tax_types.id', 'tax_returns.tax_type_id')
                ->where('tax_types.category', 'main');
            if ($parameters['tax_type_id'] != 'all') {
                if ($parameters['tax_type_code'] == 'vat') {
                    $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                        ->leftJoin('vat_returns', 'vat_returns.id', 'tax_returns.return_id')
                        ->where('tax_returns.tax_type_id', $parameters['tax_type_id']);

                    if ($parameters['vat_type'] != 'All') {
                        $model->where('tax_returns.sub_vat_id', $parameters['vat_type']);
                    }
                } 
            }else {
                $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id');
            }
        }

        $model->join('businesses', 'businesses.id', 'business_locations.business_id');


        //check if taxpayer is larger-taxpayer-department
        if ($parameters['department_type'] == 'large-taxpayer') {
            $model->where('businesses.is_business_lto', true);
        }

        //check if taxpayer is domestic-taxpayer-department
        if ($parameters['department_type'] == 'domestic-taxes') {
            $model->where('businesses.is_business_lto', false);
        }

        if ($parameters['payment_status'] != 'all') {
            //get all paid returns
            if ($parameters['payment_status'] == 'paid') {
                $returns = $model->where('tax_returns.payment_status', 'complete');
            }

            //get all un-paid returns
            if ($parameters['payment_status'] == 'unpaid') {
                $returns = $model->where('tax_returns.payment_status', '!=', 'complete');
            }
        }else{
            $returns = $model;
        }
        

        //get tax regions
        if ($parameters['department_type'] != 'large-taxpayer') {
            $returns->whereIn('business_locations.tax_region_id', $parameters['tax_regions']);
        }

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
        $start = Carbon::parse($parameters['range_start'])->startOfDay();
        $end = Carbon::parse($parameters['range_end'])->startOfDay();
        $returns->whereDate('tax_returns.created_at', '>=', $start);
        $returns->whereDate('tax_returns.created_at', '<=', $end)->orderBy('tax_returns.created_at', 'asc');
        return $returns;
    }

    // public function exportExcelReport($parameters)
    // {
    //     $records = $this->getRecords($parameters);
    //     if ($records->count() < 1) {
    //         $this->alert('error', 'No Records Found in the selected criteria');
    //         return;
    //     }

    //     $fileName = $parameters['tax_type_name'] . '_' . $parameters['filing_report_type'] . ' - ' . '.xlsx';
    //     $title    = $parameters['filing_report_type'] . ' For' . $parameters['tax_type_name'];
    //     $this->alert('success', 'Exporting Excel File');
    //     return Excel::download(new ReturnReportExport($records, $title, $parameters), $fileName);
    // }

    // public function exportPdfReport($parameters)
    // {
    //     $records = $this->getRecords($parameters);
    //     if ($records->count() < 1) {
    //         $this->alert('error', 'No Records Found in the selected criteria');

    //         return;
    //     }
    //     $this->alert('success', 'Exporting Pdf File');

    //     return redirect()->route('reports.returns.download.pdf', encrypt(json_encode($parameters)));
    // }

    // public function previewReport($parameters)
    // {
    //     $records = $this->getRecords($parameters);
    //     if ($records->count() < 1) {
    //         $this->hasData = false;
    //         $this->alert('error', 'No Records Found in the selected criteria');
    //         return;
    //     }else{
    //         $this->hasData = true;
    //     }
    // }
}
