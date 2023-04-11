<?php

namespace App\Http\Livewire\LandLease;

use App\Models\BusinessLocation;
use App\Models\LandLease;
use App\Models\LeasePayment;
use App\Models\Taxpayer;
use App\Traits\LeasePaymentReportTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LeasePaymentReportTable extends DataTableComponent
{
    use CustomAlert, LeasePaymentReportTrait;

    public $dates = [];
    public $status;
    public $date_type;
    public $taxpayer_id;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function builder(): Builder
    {
        $dates = $this->dates;
        $status = $this->status;
        $date_type = $this->date_type;
        $taxpayer_id = $this->taxpayer_id;

        if ($dates == []) {
            $model = LeasePayment::query();
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $model = LeasePayment::query();
        } else {

            if ($this->date_type == 'payment_month') {
                $months = $this->getMonthList($dates);
                $years = $this->getYearList($dates);
                $model = LeasePayment::query()
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("land_leases.{$this->date_type}", $months)
                ->whereIn("financial_years.code", $years);
            } elseif ($this->date_type == 'payment_year') {
                $years = $this->getYearList($dates);
                $model = LeasePayment::query()
                ->leftJoin('financial_years', 'financial_years.id', 'lease_payments.financial_year_id')
                ->whereIn("financial_years.code", $years);

            }else {
                $model = LeasePayment::query()->whereBetween("lease_payments.{$this->date_type}", [$dates['startDate'], $dates['endDate']]);
            }
        }

        if ($status) {
            $model = clone $model->where('lease_payments.status', $status);
        }

        if ($taxpayer_id) {
            $model = clone $model->where('lease_payments.taxpayer_id', $taxpayer_id);
        }

        return $model->with('landLease', 'financialYear')->orderBy('lease_payments.created_at', 'asc');
    }

    public function refreshTable($parameter)
    {
        $this->dates = $parameter['dates'];
        $this->status = $parameter['status'];
        $this->taxpayer_id = $parameter['taxpayer_id'];
        $this->date_type = $parameter['date_type'];
        $this->builder();
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['land_lease_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make('DP Number', 'landLease.dp_number')
                ->format(function ($value, $row) {
                    $column = 'landlease.dp_number';
                    return $row[$column];
                })
                ->searchable()
                ->sortable(),
            Column::make('Name', 'id')
                ->format(function ($value, $row) {
                    $column = 'landlease.id';
                    $landLease = LandLease::select('category', 'business_location_id', 'taxpayer_id', 'name')->find($row[$column]);
                    if(is_null($landLease)){
                        return 'N/A';
                    }
                    if ($landLease->category == 'business') {
                        return $this->getBusinessName($landLease->business_location_id);
                    } else {
                        if ($landLease->is_registered == 1) {
                            return $this->getApplicantName($landLease->taxpayer_id);
                        } else {
                            return $landLease->name;
                        }
                    }
                })
                ->sortable(),
            Column::make('Payment Year', 'financialyear.code')
                ->format(function ($value, $row) {
                    $column = 'financialyear.code';
                    return $row[$column];
                })
                ->searchable()
                ->sortable(),
            Column::make('Payment Month', 'landlease.payment_month')
                ->format(function ($value, $row) {
                    $column = 'landlease.payment_month';
                    return $row[$column];
                })
                ->searchable()
                ->sortable(),
            Column::make('Applicant Type', 'landlease.category')
                ->format(function ($value, $row) {
                    return ucwords($row['landlease.category']);
                })
                ->searchable()
                ->sortable(),
            Column::make('Status', 'status')->view('land-lease.includes.lease-payment-status'),
            Column::make('Payment Amount (USD)', 'landlease.payment_amount')
                ->format(function ($value, $row) {
                    return number_format($row['landlease.payment_amount'], 2);
                })
                ->sortable(),
            Column::make('Total Amount (USD)', 'total_amount_with_penalties')
                ->format(function ($value, $row) {
                    return number_format($row['total_amount_with_penalties'], 2);
                })
                ->sortable(),
            Column::make('Total Penalties (USD)', 'penalty')
                ->format(function ($value, $row) {
                    return number_format($row['penalty'], 2);
                })
                ->sortable(),
            Column::make('Outstanding_amount (USD)', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($row['outstanding_amount'], 2);
                })
                ->sortable(),
            Column::make('Contact Person', 'landlease.created_at')
                ->format(
                    function ($value, $row) {
                        $landLease = LandLease::select('is_registered', 'taxpayer_id', 'name')->find($row['landlease.id']);
                        if(is_null($landLease)){
                            return 'N/A';
                        }
                        if ($landLease->is_registered == 1) {
                            $taxpayer = Taxpayer::select('first_name', 'last_name')->find($landLease->taxpayer_id);
                            if(is_null($taxpayer)){
                                abort(404);
                            }
                            return $taxpayer->first_name .' '. $taxpayer->last_name;
                        } else {
                            return $landLease->name;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Phone Number', 'landLease.updated_at')
                ->format(
                    function ($value, $row) {
                        $landLease = LandLease::select('is_registered', 'taxpayer_id', 'phone')->find($row['landlease.id']);
                        if ($landLease->is_registered == 1) {
                            $taxpayer = Taxpayer::select('mobile')->find($landLease->taxpayer_id);
                            if (is_null($taxpayer)){
                                return 'N/A';
                            }
                            return $taxpayer->mobile;
                        } else {
                            return $landLease->phone;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Actions', 'landlease.id')->view('land-lease.includes.actions'),
        ];
    }

    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find($id);

        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

    public function getBusinessName($id)
    {
        $businessLocation = BusinessLocation::find($id);
        if (is_null($businessLocation)){
            return 'N/A';
        }
        return $businessLocation->business->name . ' | ' . $businessLocation->name;
    }

    public function getApplicantNo($id)
    {
        $taxpayer = Taxpayer::find($id);
        if (is_null($taxpayer)){
            return 'N/A';
        }
        return $taxpayer->reference_no;
    }

    public function getBusinessZin($id)
    {
        $businessLocation = BusinessLocation::find($id);
        if (is_null($businessLocation)){
            return 'N/A';
        }
        return $businessLocation->zin;
    }
}
