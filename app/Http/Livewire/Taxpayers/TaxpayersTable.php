<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxpayersTable extends DataTableComponent
{
    public function mount()
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->perPageAccepted = [15, 25, 50];

        // Takes a callback that gives you the current column.
        $this->setThAttributes(function(Column $column) {
            if ($column->isField('id')) {
                return [
                    'width' => '10',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function(Column $column) {
            if ($column->isField('id')) {
                return [
                    'class' => 'text-center'
                ];
            }

            return [];
        });
    }

    public function builder(): Builder
    {
        return Taxpayer::query()->with('country', 'region')->select('first_name', 'middle_name', 'last_name', 'taxpayers.id')->orderBy('taxpayers.id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('S/N', 'id')->format(function () {
                return ++$this->index;
            }),
            Column::make('Reference No.', 'reference_no')
                ->searchable()
                ->sortable(),            Column::make('Name', 'first_name')
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm){
                    return $query
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                })
                ->format(function($value, $row){
                    return "{$row->first_name} {$row->middle_name} {$row->last_name}";
                }),
            Column::make('Mobile No', 'mobile'),
            Column::make('Email Address', 'email'),
            Column::make('Nationality', 'country.nationality'),
            Column::make('Location', 'region.name'),
            Column::make('Street', 'street'),
            Column::make('Action', 'first_name')->view('taxpayers.actions')
        ];
    }

}