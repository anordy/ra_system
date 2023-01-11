<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\KYC;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RegistrationsTable extends DataTableComponent
{

    public function mount()
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['first_name', 'middle_name', 'last_name']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return KYC::query()->with('country', 'region','street');
    }

    public function columns(): array
    {
        return [

            Column::make('Name')
                ->label(fn ($row) => $row->fullname())
                ->sortable()
                ->searchable(function (Builder $query, $searchTerm) {
                    return $query
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                }),
            Column::make('Mobile No', 'mobile'),
            Column::make('Email Address', 'email'),
            Column::make('Nationality', 'country.nationality'),
            Column::make('Location', 'region.name'),
            Column::make('Street', 'street.name'),
            Column::make('Action', 'id')->view('taxpayers.registrations.actions')
        ];
    }
}
