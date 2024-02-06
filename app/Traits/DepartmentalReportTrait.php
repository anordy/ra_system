<?php

namespace App\Traits;

use App\Exports\DepartmentalReportExport;
use App\Models\Region;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

trait DepartmentalReportTrait
{
    public function getRecords($parameters)
    {
        if ($parameters['department_type'] == 'non-tax-revenue') {
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id');

            if($parameters['non_tax_revenue_selected'] == 'all'){
                foreach ($parameters['non_tax_revenue_ids'] as $id) {
                    if (TaxReturn::where("$id", '>', 0)->exists()) {
                        $model->where('tax_returns.' . $id, '>', 0);
                    }
                }
            }

            if ($parameters['non_tax_revenue_selected'] != 'all') {
                $column = $parameters['non_tax_revenue_selected'];
                $model->where('tax_returns.' . $column, '>', 0);
            }

        } else {
            //get all returns associsted with main tax types
            $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                ->join('tax_types', 'tax_types.id', 'tax_returns.tax_type_id');
            if ($parameters['tax_type_id'] != 'all') {
                //check if selected tax-type is vat
                if ($parameters['tax_type_code'] == 'vat') {
                    //if it is vat, join tax-returns table with vat_returns table
                    $model = TaxReturn::leftJoin('business_locations', 'tax_returns.location_id', 'business_locations.id')
                        ->leftJoin('vat_returns', 'vat_returns.id', 'tax_returns.return_id')
                        ->where('tax_returns.tax_type_id', $parameters['tax_type_id']);

                    //if subvat type is not all
                    if ($parameters['vat_type'] != 'All') {
                        //get returns for a given subvat selected
                        $model->where('tax_returns.sub_vat_id', $parameters['vat_type']);
                    }
                }
            } else {
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

        //check if department-type is pemba
        if ($parameters['department_type'] == 'pemba') {
            if ($parameters['region'] == 'all') {
                $pembaRegions = Region::select('id', 'name')->where('location', 'pemba')->pluck('id')->toArray();
                $model->whereIn('business_locations.region_id', $pembaRegions);
            }
        }

        $returns = $model;

        if ($parameters['payment_status'] != 'all') {
            //get all paid returns
            if ($parameters['payment_status'] == 'paid') {
                $returns = $model->where('tax_returns.payment_status', 'complete');
            }

            //get all un-paid returns
            if ($parameters['payment_status'] == 'unpaid') {
                $returns = $model->where('tax_returns.payment_status', '!=', 'complete');
            }
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

    public function exportExcelReport($parameters)
    {
        $records = $this->getRecords($parameters);
        if ($records->count() < 1) {
            $this->customAlert('error', 'No Records Found in the selected criteria');
            return;
        }

        $fileName = $parameters['department_type'] . '.xlsx';
        $title    = $parameters['department_type'];
        $this->customAlert('success', 'Exporting Excel File');
        return Excel::download(new DepartmentalReportExport($records, $title, $parameters), $fileName);
    }
}
