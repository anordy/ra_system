<?php

namespace App\Http\Livewire\LandLease;

use App\Models\BusinessLocation;
use App\Models\LandLease;
use App\Models\Taxpayer;
use App\Traits\WithSearch;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LandLeaseList extends DataTableComponent
{
    use WithSearch;
    //create builder function
    public function builder(): builder
    {
        return LandLease::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
        $this->setAdditionalSelects(['land_leases.name', 'land_leases.phone', 'taxpayer_id']);
    }

    public function columns(): array
    {
        return [
            Column::make("DP Number", "dp_number")
                ->searchable()
                ->sortable(),
            Column::make("Name", "business_location_id")
                ->format(
                    function ($value, $row) {
                        if ($row->category == 'business') {
                            return $this->getBusinessName(encrypt($value));
                        } else {
                            if ($row->is_registered == 1) {
                                return $this->getApplicantName(encrypt($row->taxpayer_id));
                            } else {
                                return $row->name;
                            }
                        }
                    }
                )
                ->sortable(),
            Column::make("Applicant Type", "category")
                ->format(function ($value) {
                    return ucwords($value);
                })
                ->searchable()
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
            Column::make("Region", "region.name")
                ->searchable()
                ->sortable(),
            Column::make("District", "district.name")
                ->searchable()
                ->sortable(),
            Column::make("Ward", "ward.name")
                ->searchable()
                ->sortable(),
            Column::make("Applicant Status", "is_registered")->view("land-lease.includes.applicant-status"),
            Column::make("Actions", "id")
            ->view("land-lease.includes.actions"),
        ];
    }

    public function getApplicantName($id)
    {
        $taxpayer = Taxpayer::find(decrypt($id));
        if(is_null($taxpayer)){
            abort(404);
        }
        return $taxpayer->first_name . ' ' . $taxpayer->last_name;
    }

    public function getBusinessName($id)
    {
        $businessLocation = BusinessLocation::find(decrypt($id));
        if(is_null($businessLocation)){
            abort(404);
        }
        return $businessLocation->business->name . ' | ' . $businessLocation->name;
    }
}
