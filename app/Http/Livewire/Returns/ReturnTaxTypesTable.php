<?php

namespace App\Http\Livewire\Returns;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxType;

class ReturnTaxTypesTable extends DataTableComponent
{
    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return TaxType::query()->where('category', '=','main');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable()->searchable(),
            Column::make('Code', 'code')
                ->sortable()->searchable(),
            Column::make('Category', 'category')
                ->sortable()->searchable(),
            Column::make('GFS Code', 'gfs_code')
                ->sortable()->searchable(),
            Column::make('Action', 'id')
                ->view('returns.includes.actions'),

        ];
    }
}
