<?php

namespace App\Http\Livewire\Returns\StampDuty;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnFilterTrait;

class StampDutyReturnsTable extends DataTableComponent
{
    use  ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    public $data         = [];

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

    public function builder(): Builder
    {
        $filter = (new StampDutyReturn)->newQuery();

        $returnTable = StampDutyReturn::getTableName();

        $filter = $this->dataFilter($filter, $this->data, $returnTable);

        return $filter->with('business')->orderBy('stamp_duty_returns.created_at', 'DESC');
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
            Column::make('Total Tax', 'total_amount_due')
                ->sortable()
                ->searchable(),
            Column::make('Financial Year', 'financialYear.name')
                ->sortable()
                ->searchable(),
            Column::make('Financial Month', 'financialMonth.name')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')->view('returns.stamp-duty.includes.actions'),
        ];
    }
}
