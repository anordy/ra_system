<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\KYC;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
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
        return KYC::query()->with('country')->select('first_name', 'middle_name', 'last_name', 'kycs.id');
    }

    public function columns(): array
    {
        return [
            Column::make('Reference No', 'reference_no')
                ->searchable(),
            Column::make('Full Name', 'first_name')
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                })
                ->format(function ($value, $row) {
                    return "{$row->first_name} {$row->middle_name} {$row->last_name}";
                }),
            Column::make('Mobile No', 'mobile')->searchable(),
            Column::make('Email Address', 'email')->searchable(),
            Column::make('Nationality', 'country.nationality'),
            Column::make('Location', 'location'),
            Column::make('Action', 'first_name')->view('taxpayers.registrations.actions')
        ];
    }
}
