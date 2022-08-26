<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PortReturnTable extends DataTableComponent
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
        return PortReturn::query();
    }

    public function columns(): array
    {
        return [
           Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),

            Column::make('Infrastructure', 'infrastructure_tax')
                ->sortable(),
            Column::make('Infrastructure(ZNZ-TM)', 'infrastructure_znz_tm')
                ->sortable(),
            Column::make('Infrastructure(ZNZ-ZNZ)', 'infrastructure_znz_znz')
                ->sortable(),
            Column::make('Total VAT', 'total_amount_due')
                ->sortable()
                ->searchable(),
            Column::make('Payment Status', 'status')
                ->hideif(true),
            Column::make('Status', 'id')->view('returns.port.includes.status'),
            Column::make("Action", "id")
                ->view('returns.port.includes.actions'),

        ];
    }

}
