<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\KYC;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxpayersTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return Taxpayer::query()->with('country')->select('first_name', 'middle_name', 'last_name', 'taxpayers.id');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'first_name')
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
            Column::make('Location', 'location'),
            Column::make('Action', 'first_name')->view('taxpayers.actions')
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }
}