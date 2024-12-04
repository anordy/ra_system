<?php

namespace App\Http\Livewire\NonTaxResident;

use App\Models\Ntr\NtrBusinessDeregistration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DeRegistrationsTable extends DataTableComponent
{

    public function mount() {
    }

    public function builder(): Builder
    {
        return NtrBusinessDeregistration::orderBy('ntr_business_deregistrations.created_at', 'DESC');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');

        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', "business.name")->searchable(),
            Column::make('ZTN', "business.ztn_location_number")->searchable(),
            Column::make('VRN', "business.vrn")->searchable(),
            Column::make('Country', "business.country.name")->searchable(),
            Column::make('Reason', "reason"),
            Column::make('Requested Date', "created_at")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y');
                    }
                    return 'N/A';
                }),
            Column::make('Approved Date', "approved_on")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y');
                    }
                    return 'N/A';
                }),
            Column::make('Rejected Date', "rejected_on")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y');
                    }
                    return 'N/A';
                }),
            Column::make('Status', 'status')->view('non-tax-resident.de-registrations.includes.status'),
            Column::make('Action', 'id')->view('non-tax-resident.de-registrations.includes.actions'),
        ];
    }



}
