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
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LeasePaymentReportTable extends DataTableComponent
{
    use LivewireAlert, LeasePaymentReportTrait;

    public $dates = [];
    public $status;
    public $date_type;

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function builder(): Builder
    {
        $dates = $this->dates;
        $status = $this->status;
        $date_type = $this->date_type;

        if ($dates == []) {
            $model = LeasePayment::query();
        } elseif ($dates['startDate'] == null || $dates['endDate'] == null) {
            $model = LeasePayment::query();
        } else {

            if ($this->date_type == 'payment_month') {
                $months = $this->getMonthList($dates);
                $model = LeasePayment::query()
                ->leftJoin('land_leases', 'land_leases.id', 'lease_payments.land_lease_id')
                ->whereIn("land_leases.{$this->date_type}", $months);

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

        return $model->orderBy('lease_payments.created_at', 'asc');
    }

    public function refreshTable($parameter)
    {
        $this->dates = $parameter['dates'];
        $this->status = $parameter['status'];
        $this->date_type = $parameter['date_type'];
        $this->builder();
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        // $this->setAdditionalSelects(['lease_payments.name', 'lease_payments.phone', 'is_registered', 'taxpayer_id', 'lease_payments.created_at', 'lease_payments.business_location_id']);
    }

    public function columns(): array
    {
        return [
            Column::make('DP Number', 'landLease.dp_number')
                ->searchable()
                ->sortable(),
            Column::make('Name', 'landLease.businessLocation.id')
                ->format(function ($value, $row) {
                    $landLease = LandLease::find($row->id);
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
            Column::make('Payment Year', 'financialYear.code')
                ->format(function ($value, $row) {
                    return $value;
                })
                ->searchable()
                ->sortable(),
            Column::make('Payment Month', 'landLease.payment_month')
                ->searchable()
                ->sortable(),
            Column::make('Applicant Type', 'landLease.category')
                ->format(function ($value) {
                    return ucwords($value);
                })
                ->searchable()
                ->sortable(),
            Column::make('Status', 'status')->view('land-lease.includes.lease-payment-status'),
            Column::make('Payment Amount (USD)', 'landLease.payment_amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->sortable(),
            Column::make('Total Amount (USD)', 'total_amount_with_penalties')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->sortable(),
            Column::make('Total Penalties (USD)', 'penalty')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->sortable(),
            Column::make('Outstanding_amount (USD)', 'outstanding_amount')
                ->format(function ($value, $row) {
                    return number_format($value, 2);
                })
                ->sortable(),
            Column::make('Contact Person', 'landLease.id')
                ->format(
                    function ($value, $row) {
                        $landLease = LandLease::find($value);
                        if ($landLease->is_registered == 1) {
                            $taxpayer = Taxpayer::find($landLease->taxpayer_id);
                            return $taxpayer->first_name .' '. $taxpayer->last_name;
                        } else {
                            return $landLease->name;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Phone Number', 'id')
                ->format(
                    function ($value, $row) {
                        $landLease = LandLease::find($value);
                        if ($landLease->is_registered == 1) {
                            $taxpayer = Taxpayer::find($landLease->taxpayer_id);
                            return $taxpayer->mobile;
                        } else {
                            return $landLease->phone;
                        }
                    }
                )
                ->searchable()
                ->sortable(),
            Column::make('Actions', 'id')->view('land-lease.includes.actions'),
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

        return $businessLocation->business->name . ' | ' . $businessLocation->name;
    }

    public function getApplicantNo($id)
    {
        $taxpayer = Taxpayer::find($id);

        return $taxpayer->reference_no;
    }

    public function getBusinessZin($id)
    {
        $businessLocation = BusinessLocation::find($id);
        // dd($businessLocation);
        return $businessLocation->zin;
    }
}
