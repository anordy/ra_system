<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumReturn;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class PetroleumReturnTable extends DataTableComponent
{

    public function mount(){
        if (!Gate::allows('return-petroleum-return-view')) {
            abort(403);
        }
    }

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
        return PetroleumReturn::query()->where('filed_by_id', auth()->user()->id)->orderBy('petroleum_returns.created_at', 'desc');
    }


    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Tax Type', 'taxtype.name')
                ->sortable()
                ->searchable(),
            Column::make('Petroleum Levy', 'petroleum_levy')
                ->sortable()
                ->searchable(),  
            Column::make('Infrastructure Tax', 'infrastructure_tax')
                ->sortable()
                ->searchable(),  
            Column::make('RDF', 'rdf_tax')
                ->sortable()
                ->searchable(),
            Column::make('Road Licence Fee', 'road_lincence_fee')
                ->sortable()
                ->searchable(),
            Column::make('Total VAT', 'total_amount_due')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('returns.petroleum.filing.includes.actions'),

        ];
    }
}
