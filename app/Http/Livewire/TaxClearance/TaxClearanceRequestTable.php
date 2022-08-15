<?php

namespace App\Http\Livewire\TaxClearance;

use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxClearanceRequestTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

    }

    public function builder(): Builder
    {
        return BusinessLocation::query()->leftJoin('businesses', 'businesses.id', '=', 'business_locations.business_id' )->where('businesses.status', 'approved');
    }

    
    public function columns(): array
    {
        return [
            Column::make('ZIN', 'zin')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make("Action", "id")
                ->view('tax-clearance.includes.actions'),

        ];
    }
}
