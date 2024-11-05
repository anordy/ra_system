<?php

namespace App\Http\Livewire\NonTaxResident;

use App\Enum\NonTaxResident\NtrApplicationType;
use App\Enum\NonTaxResident\NtrBusinessType;
use App\Models\Ntr\NtrBusiness;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return NtrBusiness::orderBy('ntr_businesses.created_at', 'desc');
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
            Column::make('Name', "name")
                ->searchable(),
            Column::make('ZTN Number', "ztn_location_number"),
            Column::make('VRN #', "vrn"),
            Column::make('Email', "email"),
            Column::make('Country of Residence', "country.name"),
            Column::make('Nature of Business', "nature.name"),
            Column::make('Registrar', "ntr_taxpayer_id")
                ->format(function ($value, $row) {
                  return $row->taxpayer->full_name ?? 'N/A';
                }),
            Column::make('Business Type', "business_type")
                ->format(function ($value) {
                    if ($value == NtrBusinessType::ECOMMERCE) {
                        return 'E-commerce';
                    } else if ($value == NtrBusinessType::NON_RESIDENT) {
                        return 'Non Resident';
                    } else {
                        return 'N/A';
                    }
                }),
            Column::make('Registration Date', "created_at")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y');
                    }
                    return 'N/A';
                }),
            Column::make('Status', 'status')->view('non-tax-resident.business.includes.status'),
            Column::make('Action', 'id')->view('non-tax-resident.business.includes.actions'),
        ];
    }



}
