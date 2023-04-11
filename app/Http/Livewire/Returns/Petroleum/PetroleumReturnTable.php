<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnFilterTrait;
use App\Traits\WithSearch;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;

class PetroleumReturnTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait, WithSearch;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function mount()
    {
        if (!Gate::allows('return-petroleum-return-view')) {
            abort(403);
        }
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        $returnTable = PetroleumReturn::getTableName();
        $filter      = (new PetroleumReturn)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->orderBy('petroleum_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Branch Name', 'businessLocation.name')
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
