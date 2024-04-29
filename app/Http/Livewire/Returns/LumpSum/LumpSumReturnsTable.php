<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Models\Returns\LumpSum\LumpSumReturn;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\ReturnFilterTrait;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LumpSumReturnsTable extends DataTableComponent
{
    use CustomAlert, ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];

    public $data = [];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class'   => 'table-bordered table-sm',
        ]);
    }

    public function filterData($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function filterCard($data)
    {
        $this->data = $data;
        $this->emit('$refresh');
    }

    public function builder(): Builder
    {
        $returnTable = LumpSumReturn::getTableName();
        $filter      = (new LumpSumReturn)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->orderBy('lump_sum_returns.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Taxpayer Name', 'business.taxpayer_name')
            ->format(function ($value, $row) {
                return $value ?? 'N/A';
            })
            ->sortable()->searchable(),
            Column::make('Business Name', 'business.name')
                ->sortable()
                ->searchable(),
            Column::make('Branch / Location', 'businessLocation.name')
            ->sortable()
            ->searchable(),
            Column::make('TIN', 'business.tin')
                ->sortable()
                ->searchable(),
            Column::make('Financial Year', 'financialyear.name')
                ->sortable()
                ->searchable(),
            Column::make('Quarter Of', 'quarter_name')
               ->sortable()
                ->searchable(),
            Column::make('Amount', 'total_amount_due_with_penalties')
                ->sortable()
                ->searchable(),
            Column::make('Control No', 'id')
            ->label(fn ($row) => $row->tax_return->bill->control_number)
            ->searchable(),
            Column::make('Status', 'status')
            ->view('returns.lump-sum.status')
                ->searchable()
                ->sortable(),
            Column::make('Action', 'id')
                ->view('returns.lump-sum.actions'),
        ];
    }
}
