<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseReportTable extends DataTableComponent
{
    use LivewireAlert;

    public $dates = [];

    protected $listeners = ['refreshTable' => 'refreshTable', 'test'];

    public function builder(): Builder
    {

        $dates = $this->dates;

        if ($dates == []) {
            return LandLease::query()->orderBy('land_leases.created_at', 'asc');
        }

        if ($dates['startDate'] == null || $dates['endDate'] == null) {
            return LandLease::query()->orderBy('land_leases.created_at', 'asc');
        }

        return LandLease::query()->whereBetween('land_leases.created_at', [$dates['startDate'], $dates['endDate']])->orderBy('land_leases.created_at', 'asc');

    }

    public function refreshTable($dates)
    {
        $this->dates = $dates;
        $this->builder();
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'is_registered', 'taxpayer_id', 'land_leases.created_at']);
    }

    public function columns(): array
    {
        return [
            Column::make("Register Date", "created_at")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),
            Column::make("DP Number", "dp_number")
                ->searchable()
                ->sortable(),
            Column::make("Name", "name")
                ->format(
                    function ($value, $row) {
                        if ($row->is_registered == 1) {
                            return $this->getApplicantName($row->taxpayer_id);
                        } else {
                            return $value;
                        }
                    }
                )
                ->sortable(),

            Column::make("Commence Date", "commence_date")
                ->format(function ($value, $row) {
                    return date('d/m/Y', strtotime($value));
                })
                ->searchable()
                ->sortable(),

            Column::make("Payment Month", "payment_month")
                ->searchable()
                ->sortable(),
            Column::make('Payment Amount (USD)', 'payment_amount')
                ->format(function ($value, $row) {
                    return number_format($value);
                })
                ->sortable(),
            Column::make('Review Shedule', 'review_schedule')
                ->format(function ($value, $row) {
                    return $value . ' years';
                })
                ->searchable()
                ->sortable(),
            // Column::make("Region", "region.name")
            //     ->searchable()
            //     ->sortable(),
            // Column::make("District", "district.name")
            //     ->searchable()
            //     ->sortable(),
            // Column::make("Ward", "ward.name")
            //     ->searchable()
            //     ->sortable(),
            Column::make("Applicant Type", "id")->view("land-lease.includes.applicant-status"),
            Column::make('ZRB No.', 'taxpayer.reference_no')
                ->format(function ($value, $row) {
                    if($value == null){
                        return 'N/A';
                    }else{
                        return $value;
                    }
                })
                ->searchable()
                ->sortable(),
            Column::make("Actions", "id")->view("land-lease.includes.actions"),
        ];
    }

    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find($id);
        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

}
