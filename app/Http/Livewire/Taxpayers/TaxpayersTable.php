<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TaxpayersTable extends DataTableComponent
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
        return Taxpayer::query()->with('country', 'region')
            ->orderBy('taxpayers.id', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Reference No.', 'reference_no')
                ->searchable()
                ->sortable(),            
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
            Column::make('Nationality', 'country_id')
                ->format(fn($value, $row) => $row->country->nationality ?? ''),
            Column::make('Location', 'region.name'),
            Column::make('Street', 'street'),
            Column::make('Action', 'id')->view('taxpayers.actions')
        ];
    }

}