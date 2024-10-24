<?php

namespace App\Http\Livewire\NonTaxResident;

use App\Models\Ntr\NtrBusinessUpdate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationUpdatesTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return NtrBusinessUpdate::orderBy('ntr_business_updates.created_at', 'desc');
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
            Column::make('Name', "business.name")
                ->searchable(),
            Column::make('ZTN Number', "business.ztn_location_number"),
            Column::make('VRN #', "business.vrn"),
            Column::make('Country of Residence', "business.country.name"),
            Column::make('Updated By', "ntr_taxpayer_id")
                ->format(function ($value, $row) {
                    return $row->taxpayer->full_name ?? 'N/A';
                }),
            Column::make('Updated Date', "created_at")
                ->format(function ($value) {
                    if ($value) {
                        return Carbon::create($value)->format('d M, Y H:i');
                    }
                    return 'N/A';
                }),
            Column::make('Action', 'id')->view('non-tax-resident.updates.includes.actions'),
        ];
    }



}
