<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseList extends DataTableComponent
{
    // protected $model = LandLease::class;

    //create builder function
    public function builder(): builder
    {
        // return LandLease::where('created_by', auth()->user()->id);
        return LandLease::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'is_registered', 'taxpayer_id']);
    }

    public function columns(): array
    {
        return [
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
            // Column::make('Review Shedule', 'review_schedule')
            //     ->format(function ($value, $row) {
            //         return $value . ' years';
            //     })
            //     ->searchable()
            //     ->sortable(),
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
            Column::make("Actions", "id")->view("land-lease.includes.actions"),
        ];
    }

    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find($id);
        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

    public function getMonthLeases()
    {
        //get this month leases
        $month_leases = LandLease::where('created_by', auth()->user()->id)
            ->whereMonth('commence_date', date('m'))
            ->whereYear('commence_date', date('Y'))
            ->get();
        return $month_leases;
    }
}
